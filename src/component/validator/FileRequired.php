<?php

namespace cotcot\component\validator;

/**
 * File required validator.
 * An empty value is not null, is a non-empty string or is a non-empty countable.
 * If hasn't content and not required, it breaks validator chain. * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FileRequired extends FileValidator {

    const FILE_REQUIRED = 0;
    const FILE_NOT_REQUIRED = 1;

    /** @var int validation type */
    public $type = self::FILE_REQUIRED;

    /** @var boolean break chain flag, allow to break validation chain if value is empty et no content is required */
    public $breakChain = true;

    protected function validate($value, $context) {
        $hasContent = parent::validate($value, $context) && $value !== null && $value['error'] !== UPLOAD_ERR_NO_FILE;
        if ($this->breakChain && !$hasContent && $this->type == self::FILE_NOT_REQUIRED) {
            //Value has no content and is not required, so we don't have to continue the validation chain
            throw new \cotcot\component\exception\BreakChainException(true);
        }
        return $hasContent || ($this->type == self::FILE_NOT_REQUIRED);
    }

}
