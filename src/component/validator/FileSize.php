<?php

namespace cotcot\component\validator;

/**
 * File size validator.
 * All values are valid and it breaks validator chain.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FileSize extends FileValidator {

    /** @var int min size */
    public $min = null;

    /** @var int max size */
    public $max = null;

    /** @var boolean min and max are included */
    public $inclusive = true;

    protected function validate($value, $context) {
        if (parent::validate($value, $context)) {
            $size = $value['size'];
            return $this->inclusive ?
                    (($this->min === null || $size >= $this->min) && ($this->max === null || $size <= $this->max)) :
                    (($this->min === null || $size > $this->min) && ($this->max === null || $size < $this->max));
        }
        return false;
    }

}
