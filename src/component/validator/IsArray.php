<?php

namespace cotcot\component\validator;

/**
 * Is array validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsArray extends Validator {

    protected function validate($value, $context) {
        return is_array($value);
    }

}
