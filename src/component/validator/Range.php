<?php

namespace cotcot\component\validator;

/**
 * Range validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Range extends Validator {

    /** @var int|float min value */
    public $min = null;

    /** @var int|float max value */
    public $max = null;

    /** @var boolean min and max are included */
    public $inclusive = true;

    protected function validate($value, $context) {
        if (is_scalar($value)) {
            return $this->inclusive ?
                    (($this->min === null || $value >= $this->min) && ($this->max === null || $value <= $this->max)) :
                    (($this->min === null || $value > $this->min) && ($this->max === null || $value < $this->max));
        }
        return false;
    }

}
