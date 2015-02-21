<?php

namespace cotcot\component\validator;

/**
 * CSRF validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Csrf extends Validator {

    /** @var \cotcot\component\web\csrf\CsrfManager CSRF manager */
    public $csrfManager;

    protected function validate($value, $context) {
        return is_scalar($value) && $this->csrfManager->validateKey($value);
    }

}
