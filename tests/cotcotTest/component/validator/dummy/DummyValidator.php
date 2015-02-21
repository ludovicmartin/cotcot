<?php

namespace cotcotTest\component\validator\dummy;

class DummyValidator extends \cotcot\component\validator\Validator {

    public $used = false;
    public $value = true;

    protected function validate($value, $context) {
        $this->used = true;
        return $this->value == $value;
    }

}
