<?php

namespace cotcot\component\validator;

/**
 * Required validator.
 * An empty value is not null, is a non-empty string or is a non-empty countable.
 * If hasn't content and not required, it breaks validator chain. * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Required extends Validator {

    const CONTENT_REQUIRED = 0;
    const CONTENT_NOT_REQUIRED = 1;

    /** @var int validation type */
    public $type = self::CONTENT_REQUIRED;

    /** @var boolean break chain flag, allow to break validation chain if value is empty et no content is required */
    public $breakChain = true;

    protected function validate($value, $context) {
        $hasContent = $value !== null && (is_bool($value) || (is_scalar($value) && strlen($value) > 0) || ((is_array($value) || $value instanceof \Countable) && count($value) > 0));
        if ($this->breakChain && !$hasContent && $this->type == self::CONTENT_NOT_REQUIRED) {
            //Value has no content and is not required, so we don't have to continue the validation chain
            throw new \cotcot\component\exception\BreakChainException(true);
        }
        return $hasContent || ($this->type == self::CONTENT_NOT_REQUIRED);
    }

}
