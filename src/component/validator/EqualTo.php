<?php

namespace cotcot\component\validator;

/**
 * Equal to othe field validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class EqualTo extends Validator {

    const EQUAL = 0;
    const NOT_EQUAL = 1;

    /** @var boolean strict comparaison */
    public $strict = false;

    /** @var string reference field name */
    public $fieldName = null;

    /** @var mixed reference value */
    public $type = self::EQUAL;

    protected function validate($value, $context) {
        if (is_array($context) && is_array($context['values']) && key_exists($this->fieldName, $context['values'])) {
            $result = $this->strict ? $value === $context['values'][$this->fieldName] : $value == $context['values'][$this->fieldName];
            return $this->type == self::EQUAL ? $result : (!$result);
        }
        return false;
    }

}
