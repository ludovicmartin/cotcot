<?php

namespace cotcot\component\validator;

/**
 * String length validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class StringLength extends Validator {

    /** @var int min length */
    public $min = null;

    /** @var int max length */
    public $max = null;

    /** @var boolean min and max are included */
    public $inclusive = true;

    /** @var boolean multi-byte string */
    public $multiByteString = true;

    protected function validate($value, $context) {
        if (is_scalar($value)) {
            $length = $this->multiByteString ? mb_strlen($value) : strlen($value);
            return $this->inclusive ?
                    (($this->min === null || $length >= $this->min) && ($this->max === null || $length <= $this->max)) :
                    (($this->min === null || $length > $this->min) && ($this->max === null || $length < $this->max));
        }
        return false;
    }

}
