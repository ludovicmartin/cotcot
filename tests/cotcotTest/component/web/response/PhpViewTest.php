<?php

namespace cotcotTest\component\web\response;

class PhpViewTest extends \PHPUnit_Framework_TestCase {

    public function test_formatTagAttributes() {
        $o = new \cotcot\component\web\response\PhpView();
        $o->basePath = '/tmp';
        $o->defaultFilenameExtension = 'php';
        $this->assertEquals('/tmp/aaa/bbb.php', $o->buildFullPath('aaa/bbb'));
        $this->assertEquals('/tmp/aaa/bbb.php', $o->buildFullPath('aaa/bbb.php'));
    }

}