<?php

namespace cotcotTest\component\web\flashMessage;

class FlashManagerTest extends \PHPUnit_Framework_TestCase {

    public function test_addMessage() {
        $s = new dummy\DummySession();

        $o = new \cotcot\component\web\flashMessage\DefaultFlashMessenger();
        $o->session = $s;

        $o->addInfo('i');
        $o->addError('e');
        $o->addSuccess('s');
        $o->addWarning('w');
        $o->addMessage('xyz', 'x');
        $o->addMessage('xyz', array('y', 'z'));
        $this->assertTrue(isset($s->items[$o->sessionIndex]));
        $data = $s->items[$o->sessionIndex];

        $this->assertTrue(isset($data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_ERROR][0]));
        $this->assertEquals('e', $data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_ERROR][0]);
        $this->assertTrue(isset($data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO][0]));
        $this->assertEquals('i', $data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO][0]);
        $this->assertTrue(isset($data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_SUCCESS][0]));
        $this->assertEquals('s', $data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_SUCCESS][0]);
        $this->assertTrue(isset($data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_WARNING][0]));
        $this->assertEquals('w', $data[\cotcot\component\web\flashMessage\FlashMessenger::TYPE_WARNING][0]);
        $this->assertTrue(isset($data['xyz'][0]));
        $this->assertEquals('x', $data['xyz'][0]);
        $this->assertTrue(isset($data['xyz'][1]));
        $this->assertEquals('y', $data['xyz'][1]);
        $this->assertTrue(isset($data['xyz'][2]));
        $this->assertEquals('z', $data['xyz'][2]);
    }

    public function test_getMessages() {
        $s = new dummy\DummySession();

        $o = new \cotcot\component\web\flashMessage\DefaultFlashMessenger();
        $o->session = $s;

        $data = $o->getMessages(\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO);
        $this->assertCount(0, $data);

        $o->addInfo('a');
        $o->addInfo('b');
        $data = $o->getMessages(\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO);
        $this->assertCount(2, $data);
        $this->assertEquals('a', $data[0]);
        $this->assertEquals('b', $data[1]);
    }

    public function test_popMessages() {
        $s = new dummy\DummySession();

        $o = new \cotcot\component\web\flashMessage\DefaultFlashMessenger();
        $o->session = $s;

        $data = $o->getMessages(\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO);
        $this->assertCount(0, $data);

        $o->addInfo('a');
        $o->addInfo('b');
        $data = $o->popMessages(\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO);
        $this->assertCount(2, $data);
        $this->assertEquals('a', $data[0]);
        $this->assertEquals('b', $data[1]);
        $data = $o->popMessages(\cotcot\component\web\flashMessage\FlashMessenger::TYPE_INFO);
        $this->assertCount(0, $data);
    }

}
