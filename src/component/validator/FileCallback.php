<?php

namespace cotcot\component\validator;

/**
 * File callback validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FileCallback extends FileValidator {

    /** @var callable custom validation function */
    public $callbackFunction = null;

    protected function validate($value, $context) {
        if (parent::validate($value, $context)) {
            $callbackFunction = $this->callbackFunction;
            return $callbackFunction($value, $context);
        }
        return false;
    }

}
