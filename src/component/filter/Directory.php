<?php

namespace cotcot\component\filter;

/**
 * Filter directory.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Directory extends \cotcot\tools\Directory {

    public function init() {
        $this->defaultItems = array(
            'callback' => array('classname' => '\cotcot\component\filter\Callback', 'singleton' => false),
            'each' => array('classname' => '\cotcot\component\filter\IteratorWalk', 'singleton' => false),
            'toLower' => array('classname' => '\cotcot\component\filter\StringCase', 'singleton' => false, 'attributes' => array('type' => \cotcot\component\filter\StringCase::TYPE_TO_LOWER)),
            'toUpper' => array('classname' => '\cotcot\component\filter\StringCase', 'singleton' => false, 'attributes' => array('type' => \cotcot\component\filter\StringCase::TYPE_TO_UPPER)),
            'trim' => array('classname' => '\cotcot\component\filter\StringTrim', 'singleton' => false),
            'lTrim' => array('classname' => '\cotcot\component\filter\StringTrim', 'singleton' => false, 'attributes' => array('type' => \cotcot\component\filter\StringTrim::TYPE_LEFT)),
            'rTrim' => array('classname' => '\cotcot\component\filter\StringTrim', 'singleton' => false, 'attributes' => array('type' => \cotcot\component\filter\StringTrim::TYPE_LEFT)),
            'nullIfEmpty' => array('classname' => '\cotcot\component\filter\EmptyToNull', 'singleton' => false),
            'explode' => array('classname' => '\cotcot\component\filter\Explode', 'singleton' => false),
            'pregExplode' => array('classname' => '\cotcot\component\filter\Explode', 'singleton' => false, 'attributes' => array('type' => Explode::TYPE_REGEXP)),
            'arrayPack' => array('classname' => '\cotcot\component\filter\ArrayPack', 'singleton' => false),
            'toArray' => array('classname' => '\cotcot\component\filter\ToArray', 'singleton' => false)
        );
        parent::init();
    }

    /**
     * Get a filter.
     * @param string $name filter name
     * @param string $attributes custom attributes merged with configuration
     * @return Filter filter
     */
    public function getFilter($name, $attributes = null) {
        return $this->getItem($name, $attributes);
    }

}
