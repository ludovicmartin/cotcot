<?php

namespace cotcot\component\validator;

/**
 * Callback validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Callback extends Validator {

    /** @var callable custom validation function */
    public $callbackFunction = null;

    protected function validate($value, $context) {
        $callbackFunction = $this->callbackFunction;
        return $callbackFunction($value, $context);
    }

}
