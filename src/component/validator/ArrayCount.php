<?php

namespace cotcot\component\validator;

/**
 * Array count validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ArrayCount extends Validator {

    /** @var int min count */
    public $min = null;

    /** @var int max count */
    public $max = null;

    /** @var boolean min and max are included */
    public $inclusive = true;

    protected function validate($value, $context) {
        if (is_array($value) || $value instanceof \Countable) {
            $length = count($value);
            return $this->inclusive ? ($this->min === null || $length >= $this->min) && ($this->max === null || $length <= $this->max) :
                    ($this->min === null || $length > $this->min) && ($this->max === null || $length < $this->max);
        }
        return false;
    }

}
