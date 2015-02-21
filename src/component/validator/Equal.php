<?php

namespace cotcot\component\validator;

/**
 * Equal validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Equal extends Validator {

    const EQUAL = 0;
    const NOT_EQUAL = 1;

    /** @var boolean strict comparaison */
    public $strict = false;

    /** @var mixed reference value */
    public $value = null;

    /** @var mixed reference value */
    public $type = self::EQUAL;

    protected function validate($value, $context) {
        $result = $this->strict ? $value === $this->value : $value == $this->value;
        return $this->type == self::EQUAL ? $result : (!$result);
    }

}
