<?php

namespace cotcotTest\component\console\commandLine;

class DefaultCommandLineTest extends \PHPUnit_Framework_TestCase {

    public function test_getArguments() {
        $o = new \cotcot\component\console\commandLine\DefaultCommandLine();
        $this->assertTrue(is_array($o->getArgument()));
        $this->assertTrue(is_string($o->getCommandName()));
    }

}
