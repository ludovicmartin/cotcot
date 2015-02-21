<?php

namespace cotcotTest\core;

class RuntimeContextTest extends \PHPUnit_Framework_TestCase {

    public function test_construct() {
        //Empty conf
        $config = array();
        $this->assertInstanceOf('\cotcot\core\RuntimeContext', new \cotcot\core\RuntimeContext($config));

        //Empty initializers
        $config = array(
            'initializers' => array()
        );
        $this->assertInstanceOf('\cotcot\core\RuntimeContext', new \cotcot\core\RuntimeContext($config));

        //One initializer
        $config = array(
            'initializers' => array(
                array(
                    'classname' => '\cotcotTest\core\dummy\DummyInitializer'
                )
            )
        );
        new \cotcot\core\RuntimeContext($config);
        $this->assertTrue(\cotcotTest\core\dummy\DummyInitializer::$done);
    }

    public function test_getComponent() {
        $config = array(
            'single' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'singleton' => true
                )
            ),
            'notSingle' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'singleton' => false
                )
            ),
            'injectProp' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'a' => 'aaa',
                        'b' => 'bbb'
                    )
                ),
                'y' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'zzz' => 'aaa',
                    )
                )
            ),
            'injectCompo' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => 'aaa.bbb'
                    )
                ),
                'z' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => 'injectCompo.a'
                    )
                ),
                'a' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy'
                )
            ),
            'aware' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\AwareDummy'
                )
            ),
            'customAttr' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'a' => 'aaa',
                        'b' => 'bbb'
                    )
                )
            ),
            'samePackage' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => 'y'
                    )
                ),
                'y' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy'
                )
            ),
            'cyclicDep' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'singleton' => true,
                    'components' => array(
                        'a' => 'x'
                    )
                )
            ),
            'cutsomCompo' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => 'y'
                    )
                ),
                'y' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'a' => 'aaa'
                    )
                ),
                'z' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'a' => 'bbb'
                    )
                )
            ),
            'objectCompo' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => 'y'
                    )
                ),
                'y' => new \cotcotTest\core\dummy\Dummy()
            ),
            'injectCompoConfCopy' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => '@y'
                    )
                ),
                'y' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy'
                )
            ),
            'injectCompoList' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'components' => array(
                        'a' => array('y', '@y')
                    )
                ),
                'y' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                )
            ),
            'classnameMissing' => array(
                'x' => array()
            ),
            'withSetter' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy',
                    'attributes' => array(
                        'c' => 'ccc'
                    )
                )
            ),
            'initializable' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\InitDummy',
                )
            ),
        );
        $context = new \cotcot\core\RuntimeContext($config);

        //Unknown component
        $this->assertNull($context->getComponent('aaa.bbb'));

        //Bad component name format
        try {
            $context->getComponent('aaa');
            $this->fail('component name format control error');
        } catch (\Exception $ex) {
            $this->assertEquals('bad component name (must be like "category.name" or "@category.name") "aaa"', $ex->getMessage());
        }

        //Singleton
        $a = $context->getComponent('single.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $b = $context->getComponent('single.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $b);
        $this->assertSame($a, $b);

        //Not singleton
        $a = $context->getComponent('notSingle.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $b = $context->getComponent('notSingle.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $b);
        $this->assertNotSame($a, $b);

        //With unknown properties
        try {
            $context->getComponent('injectProp.y');
            $this->fail('property exist control error');
        } catch (\Exception $ex) {
            $this->assertEquals('"zzz" property does not exist', $ex->getMessage());
        }

        //With properties
        $a = $context->getComponent('injectProp.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('aaa', $a->a);
        $this->assertEquals('bbb', $a->b);

        //Inject unknown component
        try {
            $context->getComponent('injectCompo.x');
            $this->fail('component exist control error');
        } catch (\Exception $ex) {
            $this->assertEquals('unknown component "aaa.bbb"', $ex->getMessage());
        }

        //Inject compoment
        $a = $context->getComponent('injectCompo.z');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->a);

        //With runtime context aware
        $a = $context->getComponent('aware.x');
        $this->assertInstanceOf('\cotcot\core\RuntimeContext', $a->runtimeContext);

        //With custom attributes
        $a = $context->getComponent('customAttr.x', array('b' => 'ccc'));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('aaa', $a->a);
        $this->assertEquals('ccc', $a->b);

        //Same package component name
        $a = $context->getComponent('samePackage.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->a);

        //Cyclic dependency
        try {
            $a = $context->getComponent('cyclicDep.x');
            $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        } catch (\Exception $ex) {
            $this->fail('error on cyclic dependency', $ex->getMessage());
        }

        //With custom component
        $a = $context->getComponent('cutsomCompo.x', null, array('b' => 'cutsomCompo.z'));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('aaa', $a->a->a);
        $this->assertEquals('bbb', $a->b->a);

        //With componant as object
        $a = $context->getComponent('objectCompo.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->a);

        //Inject compoment config copy
        $a = $context->getComponent('injectCompoConfCopy.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertTrue(is_array($a->a));
        $this->assertTrue(isset($a->a['classname']));
        $this->assertEquals('\cotcotTest\core\dummy\Dummy', $a->a['classname']);

        //Inject component list
        $a = $context->getComponent('injectCompoList.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertCount(2, $a->a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->a[0]);
        $this->assertTrue(is_array($a->a[1]));
        $this->assertTrue(isset($a->a[1]['classname']));
        $this->assertEquals('\cotcotTest\core\dummy\Dummy', $a->a[1]['classname']);

        //Classname missing
        try {
            $context->getComponent('classnameMissing.x');
            $this->fail('classname missing control error');
        } catch (\Exception $ex) {
            $this->assertEquals('classname not defined in configuration', $ex->getMessage());
        }

        //With setter
        $a = $context->getComponent('withSetter.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('ccc', $a->getC());

        //Initializable
        $a = $context->getComponent('initializable.x');
        $this->assertInstanceOf('\cotcotTest\core\dummy\InitDummy', $a);
        $this->assertTrue($a->initialized);
        $a = $context->getComponent('initializable.x', null, null, false);
        $this->assertInstanceOf('\cotcotTest\core\dummy\InitDummy', $a);
        $this->assertFalse($a->initialized);
    }

    public function test_createObject() {
        $context = new \cotcot\core\RuntimeContext(array(
            'dummy' => array(
                'x' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy'
                ),
                'b' => array(
                    'classname' => '\cotcotTest\core\dummy\Dummy'
                )
            )
        ));

        //Classname
        $a = $context->createObject('\cotcotTest\core\dummy\Dummy');
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);

        //Bad configuration
        try {
            $context->createObject(array());
            $this->fail('classname missing control error');
        } catch (\Exception $ex) {
            $this->assertEquals('classname not defined in configuration', $ex->getMessage());
        }

        //Configuration
        $a = $context->createObject(array(
            'classname' => '\cotcotTest\core\dummy\Dummy',
            'attributes' => array(
                'a' => 'aaa'
            ),
            'components' => array(
                'b' => 'dummy.x'
            ),
        ));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('aaa', $a->a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->b);

        //With custom attributes
        $a = $context->createObject(array(
            'classname' => '\cotcotTest\core\dummy\Dummy',
            'attributes' => array(
                'b' => 'bbb'
            )
                ), array('a' => 'aaa'));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertEquals('aaa', $a->a);
        $this->assertEquals('bbb', $a->b);

        //With custom components
        $a = $context->createObject(array(
            'classname' => '\cotcotTest\core\dummy\Dummy',
            'components' => array(
                'b' => 'dummy.x'
            )
                ), null, array('a' => 'dummy.x'));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->b);

        //Unknown components in array
        try {
            $context->createObject(array(
                'classname' => '\cotcotTest\core\dummy\Dummy',
                'components' => array(
                    'a' => array('dummy.unknown')
                )
            ));
            $this->fail('component exists control error');
        } catch (\Exception $ex) {
            $this->assertEquals('unknown component "dummy.unknown"', $ex->getMessage());
        }

        //Auto property name for component
        $a = $context->createObject(array(
            'classname' => '\cotcotTest\core\dummy\Dummy',
            'components' => array(
                'dummy.b'
            )
        ));
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a);
        $this->assertInstanceOf('\cotcotTest\core\dummy\Dummy', $a->b);
    }

}
