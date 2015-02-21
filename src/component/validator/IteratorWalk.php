<?php

namespace cotcot\component\validator;

/**
 * Iterator walk validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IteratorWalk extends \cotcot\component\validator\Validator {

    /** @var \cotcot\component\validator\Validator validators */
    public $validators = array();

    protected function validate($value, $context) {
        if (is_array($value) || ($value instanceof \ArrayAccess && $value instanceof \Iterator)) {
            $result = true;
            foreach ($value as $itemValue) {
                foreach ($this->validators as $validator) {
                    $brokenChain = false;
                    try {
                        $validatorResult = $validator->isValid($itemValue, $context);
                    } catch (\cotcot\component\exception\BreakChainException $ex) {
                        $validatorResult = $ex->status;
                        $brokenChain = true;
                    }
                    $result = $result && $validatorResult;
                    $this->addMessage($validator->getMessages());
                    if (!$validatorResult || $brokenChain) {
                        break;
                    }
                }
            }
            return $result;
        }
        return false;
    }

}
