<?php

namespace cotcot\component\validator;

/**
 * Is float validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsFloat extends Validator {

    protected function validate($value, $context) {
        return is_float($value);
    }

}
