<?php

namespace cotcot\component\filter;

/**
 * String trim filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class StringTrim extends Filter {

    const TYPE_LEFT = 0;
    const TYPE_RIGHT = 1;
    const TYPE_BOTH = 2;

    /** @var int trim type */
    public $type = self::TYPE_BOTH;

    /** @var string char list to trim */
    public $charList = " \t\n\r\0\x0B";

    public function filter($value) {
        if (is_scalar($value)) {
            switch ($this->type) {
                case self::TYPE_LEFT:
                    return ltrim($value, $this->charList);
                case self::TYPE_RIGHT:
                    return rtrim($value, $this->charList);
                case self::TYPE_BOTH:
                    return trim($value, $this->charList);
            }
        }
        return null;
    }

}
