<?php

namespace cotcot\component\validator;

/**
 * Safe validator.
 * All values are valid and it breaks validator chain.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Safe extends Validator {

    protected function validate($value, $context) {
        return true;
    }

}
