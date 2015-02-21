<?php

namespace cotcot\component\validator;

/**
 * String PREG match validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class StringPregMatch extends Validator {

    /** @var string regexp */
    public $pattern = null;

    protected function validate($value, $context) {
        return is_scalar($value) && preg_match($this->pattern, $value) > 0;
    }

}
