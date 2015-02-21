<?php

namespace cotcotTest\component\web\router;

class DefaultRouterTest extends \PHPUnit_Framework_TestCase {

    public function test_getControllerName_getActionName_getParameters() {
        $r = new dummy\DummyRequest();

        $r->server = array('REQUEST_URI' => '');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array();
        $this->assertNull($o->getControllerName());
        $this->assertNull($o->getActionName());
        $this->assertNull($o->getParameters());

        $r->server = array('REQUEST_URI' => '/');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/' => array('c', 'a')
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(0, $params);

        $r->server = array('REQUEST_URI' => '/bbb');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa' => array('c', 'a')
        );
        $this->assertNull($o->getControllerName());
        $this->assertNull($o->getActionName());
        $this->assertNull($o->getParameters());

        $r->server = array('REQUEST_URI' => '/aaa');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa' => array('c', 'a')
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(0, $params);

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc' => array('c', 'a')
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('bbb', $params['b']);

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc' => array('c', 'a', array('d' => 'eee'))
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(2, $params);
        $this->assertEquals('bbb', $params['b']);
        $this->assertEquals('eee', $params['d']);

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc/ddd');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc' => array('c', 'a')
        );
        $this->assertNull($o->getControllerName());
        $this->assertNull($o->getActionName());
        $this->assertNull($o->getParameters());

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc/ddd' => array('c', 'a')
        );
        $this->assertNull($o->getControllerName());
        $this->assertNull($o->getActionName());
        $this->assertNull($o->getParameters());

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc?x=y');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc' => array('c', 'a')
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('bbb', $params['b']);

        try {
            $r->server = array('REQUEST_URI' => '');
            $o = new \cotcot\component\web\router\DefaultRouter();
            $o->request = $r;
            $o->rules = array(
                '/aaa/:b/ccc' => array('c')
            );
            $o->getControllerName();
            $this->fail('weak rule detection error');
        } catch (\Exception $ex) {
            $this->assertEquals('route configuration error: /aaa/:b/ccc', $ex->getMessage());
        }

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc?x=y');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $o->rules = array(
            '/aaa/:b/ccc' => array('c', 'a', array('x' => 'y'))
        );
        $this->assertEquals('c', $o->getControllerName());
        $this->assertEquals('a', $o->getActionName());
        $params = $o->getParameters();
        $this->assertCount(2, $params);
        $this->assertEquals('bbb', $params['b']);
        $this->assertEquals('y', $params['x']);
    }

    public function test_getRoute() {
        $r = new dummy\DummyRequest();

        $r->server = array('REQUEST_URI' => '/aaa/bbb/ccc?x=y');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $this->assertEquals('/aaa/bbb/ccc', $o->getRoute());

        $r->server = array('REQUEST_URI' => '/');
        $o = new \cotcot\component\web\router\DefaultRouter();
        $o->request = $r;
        $this->assertEquals('/', $o->getRoute());
    }

}
