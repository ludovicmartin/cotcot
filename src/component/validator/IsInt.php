<?php

namespace cotcot\component\validator;

/**
 * Is int validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsInt extends Validator {

    protected function validate($value, $context) {
        return is_int($value);
    }

}
