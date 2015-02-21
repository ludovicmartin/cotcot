<?php

namespace cotcotTest\tools;

class DirectoryTest extends \PHPUnit_Framework_TestCase {

    public function test_getItem() {
        $d = new \cotcot\tools\Directory();
        $d->items = array(
            'x' => array('classname' => '\Directory')
        );
        $d->init();
        $this->assertTrue($d->getItem('x') instanceof \Directory);
        $this->assertNull($d->getItem('y'));
    }

}
