<?php

namespace cotcot\component\validator;

/**
 * In array validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class InArray extends Validator {

    /** @var array haystack */
    public $haystack = array();

    /** @var boolean strict comparaison switch */
    public $strict = false;

    protected function validate($value, $context) {
        return in_array($value, $this->haystack, $this->strict);
    }

}
