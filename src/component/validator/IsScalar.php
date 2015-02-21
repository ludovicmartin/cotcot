<?php

namespace cotcot\component\validator;

/**
 * Is scalar validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsScalar extends Validator {

    protected function validate($value, $context) {
        return is_scalar($value);
    }

}
