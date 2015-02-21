<?php

namespace cotcotTest\component\filter;

class FilterTest extends \PHPUnit_Framework_TestCase {

    public function test_callback_filter() {
        $o = new \cotcot\component\filter\Callback();
        $o->callbackFunction = function ($value) {
            return $value . 'a';
        };
        $this->assertEquals('aa', $o->filter('a'));
    }

    public function test_iteratorWalk_filter() {
        $o = new \cotcot\component\filter\IteratorWalk();
        $o->filter = new \cotcot\component\filter\StringTrim();
        $result = $o->filter(array('a', ' b', 'c' => ' d '));
        $this->assertCount(3, $result);
        $this->assertEquals('a', $result[0]);
        $this->assertEquals('b', $result[1]);
        $this->assertEquals('d', $result['c']);
    }

    public function test_stringCase_filter() {
        $o = new \cotcot\component\filter\StringCase();
        $o->type = \cotcot\component\filter\StringCase::TYPE_TO_UPPER;
        $this->assertEquals('A', $o->filter('a'));

        $o->type = \cotcot\component\filter\StringCase::TYPE_TO_LOWER;
        $this->assertEquals('a', $o->filter('A'));

        $o->multiByteString = true;
        $this->assertEquals('é', $o->filter('É'));

        $o->multiByteString = false;
        $this->assertNotEquals('é', $o->filter('É'));

        $o->type = 12345;
        $this->assertNull($o->filter('a'));
    }

    public function test_stringTrim_filter() {
        $o = new \cotcot\component\filter\StringTrim();
        $o->charList = 'b ';
        $this->assertEquals('a', $o->filter('b a b'));

        $o->type = \cotcot\component\filter\StringTrim::TYPE_BOTH;
        $this->assertEquals('a', $o->filter(' a '));

        $o->type = \cotcot\component\filter\StringTrim::TYPE_LEFT;
        $this->assertEquals('a ', $o->filter(' a '));

        $o->type = \cotcot\component\filter\StringTrim::TYPE_RIGHT;
        $this->assertEquals(' a', $o->filter(' a '));

        $o->type = 12345;
        $this->assertNull($o->filter('a'));
    }

    public function test_emptyToNull_filter() {
        $o = new \cotcot\component\filter\EmptyToNull();

        $this->assertEquals('a', $o->filter('a'));
        $this->assertNotNull($o->filter('0'));
        $this->assertNull($o->filter(''));
        $this->assertNotNull($o->filter(0));
        $this->assertNull($o->filter(array()));
        $this->assertNotNull($o->filter(array('a')));
        $this->assertNotNull($o->filter(true));
        $this->assertNotNull($o->filter(false));
    }

    public function test_explode_filter() {
        $o = new \cotcot\component\filter\Explode();
        $o->pattern = ',';

        $temp = $o->filter('');
        $this->assertCount(0, $temp);

        $temp = $o->filter('a');
        $this->assertCount(1, $temp);
        $this->assertEquals('a', $temp[0]);

        $temp = $o->filter('a,b');
        $this->assertCount(2, $temp);
        $this->assertEquals('a', $temp[0]);
        $this->assertEquals('b', $temp[1]);

        $o->type = \cotcot\component\filter\Explode::TYPE_REGEXP;
        $o->pattern = '/x|y/';
        $temp = $o->filter('axbyc');
        $this->assertCount(3, $temp);
        $this->assertEquals('a', $temp[0]);
        $this->assertEquals('b', $temp[1]);
        $this->assertEquals('c', $temp[2]);
    }

    public function test_toArray_filter() {
        $o = new \cotcot\component\filter\ToArray();

        $temp = $o->filter(null);
        $this->assertTrue(is_array($temp));
        $this->assertCount(0, $temp);

        $temp = $o->filter(array());
        $this->assertTrue(is_array($temp));
        $this->assertCount(0, $temp);

        $temp = $o->filter(array('a'));
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertEquals('a', $temp[0]);

        $temp = $o->filter('');
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertEquals('', $temp[0]);

        $temp = $o->filter('a');
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertEquals('a', $temp[0]);

        $temp = $o->filter(1);
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertEquals(1, $temp[0]);

        $temp = $o->filter(true);
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertTrue($temp[0]);

        $temp = $o->filter(false);
        $this->assertTrue(is_array($temp));
        $this->assertCount(1, $temp);
        $this->assertFalse($temp[0]);
    }

    public function test_arrayPack_filter() {
        $o = new \cotcot\component\filter\ArrayPack();

        $temp = $o->filter(array('a', null, '0', 0, array(), '', false));
        $this->assertTrue(is_array($temp));
        $this->assertCount(6, $temp);
        $this->assertEquals('a', $temp[0]);
        $this->assertFalse(key_exists(1, $temp));
        $this->assertEquals('0', $temp[2]);
        $this->assertEquals(0, $temp[3]);
    }

    public function test_directory() {
        $d = new \cotcot\component\filter\Directory();
        $d->init();

        $this->assertTrue($d->getFilter('callback') instanceof \cotcot\component\filter\Callback);
        $this->assertTrue($d->getFilter('each') instanceof \cotcot\component\filter\IteratorWalk);
        $this->assertTrue($d->getFilter('toLower') instanceof \cotcot\component\filter\StringCase);
        $this->assertTrue($d->getFilter('toUpper') instanceof \cotcot\component\filter\StringCase);
        $this->assertTrue($d->getFilter('trim')instanceof \cotcot\component\filter\StringTrim);
        $this->assertTrue($d->getFilter('lTrim')instanceof \cotcot\component\filter\StringTrim);
        $this->assertTrue($d->getFilter('rTrim') instanceof \cotcot\component\filter\StringTrim);
        $this->assertTrue($d->getFilter('nullIfEmpty') instanceof \cotcot\component\filter\EmptyToNull);
        $this->assertTrue($d->getFilter('explode') instanceof \cotcot\component\filter\Explode);
        $this->assertTrue($d->getFilter('pregExplode') instanceof \cotcot\component\filter\Explode);
        $this->assertTrue($d->getFilter('arrayPack') instanceof \cotcot\component\filter\ArrayPack);
        $this->assertTrue($d->getFilter('toArray') instanceof \cotcot\component\filter\ToArray);
    }

}
