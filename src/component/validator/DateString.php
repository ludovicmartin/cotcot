<?php

namespace cotcot\component\validator;

/**
 * Date validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DateString extends Validator {

    /** @var string date format */
    public $format = 'd/m/Y';

    protected function validate($value, $context) {
        if (is_scalar($value)) {
            $date = \DateTime::createFromFormat($this->format, $value);
            $errors = \DateTime::getLastErrors();
            return $date !== false && $errors['warning_count'] == 0;
        }
        return false;
    }

}
