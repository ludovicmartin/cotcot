<?php

namespace cotcotTest\tools;

class VarUtilsTest extends \PHPUnit_Framework_TestCase {

    public function test_camelize() {
        $this->assertEquals('AaBb', \cotcot\tools\VarUtils::camelize('aa_bb'));
    }

    public function test_uncamelize() {
        $this->assertEquals('aa_bb', \cotcot\tools\VarUtils::uncamelize('aaBb'));
        $this->assertEquals('aa_bb', \cotcot\tools\VarUtils::uncamelize('AaBb'));
    }

    public function test_buildVarName() {
        $this->assertEquals('aB', \cotcot\tools\VarUtils::buildVarName('a_b'));
        $this->assertEquals('aB', \cotcot\tools\VarUtils::buildVarName('aB'));
    }

}

