<?php

namespace cotcotTest\component\web\csrf;

class CsrfManagerTest extends \PHPUnit_Framework_TestCase {

    public function test_generateKey() {
        $s = new dummy\DummySession();
        $k = new \cotcot\component\key\KeyManager();

        $o = new \cotcot\component\web\csrf\CsrfManager();
        $o->keyManager = $k;
        $o->session = $s;

        $k1 = $o->generateKey();
        $k2 = $o->generateKey();
        $this->assertRegexp('/^[0-9a-f]+$/', $k1);
        $this->assertNotEquals($k1, $k2);
        $sessionItem = $s->getItem($o->sessionIndex);
        $this->assertTrue(is_array($sessionItem));
        $this->assertRegexp('/^[0-9a-f]+$/', $sessionItem['seed']);
        $this->assertRegexp('/^[0-9a-f]+$/', $sessionItem['value']);
    }

    public function test_validateKey() {
        $s = new dummy\DummySession();
        $k = new \cotcot\component\key\KeyManager();

        $o = new \cotcot\component\web\csrf\CsrfManager();
        $o->keyManager = $k;
        $o->session = $s;
        $o->ttl = 0;

        $k = $o->generateKey();
        $this->assertTrue($o->validateKey($k));
        sleep(1);
        $this->assertFalse($o->validateKey($k));
    }

}
