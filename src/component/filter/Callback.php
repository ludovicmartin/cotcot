<?php

namespace cotcot\component\filter;

/**
 * Callback filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Callback extends Filter {

    /** @var callable custom filter function */
    public $callbackFunction = null;

    public function filter($value) {
        $callbackFunction = $this->callbackFunction;
        return $callbackFunction($value);
    }

}
