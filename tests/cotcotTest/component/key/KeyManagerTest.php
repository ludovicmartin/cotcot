<?php

namespace cotcotTest\component\key;

class KeyManagerTest extends \PHPUnit_Framework_TestCase {

    public function test_buildLimitedLifeTimeKey() {
        $o = new \cotcot\component\key\KeyManager();

        $k1 = $o->buildLimitedLifeTimeKey('a', '2013-01-01 00:00:00');
        $k2 = $o->buildLimitedLifeTimeKey('b', '2013-01-01 00:00:00');
        $k3 = $o->buildLimitedLifeTimeKey('a', '2013-01-01 00:00:01');
        $k4 = $o->buildLimitedLifeTimeKey('b', '2013-01-01 00:00:01');
        $k5 = $o->buildLimitedLifeTimeKey('a');
        $k6 = $o->buildLimitedLifeTimeKey('b');

        $this->assertEquals('e3eeaacfd12a499397f5d3093f40564da8691b58eedeb504918146251bf5bd05', $k2);

        $this->assertNotEquals($k1, $k2);
        $this->assertNotEquals($k1, $k3);
        $this->assertNotEquals($k1, $k4);
        $this->assertNotEquals($k1, $k5);
        $this->assertNotEquals($k1, $k6);

        $this->assertNotEquals($k2, $k3);
        $this->assertNotEquals($k2, $k4);
        $this->assertNotEquals($k2, $k5);
        $this->assertNotEquals($k2, $k6);

        $this->assertNotEquals($k3, $k4);
        $this->assertNotEquals($k3, $k5);
        $this->assertNotEquals($k3, $k6);

        $this->assertNotEquals($k4, $k5);
        $this->assertNotEquals($k4, $k6);

        $this->assertNotEquals($k5, $k6);
    }

    public function test_limitedLifeTimeKeyCheck() {
        $o = new \cotcot\component\key\KeyManager();
        $k = $o->buildLimitedLifeTimeKey('a', '2013-01-01 00:00:00');

        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 0, '2013-01-01 00:00:00'));
        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 1, '2013-01-01 00:00:00'));
        $this->assertFalse($o->limitedLifeTimeKeyCheck($k, 'b', 1, '2013-01-01 00:00:00'));
        $this->assertFalse($o->limitedLifeTimeKeyCheck($k, 'a', 1));
        $this->assertFalse($o->limitedLifeTimeKeyCheck($k, 'b', 1));
        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 10, '2013-01-01 00:00:00'));
        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 10, '2013-01-01 00:00:01'));
        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 10, '2013-01-01 00:00:05'));
        $this->assertTrue($o->limitedLifeTimeKeyCheck($k, 'a', 10, '2013-01-01 00:00:10'));
        $this->assertFalse($o->limitedLifeTimeKeyCheck($k, 'a', 10, '2013-01-01 00:00:11'));
    }

}
