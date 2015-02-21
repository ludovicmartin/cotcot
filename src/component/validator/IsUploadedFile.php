<?php

namespace cotcot\component\validator;

/**
 * Is uploaded file validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class IsUploadedFile extends FileValidator {

    protected function validate($value, $context) {
        return parent::validate($value, $context) &&
                is_uploaded_file($value['tmp_name']) &&
                $value['error'] == UPLOAD_ERR_OK;
    }

}
