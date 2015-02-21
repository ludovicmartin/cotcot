<?php

namespace cotcot\component\validator;

/**
 * File validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class FileValidator extends Validator {

    protected function validate($value, $context) {
        return is_array($value) &&
                array_key_exists('name', $value) &&
                array_key_exists('type', $value) &&
                array_key_exists('size', $value) &&
                array_key_exists('tmp_name', $value) &&
                array_key_exists('error', $value);
    }

}
