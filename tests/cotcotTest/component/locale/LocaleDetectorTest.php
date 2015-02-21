<?php

namespace cotcotTest\component\locale;

class LocaleDetectorTest extends \PHPUnit_Framework_TestCase {

    public function test_fixed_localDetector() {
        $o = new \cotcot\component\locale\FixedLocaleDetector();
        $o->localeName = null;
        $this->assertNull($o->getLocale());
        $o->localeName = 'fr-FR';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);
    }

    public function test_default_localDetector() {
        $o = new \cotcot\component\locale\DefaultLocaleDetector();
        $o->allowedLocales = array('fr-FR', 'es-ES');

        $this->assertNull($o->getLocale());

        $d = new \cotcot\component\locale\FixedLocaleDetector();
        $d->localeName = 'fr-FR';
        $o = new \cotcot\component\locale\DefaultLocaleDetector();
        $o->allowedLocales = array('fr-FR', 'es-ES');
        $o->detectors = array($d);
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $d = new \cotcot\component\locale\FixedLocaleDetector();
        $d->localeName = 'en-EN';
        $o = new \cotcot\component\locale\DefaultLocaleDetector();
        $o->allowedLocales = array('fr-FR', 'es-ES');
        $o->detectors = array($d);
        $this->assertNull($o->getLocale());
    }

    public function test_requestHeader_localDetector() {
        $r = new dummy\DummyRequest();
        $o = new \cotcot\component\locale\RequestHeaderLocaleDetector();
        $o->request = $r;

        $r->server['HTTP_ACCEPT_LANGUAGE'] = null;
        $locale = $o->getLocale();
        $this->assertNull($locale);

        $r->server['HTTP_ACCEPT_LANGUAGE'] = 'fr-fr';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $r->server['HTTP_ACCEPT_LANGUAGE'] = 'fr-FR';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $r->server['HTTP_ACCEPT_LANGUAGE'] = 'fr-fr,en-us;q=0.7,en;q=0.3';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);
    }

    public function test_requestParamHeader_localDetector() {
        $r = new dummy\DummyRequest();
        $o = new \cotcot\component\locale\RequestParamLocaleDetector();
        $o->paramName = 'lang';
        $o->paramType = \cotcot\component\locale\RequestParamLocaleDetector::TYPE_GET;
        $o->request = $r;

        $r->get['lang'] = null;
        $locale = $o->getLocale();
        $this->assertNull($locale);

        $r->get['lang'] = 'fr-fr';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $r->get['lang'] = 'fr-FR';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $o->paramType = \cotcot\component\locale\RequestParamLocaleDetector::TYPE_POST;
        $r->post['lang'] = null;
        $locale = $o->getLocale();
        $this->assertNull($locale);

        $r->post['lang'] = 'fr-fr';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);

        $o->paramType = \cotcot\component\locale\RequestParamLocaleDetector::TYPE_COOKIE;
        $r->cookie['lang'] = null;
        $locale = $o->getLocale();
        $this->assertNull($locale);

        $r->cookie['lang'] = 'fr-fr';
        $locale = $o->getLocale();
        $this->assertInstanceOf('\cotcot\component\locale\Locale', $locale);
        $this->assertEquals('fr-FR', (string) $locale);
    }

}