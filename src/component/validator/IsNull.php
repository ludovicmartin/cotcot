<?php

namespace cotcot\component\validator;

/**
 * Is null validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsNull extends Validator {

    const IS_NULL = 0;
    const IS_NOT_NULL = 1;

    /** @var int validation type */
    public $type = self::IS_NULL;

    protected function validate($value, $context) {
        return $this->type == self::IS_NULL ? $value === null : $value !== null;
    }

}
