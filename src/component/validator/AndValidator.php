<?php

namespace cotcot\component\validator;

/**
 * And validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class AndValidator extends Validator {

    /** @var Validator validators to apply */
    public $validators = array();

    /** @var boolean stop on first validation error */
    public $lazy = true;

    protected function validate($value, $context) {
        $result = true;
        foreach ($this->validators as $validator) {
            $result = $validator->isValid($value, $context) && $result;
            $this->addMessage($validator->getMessages());
            if ($this->lazy && !$result) {
                return false;
            }
        }
        return $result;
    }

}
