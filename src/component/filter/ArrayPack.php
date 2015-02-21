<?php

namespace cotcot\component\filter;

/**
 * ArrayPack filter.
 * Filter an array by keeping non-null elements.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ArrayPack extends Filter {

    public function filter($value) {
        if (is_array($value)) {
            return array_filter($value, function($itemValue) {
                return $itemValue !== null;
            });
        }
        return null;
    }

}
