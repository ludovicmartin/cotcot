<?php

namespace cotcot\component\filter;

/**
 * Filter.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Filter {

    /**
     * Apply filter to a value.
     * @param mixed $value value to filter
     * @return mixed fitered value
     */
    public abstract function filter($value);
}
