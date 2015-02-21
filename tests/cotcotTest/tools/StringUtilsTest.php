<?php

namespace cotcotTest\tools;

class StringUtilsTest extends \PHPUnit_Framework_TestCase {

    public function test_toSlug() {
        $this->assertEquals('salut-les-amis-e', \cotcot\tools\StringUtils::toSlug('Salut les amis ! &é\' '));
        $this->assertEquals('abcd', \cotcot\tools\StringUtils::toSlug('abcdef', '-', 4));
    }

    public function test_buildRandomKey() {
        $key = \cotcot\tools\StringUtils::buildRandomKey(20);
        $this->assertEquals(20, strlen($key));
        $this->assertRegExp('/^[0-9a-z]+$/', $key);
    }

    public function test_toDataSizeString() {
        $this->assertEquals('1 oct', \cotcot\tools\StringUtils::toDataSizeString(1));
        $this->assertEquals('1.00 Ko', \cotcot\tools\StringUtils::toDataSizeString(1024));
        $this->assertEquals('1.00 Mo', \cotcot\tools\StringUtils::toDataSizeString(1024 * 1024));
        $this->assertEquals('1.00 Go', \cotcot\tools\StringUtils::toDataSizeString(1024 * 1024 * 1024));
        $this->assertEquals('1.00 To', \cotcot\tools\StringUtils::toDataSizeString(1024 * 1024 * 1024 * 1024));
    }

    public function test_cut() {
        $this->assertEquals('abc', \cotcot\tools\StringUtils::cut('abcdefgh', 3, '...'));
        $this->assertEquals('a...', \cotcot\tools\StringUtils::cut('abcdefgh', 4, '...'));
        $this->assertEquals('é...', \cotcot\tools\StringUtils::cut('éècdefgh', 4, '...'));

        $this->assertEquals('abc', \cotcot\tools\StringUtils::cut('abcdefgh', 3, '...', true));
        $this->assertEquals('a...', \cotcot\tools\StringUtils::cut('abcdefgh', 4, '...', true));
        $this->assertEquals('é...', \cotcot\tools\StringUtils::cut('éècdefgh', 4, '...', true));

        $this->assertEquals('abc', \cotcot\tools\StringUtils::cut('abcde fgh', 3, '...', true));
        $this->assertEquals('a...', \cotcot\tools\StringUtils::cut('a bcdefgh', 4, '...', true));
        $this->assertEquals('é...', \cotcot\tools\StringUtils::cut('éè cdefgh', 4, '...', true));

        $this->assertEquals('éè cde...', \cotcot\tools\StringUtils::cut('éè cde ffdf dsf dfdsf dsf gh', 10, '...', true));
    }

    public function test_removeAccents() {
        $this->assertEquals('eaee', \cotcot\tools\StringUtils::removeAccents('éàèê'));
    }

    public function test_pluralize() {
        $this->assertEquals(\cotcot\tools\StringUtils::pluralize(0, 'x', 'y'), 'x');
        $this->assertEquals(\cotcot\tools\StringUtils::pluralize(1, 'x', 'y'), 'x');
        $this->assertEquals(\cotcot\tools\StringUtils::pluralize(2, 'x', 'y'), 'y');
        $this->assertEquals(\cotcot\tools\StringUtils::pluralize(2, 'x'), 'xs');
    }

}
