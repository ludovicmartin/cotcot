<?php

namespace cotcotTest\component\validator\dummy;

class DummyCsrfManager {

    public function generateKey() {
        return 'aaa';
    }

    public function validateKey($value) {
        return $value == 'aaa';
    }

}
