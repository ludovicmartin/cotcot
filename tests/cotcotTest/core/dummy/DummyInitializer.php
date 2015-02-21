<?php

namespace cotcotTest\core\dummy;

class DummyInitializer implements \cotcot\core\Initializable {

    public static $done = false;

    public function init() {
        self::$done = true;
    }

}
