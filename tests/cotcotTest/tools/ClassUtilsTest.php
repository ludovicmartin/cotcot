<?php

namespace cotcotTest\tools;

class ClassUtilsTest extends \PHPUnit_Framework_TestCase {

    public function test_setProperties() {
        $o = new \cotcotTest\core\dummy\Dummy();
        \cotcot\tools\ClassUtils::setProperties($o, array('a' => 'b', 'b' => 'c'));
        $this->assertEquals('b', $o->a);
        $this->assertEquals('c', $o->b);
    }

    public function test_setProperty() {
        $o = new \cotcotTest\core\dummy\Dummy();
        \cotcot\tools\ClassUtils::setProperty($o, 'a', 'b');
        $this->assertEquals('b', $o->a);

        try {
            $o = 'Not an object';
            \cotcot\tools\ClassUtils::setProperty($o, 'a', 'b');
            $this->fail('object control error');
        } catch (\Exception $ex) {
            $this->assertEquals('$object parametter must be an object', $ex->getMessage());
        }

        $o = new \cotcotTest\core\dummy\Dummy();
        \cotcot\tools\ClassUtils::setProperty($o, 'c', 'ccc');
        $this->assertEquals('ccc', $o->getC());
    }

    public function test_getProperties() {
        $o = new \cotcotTest\core\dummy\Dummy();
        $o->a = 'b';
        $o->b = 'c';
        $o->setC('d');

        $res = \cotcot\tools\ClassUtils::getProperties($o);
        $this->assertCount(3, $res);
        $this->assertEquals('b', $res['a']);
        $this->assertEquals('c', $res['b']);
        $this->assertEquals('d', $res['c']);

        $res = \cotcot\tools\ClassUtils::getProperties($o, array('b'));
        $this->assertCount(1, $res);
        $this->assertEquals('c', $res['b']);

        $res = \cotcot\tools\ClassUtils::getProperties($o, array('c'));
        $this->assertCount(1, $res);
        $this->assertEquals('d', $res['c']);

        try {
            $res = \cotcot\tools\ClassUtils::getProperties($o, array('zaza'));
            $this->fail('property exist control error');
        } catch (\Exception $ex) {
            $this->assertEquals('"zaza" property does not exist', $ex->getMessage());
        }

        try {
            $o = 'Not an object';
            \cotcot\tools\ClassUtils::getProperties($o);
            $this->fail('object control error');
        } catch (\Exception $ex) {
            $this->assertEquals('$object parametter must be an object', $ex->getMessage());
        }
    }

}

