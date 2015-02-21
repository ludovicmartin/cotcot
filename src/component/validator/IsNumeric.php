<?php

namespace cotcot\component\validator;

/**
 * Is numeric validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsNumeric extends Validator {

    protected function validate($value, $context) {
        return is_numeric($value);
    }

}
