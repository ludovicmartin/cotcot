<?php

namespace cotcot\component\validator;

/**
 * Is string validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsString extends Validator {

    protected function validate($value, $context) {
        return is_string($value);
    }

}
