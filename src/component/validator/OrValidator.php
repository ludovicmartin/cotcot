<?php

namespace cotcot\component\validator;

/**
 * Or validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class OrValidator extends Validator {

    /** @var Validator validators to apply */
    public $validators = array();

    /** @var boolean stop on first validated filter */
    public $lazy = true;

    protected function validate($value, $context) {
        $result = false;
        foreach ($this->validators as $validator) {
            $result = $validator->isValid($value, $context) || $result;
            $this->addMessage($validator->getMessages());
            if ($this->lazy && $result) {
                return true;
            }
        }
        return $result;
    }

}
