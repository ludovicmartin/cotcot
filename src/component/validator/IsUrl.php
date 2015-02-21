<?php

namespace cotcot\component\validator;

/**
 * Is URL validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsUrl extends Validator {

    protected function validate($value, $context) {
        return is_scalar($value) && filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

}
