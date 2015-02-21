<?php

namespace cotcot\component\filter;

/**
 * To array filter.
 * Convert any value to a single element array. If value is already an array, it is returned without any modification.
 * Null value will produce an empty array.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ToArray extends Filter {

    public function filter($value) {
        if (is_array($value)) {
            return $value;
        }
        return $value !== null ? array($value) : array();
    }

}
