<?php

namespace cotcot\component\filter;

/**
 * Explode filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Explode extends Filter {

    const TYPE_STRING = 0;
    const TYPE_REGEXP = 1;

    /** @var int explode type */
    public $type = self::TYPE_STRING;

    /** @var string explode pattern */
    public $pattern = '';

    public function filter($value) {
        if (is_scalar($value)) {
            if (strlen($value) == 0) {
                return array();
            }
            return $this->type == self::TYPE_STRING ? explode($this->pattern, $value) : preg_split($this->pattern, $value);
        }
        return null;
    }

}
