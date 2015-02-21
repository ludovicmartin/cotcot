<?php

namespace cotcotTest\component\locale;

class LocaleTest extends \PHPUnit_Framework_TestCase {

    public function test_parse() {
        $o = \cotcot\component\locale\Locale::parse('');
        $this->assertNull($o);

        $o = \cotcot\component\locale\Locale::parse('ibiobz65z6');
        $this->assertNull($o);

        $o = \cotcot\component\locale\Locale::parse('fr-FR');
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $o);
        $this->assertEquals('fr', $o->language);
        $this->assertEquals('FR', $o->region);
        $this->assertEquals('fr-FR', $o->name);

        $o = \cotcot\component\locale\Locale::parse('fr-fr');
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $o);
        $this->assertEquals('fr', $o->language);
        $this->assertEquals('FR', $o->region);
        $this->assertEquals('fr-FR', $o->name);

        $o = \cotcot\component\locale\Locale::parse('fr');
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $o);
        $this->assertEquals('fr', $o->language);
        $this->assertNull($o->region);
        $this->assertEquals('fr', $o->name);
    }

    public function test_toString() {
        $o = new \cotcot\component\locale\Locale();
        $o->name = 'fr-FR';
        $this->assertEquals('fr-FR', (string) $o);

        $o = \cotcot\component\locale\Locale::parse('fr-FR');
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $o);
        $this->assertEquals('fr-FR', (string) $o);

        $o = \cotcot\component\locale\Locale::parse('fr');
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $o);
        $this->assertEquals('fr', (string) $o);
    }

}