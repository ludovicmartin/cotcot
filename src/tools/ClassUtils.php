<?php

namespace cotcot\tools;

/**
 * Class utils.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ClassUtils {

    /**
     * Inject properties to an object.
     * @param \Object $object object
     * @param array $properties properties
     * @return void
     * @throws \Exception thrown if a property doesn't exist or object is not an object
     */
    public static function setProperties($object, $properties) {
        foreach ($properties as $key => $value) {
            self::setProperty($object, $key, $value);
        }
    }

    /**
     * Inject a property to an object.
     * @param \Object $object object
     * @param string $name name
     * @param mixed $value value
     * @return void
     * @throws \Exception thrown if property doesn't exist or object is not an object
     */
    public static function setProperty($object, $name, $value) {
        if (!is_object($object)) {
            throw new \Exception('$object parametter must be an object');
        }
        $accessorName = 'set' . lcfirst($name);
        if (method_exists($object, $accessorName)) {
            call_user_func(array($object, $accessorName), $value);
        } elseif (property_exists($object, $name)) {
            $object->$name = $value;
        } else {
            throw new \Exception('"' . $name . '" property does not exist');
        }
    }

    /**
     * Get object properties of an object.
     * @param \Object $object object
     * @param array $names name of properties to get
     * @return array property array
     */
    public static function getProperties($object, $names = null) {
        if (!is_object($object)) {
            throw new \Exception('$object parametter must be an object');
        }
        if ($names === null) {
            $classname = get_class($object);
            $names = array_keys(get_class_vars($classname));
            foreach (get_class_methods($classname) as $method) {
                if (strncmp('get', $method, 3) == 0 && strlen($method) > 3) {
                    $names[] = lcfirst(substr($method, 3));
                }
            }
        }
        $values = array();
        foreach ($names as $name) {
            $accessorName = 'get' . lcfirst($name);
            if (method_exists($object, $accessorName)) {
                $values[] = call_user_func(array($object, $accessorName));
            } elseif (property_exists($object, $name)) {
                $values[] = $object->$name;
            } else {
                throw new \Exception('"' . $name . '" property does not exist');
            }
        }
        return array_combine($names, $values);
    }

}
