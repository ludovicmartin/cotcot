<?php

namespace cotcot\component\validator;

/**
 * Is e-mail validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsEmail extends Validator {

    protected function validate($value, $context) {
        return is_scalar($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

}
