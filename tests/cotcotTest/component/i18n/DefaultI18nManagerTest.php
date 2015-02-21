<?php

namespace cotcotTest\component\i18n;

class DefaultI18nManagerTest extends \PHPUnit_Framework_TestCase {

    public function test_translate() {
        $fld = new \cotcot\component\locale\FixedLocaleDetector();
        $fld->localeName = 'fr-FR';

        $o = new \cotcot\component\i18n\DefaultI18nManager();
        $o->basePath = __DIR__;
        $o->localeDetector = $fld;

        $this->assertEquals('', $o->translate('unknown', 'a'));
        $this->assertEquals('Salut {x}', $o->translate('test', 'a'));
        $this->assertEquals('', $o->translate('test', 'b'));
        $this->assertEquals('Salut X', $o->translate('test', 'a', array('{x}' => 'X')));
        $this->assertEquals('Salut X', $o->translate('test', 'a', array('{x}' => 'X')), 'fr-FR');

        $fld->localeName = 'en-EN';
        $this->assertEquals('', $o->translate('unknown', 'a'));
        $this->assertEquals('Hello {x}', $o->translate('test', 'a'));
        $this->assertEquals('', $o->translate('test', 'b'));
        $this->assertEquals('Hello X', $o->translate('test', 'a', array('{x}' => 'X')));
        $this->assertEquals('Hello X', $o->translate('test', 'a', array('{x}' => 'X')), 'en-EN');

        try {
            $o->translate(DIRECTORY_SEPARATOR . 'test', 'a');
            $this->fail('security check error');
        } catch (\Exception $ex) {
            $this->assertEquals('unvalid category string', $ex->getMessage());
        }
        try {
            $o->translate('test', 'a', null, DIRECTORY_SEPARATOR . 'fr-FR');
            $this->fail('security check error');
        } catch (\Exception $ex) {
            $this->assertEquals('unvalid locale name string', $ex->getMessage());
        }
    }

}
