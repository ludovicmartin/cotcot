<?php

namespace cotcot\component\filter;

/**
 * String case filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class StringCase extends Filter {

    const TYPE_TO_UPPER = 0;
    const TYPE_TO_LOWER = 1;

    /** @var int trim type */
    public $type = self::TYPE_TO_UPPER;

    /** @var boolean multi-byte string */
    public $multiByteString = true;

    public function filter($value) {
        if (is_scalar($value)) {
            switch ($this->type) {
                case self::TYPE_TO_UPPER:
                    return $this->multiByteString ? mb_strtoupper($value) : strtoupper($value);
                case self::TYPE_TO_LOWER:
                    return $this->multiByteString ? mb_strtolower($value) : strtolower($value);
            }
        }
        return null;
    }

}
