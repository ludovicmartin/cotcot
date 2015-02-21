<?php

namespace cotcot\component\filter;

/**
 * Empty to null filter.
 * Convert empty array and empty string to null (note 0 int or float values are not empty strings).
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class EmptyToNull extends Filter {

    public function filter($value) {
        return $value !== null && (is_bool($value) || (is_scalar($value) && strlen($value) > 0) || ((is_array($value) || $value instanceof \Countable) && count($value) > 0)) ? $value : null;
    }

}
