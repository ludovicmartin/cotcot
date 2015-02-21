<?php

namespace cotcot\component\filter;

/**
 * Iterator walk filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IteratorWalk extends Filter {

    /** @var Filter filter to apply to each element */
    public $filter = null;

    public function filter($value) {
        if (is_array($value) || ($value instanceof \ArrayAccess && $value instanceof \Iterator)) {
            foreach ($value as $key => $itemValue) {
                $value[$key] = $this->filter->filter($itemValue);
            }
            return $value;
        }
        return null;
    }

}
