<?php

namespace cotcotTest\component\dataInput;

class DataInputTest extends \PHPUnit_Framework_TestCase {

    public function test_setData_getData() {
        $o = new \cotcot\component\dataInput\DataInput();
        $o->setData(array('a' => 'b', 'c' => 'd'));
        $data = $o->getData();
        $this->assertArrayNotHasKey('a', $data);
        $this->assertNull($o->getData('a'));
        $this->assertArrayNotHasKey('b', $data);
        $this->assertNull($o->getData('b'));

        $o = new \cotcot\component\dataInput\DataInput();
        $o->validators['a'] = array(new \cotcot\component\validator\Safe());
        $o->setData(array('a' => 'b', 'c' => 'd'));
        $data = $o->getData();
        $this->assertArrayHasKey('a', $data);
        $this->assertEquals('b', $o->getData('a'));
        $this->assertArrayNotHasKey('c', $data);
        $this->assertNull($o->getData('c'));

        $o = new \cotcot\component\dataInput\DataInput();
        $o->validators['a'] = array(new \cotcot\component\validator\Safe());
        $o->validators['b'] = array(new \cotcot\component\validator\Safe());
        $o->setData(array('a' => 'b', 'c' => 'd'));
        $data = $o->getData();
        $this->assertArrayHasKey('a', $data);
        $this->assertEquals('b', $o->getData('a'));
        $this->assertArrayHasKey('b', $data);
        $this->assertNull($o->getData('b'));
        $this->assertArrayNotHasKey('c', $data);
        $this->assertNull($o->getData('c'));
    }

    public function test_isValid() {
        $o = new \cotcot\component\dataInput\DataInput();
        $this->assertTrue($o->isValid());

        $o = new \cotcot\component\dataInput\DataInput();
        $o->setData(array('a' => 'aaa', 'b' => 'bbb', 'c' => 'ccc'));
        $this->assertTrue($o->isValid());

        $o = new \cotcot\component\dataInput\DataInput();
        $o->validators['a'] = array(new \cotcot\component\validator\Safe());
        $o->validators['b'] = array(new \cotcot\component\validator\Safe());
        $o->validators['c'] = array(new \cotcot\component\validator\Safe());
        $o->setData(array('a' => 'aaa', 'b' => 'bbb', 'c' => 'ccc'));
        $this->assertTrue($o->isValid());

        $o = new \cotcot\component\dataInput\DataInput();
        $validator = new \cotcot\component\validator\StringLength();
        $validator->min = 1;
        $o->filters = array();
        $o->validators['a'] = array($validator);
        $o->validators['b'] = array(new \cotcot\component\validator\Safe());
        $o->validators['c'] = array(new \cotcot\component\validator\Safe());
        $o->setData(array('a' => '', 'b' => 'bbb', 'c' => 'ccc'));
        $this->assertFalse($o->isValid());
        $o->setData(array('a' => 'aaa', 'b' => 'bbb', 'c' => 'ccc'));
        $this->assertTrue($o->isValid());
        $o->setData(array('a' => 'aaa', 'b' => '', 'c' => 'ccc'));
        $this->assertTrue($o->isValid());

        $o = new \cotcot\component\dataInput\DataInput();
        $filter = new \cotcot\component\filter\StringTrim();
        $validator = new \cotcot\component\validator\StringLength();
        $validator->min = 1;
        $o->filters = array('a' => array($filter));
        $o->validators['a'] = array($validator);
        $o->validators['b'] = array(new \cotcot\component\validator\Safe());
        $o->validators['c'] = array(new \cotcot\component\validator\Safe());
        $o->setData(array('a' => '', 'b' => 'bbb', 'c' => 'ccc'));
        $data = $o->getData();
        $this->assertEquals('', $data['a']);
        $this->assertFalse($o->isValid());
        $o->setData(array('a' => ' ', 'b' => 'bbb', 'c' => 'ccc'));
        $data = $o->getData();
        $this->assertEquals('', $data['a']);
        $this->assertFalse($o->isValid());
        $o->setData(array('a' => 'a', 'b' => '', 'c' => 'ccc'));
        $data = $o->getData();
        $this->assertEquals('a', $data['a']);
        $this->assertTrue($o->isValid());
    }

    public function test_getMessages() {
          $o = new \cotcot\component\dataInput\DataInput();
          $validator = new \cotcot\component\validator\StringLength();
          $validator->min = 1;
          $validator->message = 'Pas bien !';
          $o->validators['a'] = array($validator);

          $this->assertEmpty($o->getMessages());
          $this->assertEmpty($o->getMessages('a'));
          $this->assertEmpty($o->getMessages('b'));

          $this->assertFalse($o->isValid());
          $this->assertNotEmpty($o->getMessages());
          $this->assertNotEmpty($o->getMessages('a'));
          $this->assertEmpty($o->getMessages('b'));

          $o->setData(array('a' => 'a'));
          $this->assertTrue($o->isValid());
          $this->assertNotEmpty($o->getMessages());
          $this->assertEmpty($o->getMessages('a'));
          $this->assertEmpty($o->getMessages('b'));

          $o->setData(array('a' => ''));
          $this->assertFalse($o->isValid());
          $this->assertNotEmpty($o->getMessages());
          $this->assertNotEmpty($o->getMessages('a'));
          $this->assertEmpty($o->getMessages('b'));
    }

    public function test_hasMessage() {
          $o = new \cotcot\component\dataInput\DataInput();
          $validator = new \cotcot\component\validator\StringLength();
          $validator->min = 1;
          $validator->message = 'Pas bien !';
          $o->validators['a'] = array($validator);

          $this->assertFalse($o->hasMessage());
          $this->assertFalse($o->hasMessage('a'));
          $this->assertFalse($o->hasMessage('b'));

          $this->assertFalse($o->isValid());
          $this->assertTrue($o->hasMessage());
          $this->assertTrue($o->hasMessage('a'));
          $this->assertFalse($o->hasMessage('b'));

          $o->setData(array('a' => 'a'));
          $this->assertTrue($o->isValid());
          $this->assertFalse($o->hasMessage());
          $this->assertFalse($o->hasMessage('a'));
          $this->assertFalse($o->hasMessage('b'));

          $o->setData(array('a' => ''));
          $this->assertFalse($o->isValid());
          $this->assertTrue($o->hasMessage());
          $this->assertTrue($o->hasMessage('a'));
          $this->assertFalse($o->hasMessage('b'));
    }

}
