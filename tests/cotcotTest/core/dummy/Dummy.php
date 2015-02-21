<?php

namespace cotcotTest\core\dummy;

class Dummy {

    public $a;
    public $b;
    private $c;

    public function setC($c) {
        $this->c = $c;
    }

    public function getC() {
        return $this->c;
    }

}
