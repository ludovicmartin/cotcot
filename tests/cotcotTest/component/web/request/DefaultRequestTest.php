<?php

namespace cotcotTest\component\web\request;

class DefaultRequestTest extends \PHPUnit_Framework_TestCase {

    public function test_getGet() {
        $o = new \cotcot\component\web\request\DefaultRequest();

        unset($_GET['x']);
        $_GET = array();
        $this->assertNull($o->getGet('x'));
        $this->assertEquals('y', $o->getGet('x', 'y'));
        $_GET = array('x' => 'y');
        $this->assertEquals('y', $o->getGet('x'));
    }

    public function test_getPost() {
        $o = new \cotcot\component\web\request\DefaultRequest();

        unset($_POST['x']);
        $_POST = array();
        $this->assertNull($o->getGet('x'));
        $this->assertEquals('y', $o->getPost('x', 'y'));
        $_POST = array('x' => 'y');
        $this->assertEquals('y', $o->getPost('x'));
    }

    public function test_getFile() {
        $o = new \cotcot\component\web\request\DefaultRequest();

        unset($_FILES['x']);
        $this->assertNull($o->getFile('x'));
        $this->assertEquals('y', $o->getFile('x', 'y'));
        $_FILES = array('x' => 'y');
        $this->assertEquals('y', $o->getFile('x'));
    }

    public function test_getServer() {
        $o = new \cotcot\component\web\request\DefaultRequest();

        unset($_SERVER['x']);
        $this->assertNull($o->getServer('x'));
        $this->assertEquals('y', $o->getServer('x', 'y'));
        $_SERVER = array('x' => 'y');
        $this->assertEquals('y', $o->getServer('x'));
    }

    public function test_getCookie() {
        $o = new \cotcot\component\web\request\DefaultRequest();

        unset($_COOKIE['x']);
        $this->assertNull($o->getCookie('x'));
        $this->assertEquals('y', $o->getCookie('x', 'y'));
        $_COOKIE = array('x' => 'y');
        $this->assertEquals('y', $o->getCookie('x'));
    }

}