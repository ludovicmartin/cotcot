<?php

namespace cotcot\tools;

/**
 * Generic directory.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Directory implements \cotcot\core\Initializable {

    /** @var array items configuration (uses Cotcot context configuration format and can override defaultItems) */
    public $items = array();

    /** @var array default items configuration (uses Cotcot context configuration format) */
    protected $defaultItems = array();

    /** @var \cotcot\core\RuntimeContext context */
    private $runtimeContext;

    public function init() {
        $this->runtimeContext = new \cotcot\core\RuntimeContext(array('items' => array_merge($this->defaultItems, $this->items)));
    }

    /**
     * Get a validator.
     * @param string $name item name
     * @param string $attributes custom attributes merged with configuration    
     * @return Object item
     */
    public function getItem($name, $attributes = null) {
        return $this->runtimeContext->getComponent('items.' . $name, $attributes);
    }

}
