<?php

namespace cotcotTest\core\dummy;

class InitDummy implements \cotcot\core\Initializable {

    public $initialized = false;

    public function init() {
        $this->initialized = true;
    }

}
