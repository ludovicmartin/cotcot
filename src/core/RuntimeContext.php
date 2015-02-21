<?php

namespace cotcot\core;

/**
 * Runtime context.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class RuntimeContext {

    const VERSION = '0.1';
    const APPLICATION_CONFIGURATION_INITIALIZERS = 'initializers';

    /** @var array component list instance cache */
    private $components = array();

    /** @var array configuration */
    private $configuration = null;

    /**
     * Constructs a runtime context.
     * @param array $configuration configuration :
     *   array(
     *      'initializers' => array(
     *       ...
     *      ),
     *      'abcd => array(
     *          'component_xxx => array(
     *              ...
     *          ),
     *          ...
     *      )
     *   )
     * 
     * Initializers and components are defined like this :
     *   array(
     *      'classname' => '\\myPackage\\MyClass',
     *      'attributes' => array(
     *          'attrib1' => 'value1',
     *          'attrib2' => 'value2'
     *      ),
     *      'singleton' => true,
     *      'components' => array(
     *          'var1' => 'abcd.component_xxx', //Inject "component_xxx" from 'abcd' category
     *          'var2' => 'abcd.component_yyy', //Inject "component_yyy" from 'abcd' category
     *          'var3' => '&abcd.component_zzz' //Inject configuration of "component_zzz" from 'abcd' category
     *      )
     *   )
     */
    public function __construct($configuration) {
        $this->configuration = $configuration;
        //Fix configuration
        foreach ($this->configuration as $categoryName => $components) {
            foreach ($components as $componentName => $componentConfiguration) {
                if (!is_object($componentConfiguration)) {
                    $this->configuration[$categoryName][$componentName] = $this->fixComponentNamesInConfiguration($componentConfiguration, $categoryName);
                }
            }
        }
        //Create and run initializers
        if (isset($this->configuration[self::APPLICATION_CONFIGURATION_INITIALIZERS])) {
            foreach ($this->configuration[self::APPLICATION_CONFIGURATION_INITIALIZERS] as $itemConfiguration) {
                $this->createObject($itemConfiguration);
            }
        }
    }

    /**
     * Get a component by name.
     * @param string $name component name in configuration (ex: "category.component_name")
     * @param string $attributes custom attributes merged with configuration
     * @param string $components custom components merged with configuration
     * @param boolean $runInit run init for initializable objects
     * @return \Object|null component or null if unknown
     * @throws \Exception on error
     */
    public function getComponent($name, $attributes = null, $components = null, $runInit = true) {
        //Parse component and category name
        $parts = $this->parseComponentName($name);
        $configurationCopyAsked = $parts['copy'];
        $categoryName = $parts['category'];
        $componentName = $parts['name'];
        //Wanted component exists ?
        if (isset($this->configuration[$categoryName][$componentName])) {
            $configuration = $this->configuration[$categoryName][$componentName];
            //Component is already an object OR configuration copy is wanted, just return it without any alteration
            if (is_object($configuration) || $configurationCopyAsked) {
                return $configuration;
            }
            $isSingleton = isset($configuration['singleton']) && $configuration['singleton'] === true;
            //Create object if needed
            if (!($isSingleton && isset($this->components[$name]))) {
                if (!isset($configuration['classname'])) {
                    throw new \Exception('classname not defined in configuration');
                }
                //Create object
                $component = new $configuration['classname']();
                //Put object into the cache before being initialized to make object available in case of cyclic dependency (only if is singleton)
                if ($isSingleton) {
                    $this->components[$name] = $component;
                }
                $configAttributes = isset($configuration['attributes']) && is_array($configuration['attributes']) ? $configuration['attributes'] : array();
                if ($attributes !== null && is_array($attributes)) {
                    $configAttributes = array_merge($configAttributes, $attributes);
                }
                $configComponents = isset($configuration['components']) && is_array($configuration['components']) ? $configuration['components'] : array();
                if ($components !== null && is_array($components)) {
                    $configComponents = array_merge($configComponents, $components);
                }
                try {
                    //Initialize object
                    $this->initializeObject(
                            $component
                            , empty($configAttributes) ? null : $configAttributes
                            , empty($configComponents) ? null : $configComponents
                            , $runInit
                    );
                } catch (\Exception $ex) {
                    //Clean component cache if error detected on initialization
                    if ($isSingleton) {
                        unset($this->components[$name]);
                    }
                    throw $ex;
                }
                return $component;
            }
            return $this->components[$name];
        }
        return null;
    }

    /**
     * Create an object.
     * A new object is created on each call.
     * If the object implements tue RuntimeContextAware interface, the runtime context is injected.
     * When object is created by configuration (array in the classname param), all others params are merged with configuration.
     * @param string|array $classnameOrConfiguration name of the class to instanciate or an array that contains configuration
     * @param array|null $attributes attributes
     * @param array|null $components components
     * @param boolean $runInit run init for initializable objects
     * @return \Object object
     * @throws \Exception on error
     */
    public function createObject($classnameOrConfiguration, $attributes = null, $components = null, $runInit = true) {
        //Detect classname data type
        if (is_array($classnameOrConfiguration)) {
            if (!isset($classnameOrConfiguration['classname'])) {
                throw new \Exception('classname not defined in configuration');
            }
            $classname = $classnameOrConfiguration['classname'];
            if (isset($classnameOrConfiguration['attributes']) && is_array($classnameOrConfiguration['attributes'])) {
                $attributes = array_merge($classnameOrConfiguration['attributes'], $attributes !== null && is_array($attributes) ? $attributes : array());
            }
            if (isset($classnameOrConfiguration['components']) && is_array($classnameOrConfiguration['components'])) {
                $components = array_merge($classnameOrConfiguration['components'], $components !== null && is_array($components) ? $components : array() );
            }
        } else {
            $classname = $classnameOrConfiguration;
        }
        //Create object
        $object = new $classname();
        //Initialize attributes and components
        $this->initializeObject($object, $attributes, $components, $runInit);
        return $object;
    }

    /**
     * Create an object.
     * A new object is created on each call.
     * If the object implements tue RuntimeContextAware interface, the runtime context is injected.
     * @param object $object object to initialize
     * @param array|null $attributes attributes
     * @param array|null $components components
     * @param boolean $runInit run init for initializable objects
     * @return void
     * @throws \Exception on error
     */
    private function initializeObject($object, $attributes = null, $components = null, $runInit = true) {
        //Inject attributes
        if ($attributes !== null && is_array($attributes)) {
            \cotcot\tools\ClassUtils::setProperties($object, $attributes);
        }
        //Inject components
        if ($components !== null && is_array($components)) {
            foreach ($components as $componentVarname => $componentName) {
                $component = null;
                if (is_array($componentName)) {
                    $component = array();
                    foreach ($componentName as $key => $componentNameItem) {
                        $componentItem = $this->getComponent($componentNameItem);
                        if ($componentItem === null) {
                            throw new \Exception('unknown component "' . $componentNameItem . '"');
                        }
                        $component[$key] = $componentItem;
                    }
                } else {
                    $component = $this->getComponent($componentName);
                }
                if ($component === null) {
                    throw new \Exception('unknown component "' . $componentName . '"');
                }
                if (is_numeric($componentVarname)) {
                    $dotIndex = strpos($componentName, '.');
                    $componentVarname = \cotcot\tools\VarUtils::buildVarName($dotIndex !== false ? substr($componentName, $dotIndex + 1) : $componentName);
                }
                \cotcot\tools\ClassUtils::setProperty($object, $componentVarname, $component);
            }
        }
        //Inject context
        if ($object instanceof RuntimeContextAware) {
            \cotcot\tools\ClassUtils::setProperty($object, 'runtimeContext', $this);
        }
        //Call init function
        if ($object instanceof Initializable && $runInit) {
            $object->init();
        }
        return;
    }

    /**
     * Parse component name.
     * @param string $componentName component name.
     * @return array parse result
     */
    private function parseComponentName($componentName) {
        $filteredName = ltrim($componentName, '@');
        $parts = explode('.', $filteredName, 2);
        if (count($parts) != 2) {
            throw new \Exception('bad component name (must be like "category.name" or "@category.name") "' . $componentName . '"');
        }
        return array(
            'copy' => strlen($componentName) !== strlen($filteredName),
            'category' => $parts[0],
            'name' => $parts[1]
        );
    }

    /**
     * Fix recursively a component configuration.
     * @param array $configuration configuration
     * @param string $categoryName categorie name to use for fix
     * @return array fixed configuration
     */
    private function fixComponentNamesInConfiguration($configuration, $categoryName) {
        if (isset($configuration['components']) && is_array($configuration['components'])) {
            foreach ($configuration['components'] as $variableName => $componentName) {
                if (is_array($componentName)) {
                    foreach ($componentName as $key => $componentNameItem) {
                        $configuration['components'][$variableName][$key] = $this->fixComponentName($componentNameItem, $categoryName);
                    }
                } elseif (!is_object($componentName)) {
                    $configuration['components'][$variableName] = $this->fixComponentName($componentName, $categoryName);
                }
            }
        }
        return $configuration;
    }

    /**
     * Fix a component name.
     * @param string $componentName
     * @param string $defaultCategoryName
     * @return string fixed name
     */
    private function fixComponentName($componentName, $defaultCategoryName) {
        if (strpos($componentName, '.') === false) {
            $filteredName = ltrim($componentName, '@');
            return (strlen($componentName) !== strlen($filteredName) ? '@' : '') . $defaultCategoryName . '.' . $filteredName;
        }
        return $componentName;
    }

}
