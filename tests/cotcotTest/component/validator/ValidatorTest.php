<?php

namespace cotcotTest\component\logger;

class ValidatorTest extends \PHPUnit_Framework_TestCase {

    public function test_validate() {
        $o = new \cotcotTest\component\validator\dummy\DummyValidator();
        $o->message = 'aaa';
        $o->value = 'a';
        $this->assertTrue($o->isValid('a'));
        $this->assertFalse($o->hasMessage());
        $this->assertCount(0, $o->getMessages());

        $this->assertFalse($o->isValid('b'));
        $this->assertTrue($o->hasMessage());
        $this->assertCount(1, $o->getMessages());

        $o->message = array('aaa', 'bbb');
        $this->assertFalse($o->isValid('b'));
        $this->assertTrue($o->hasMessage());
        $this->assertCount(2, $o->getMessages());

        $o->message = array('aaa', 'aaa');
        $this->assertFalse($o->isValid('b'));
        $this->assertTrue($o->hasMessage());
        $this->assertCount(1, $o->getMessages());
    }

    public function test_andValidator_validate() {
        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'b';
        $o = new \cotcot\component\validator\AndValidator();
        $o->lazy = false;
        $o->validators = array($v1, $v2);
        $this->assertFalse($o->isValid('c'));
        $this->assertTrue($v1->used);
        $this->assertTrue($v2->used);

        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'a';
        $o = new \cotcot\component\validator\AndValidator();
        $o->lazy = false;
        $o->validators = array($v1, $v2);
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($v1->used);
        $this->assertTrue($v2->used);

        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'b';
        $o = new \cotcot\component\validator\AndValidator();
        $o->lazy = true;
        $o->validators = array($v1, $v2);
        $this->assertFalse($o->isValid('c'));
        $this->assertTrue($v1->used);
        $this->assertFalse($v2->used);

        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'a';
        $o = new \cotcot\component\validator\AndValidator();
        $o->lazy = true;
        $o->validators = array($v1, $v2);
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($v1->used);
        $this->assertTrue($v2->used);
    }

    public function test_arrayCountValidator_validate() {
        $o = new \cotcot\component\validator\ArrayCount();

        $o->min = null;
        $o->max = null;
        $o->inclusive = true;
        $this->assertTrue($o->isValid(array(0, 1)));

        $o->min = 1;
        $o->max = 3;
        $o->inclusive = true;
        $this->assertTrue($o->isValid(array(0, 1)));

        $o->min = 1;
        $o->max = null;
        $o->inclusive = true;
        $this->assertTrue($o->isValid(array(0, 1)));

        $o->min = 2;
        $o->max = 3;
        $o->inclusive = true;
        $this->assertTrue($o->isValid(array(0, 1)));
        $this->assertTrue($o->isValid(array(0, 1, 2)));

        $o->min = null;
        $o->max = 3;
        $o->inclusive = true;
        $this->assertTrue($o->isValid(array(0, 1)));
        $this->assertTrue($o->isValid(array(0, 1, 2)));

        $o->min = 2;
        $o->max = 3;
        $o->inclusive = false;
        $this->assertFalse($o->isValid(array(0, 1)));
        $this->assertFalse($o->isValid(array(0, 1, 2)));

        $o->min = 2;
        $o->max = 4;
        $o->inclusive = false;
        $this->assertFalse($o->isValid(array(0, 1)));
        $this->assertTrue($o->isValid(array(0, 1, 3)));
    }

    public function test_callbackValidator_validate() {
        $o = new \cotcot\component\validator\Callback();
        $o->callbackFunction = function($value, $context) {
            return $value == 'a';
        };
        $this->assertFalse($o->isValid('b'));
        $this->assertTrue($o->isValid('a'));

        $o = new \cotcot\component\validator\Callback();
        $o->callbackFunction = function($value, $context) {
            return $value == 'a' && isset($context['values']['b']) && $value == $context['values']['b'];
        };
        $this->assertFalse($o->isValid('b'));
        $this->assertFalse($o->isValid('a'));
        $this->assertTrue($o->isValid('a', array('fieldName' => '', 'values' => array('b' => 'a'))));
    }

    public function test_csrfValidator_validate() {
        $o = new \cotcot\component\validator\Csrf();
        $o->csrfManager = new \cotcotTest\component\validator\dummy\DummyCsrfManager();

        $this->assertFalse($o->isValid('bbb'));
        $this->assertTrue($o->isValid('aaa'));
    }

    public function test_dateStringValidator_validate() {
        $o = new \cotcot\component\validator\DateString();
        $o->format = 'd/m/Y';

        $this->assertFalse($o->isValid('01-01-2013'));
        $this->assertTrue($o->isValid('01/01/2013'));
    }

    public function test_equalValidator_validate() {
        $o = new \cotcot\component\validator\Equal();

        $o->strict = false;
        $o->value = 'Abc';
        $this->assertFalse($o->isValid('abc'));
        $this->assertTrue($o->isValid('Abc'));

        $o->strict = false;
        $o->value = 0;
        $this->assertTrue($o->isValid(null));
        $this->assertTrue($o->isValid(0));
        $this->assertTrue($o->isValid('blabla'));

        $o->strict = true;
        $o->value = 0;
        $this->assertFalse($o->isValid(null));
        $this->assertTrue($o->isValid(0));
        $this->assertFalse($o->isValid('blabla'));
    }

    public function test_equalToValidator_validate() {
        $o = new \cotcot\component\validator\EqualTo();

        $o->strict = false;
        $o->fieldName = 'a';
        $context = array('fieldName' => '', 'values' => array('a' => 'Abc'));
        $this->assertFalse($o->isValid('abc', $context));
        $this->assertTrue($o->isValid('Abc', $context));

        $o->strict = false;
        $o->fieldName = 'a';
        $context = array('fieldName' => '', 'values' => array('a' => 0));
        $this->assertTrue($o->isValid(null, $context));
        $this->assertTrue($o->isValid(0, $context));
        $this->assertTrue($o->isValid('blabla', $context));

        $o->strict = true;
        $o->fieldName = 'a';
        $context = array('fieldName' => '', 'values' => array('a' => 0));
        $this->assertFalse($o->isValid(null, $context));
        $this->assertTrue($o->isValid(0, $context));
        $this->assertFalse($o->isValid('blabla', $context));
    }

    public function test_inArrayValidator_validate() {
        $o = new \cotcot\component\validator\InArray();

        $o->strict = false;
        $o->haystack = array('a', 'b');
        $this->assertFalse($o->isValid('c'));
        $this->assertTrue($o->isValid('b'));

        $o->strict = false;
        $o->haystack = array(0);
        $this->assertTrue($o->isValid(null));
        $this->assertTrue($o->isValid(0));
        $this->assertTrue($o->isValid('blabla'));

        $o->strict = true;
        $o->haystack = array(0);
        $this->assertFalse($o->isValid(null));
        $this->assertTrue($o->isValid(0));
        $this->assertFalse($o->isValid('blabla'));
    }

    public function test_isArrayValidator_validate() {
        $o = new \cotcot\component\validator\IsArray();

        $this->assertFalse($o->isValid(1234));
        $this->assertFalse($o->isValid('aaa'));
        $this->assertTrue($o->isValid(array()));
    }

    public function test_isEmailValidator_validate() {
        $o = new \cotcot\component\validator\IsEmail();

        $this->assertTrue($o->isValid('aaaa@aaa.com'));
        $this->assertFalse($o->isValid('qjhghjg'));
    }

    public function test_requiredValidator_validate() {
        $o = new \cotcot\component\validator\Required();

        $this->assertFalse($o->isValid(''));
        $this->assertFalse($o->isValid(array()));
        $this->assertFalse($o->isValid(null));
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($o->isValid(array('a')));
        $this->assertTrue($o->isValid(0));

        $o->type = \cotcot\component\validator\Required::CONTENT_NOT_REQUIRED;
        $o->breakChain = false;
        try {
            $o->isValid('');
        } catch (\cotcot\component\exception\BreakChainException $ex) {
            $this->fail('break chain must not be done');
        }
        $o->breakChain = true;
        try {
            $o->isValid('');
            $this->fail('break chain must be done');
        } catch (\cotcot\component\exception\BreakChainException $ex) {
            $this->assertTrue($ex->status);
        }
    }

    public function test_isFloatValidator_validate() {
        $o = new \cotcot\component\validator\IsFloat();

        $this->assertFalse($o->isValid(1));
        $this->assertTrue($o->isValid(1.0));
        $this->assertFalse($o->isValid('1'));
        $this->assertTrue($o->isValid(-1.2));
        $this->assertfalse($o->isValid('-1.2'));
        $this->assertFalse($o->isValid('qjhghjg'));
    }

    public function test_isIntValidator_validate() {
        $o = new \cotcot\component\validator\IsInt();

        $this->assertTrue($o->isValid(1));
        $this->assertFalse($o->isValid(1.0));
        $this->assertFalse($o->isValid('1'));
        $this->assertFalse($o->isValid(-1.2));
        $this->assertfalse($o->isValid('-1.2'));
        $this->assertFalse($o->isValid('qjhghjg'));
    }

    public function test_isScalarValidator_validate() {
        $o = new \cotcot\component\validator\IsScalar();

        $this->assertTrue($o->isValid(1));
        $this->assertTrue($o->isValid(1.0));
        $this->assertTrue($o->isValid('1'));
        $this->assertTrue($o->isValid(-1.2));
        $this->assertTrue($o->isValid('-1.2'));
        $this->assertTrue($o->isValid('qjhghjg'));
        $this->assertFalse($o->isValid($o));
    }

    public function test_isStringValidator_validate() {
        $o = new \cotcot\component\validator\IsString();

        $this->assertFalse($o->isValid(1));
        $this->assertFalse($o->isValid(1.0));
        $this->assertTrue($o->isValid('1'));
        $this->assertFalse($o->isValid(-1.2));
        $this->assertTrue($o->isValid('-1.2'));
        $this->assertTrue($o->isValid('qjhghjg'));
        $this->assertFalse($o->isValid($o));
    }

    public function test_isUrlValidator_validate() {
        $o = new \cotcot\component\validator\IsUrl();

        $this->assertFalse($o->isValid('a aa'));
        $this->assertFalse($o->isValid('www.truc.com'));
        $this->assertTrue($o->isValid('http://www.truc.com'));
    }

    public function test_orValidator_validate() {
        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'b';
        $o = new \cotcot\component\validator\OrValidator();
        $o->lazy = false;
        $o->validators = array($v1, $v2);
        $this->assertFalse($o->isValid('c'));
        $this->assertTrue($v1->used);
        $this->assertTrue($v2->used);

        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'b';
        $o = new \cotcot\component\validator\OrValidator();
        $o->lazy = false;
        $o->validators = array($v1, $v2);
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($v1->used);
        $this->assertTrue($v2->used);

        $v1 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v1->value = 'a';
        $v2 = new \cotcotTest\component\validator\dummy\DummyValidator();
        $v2->value = 'b';
        $o = new \cotcot\component\validator\OrValidator();
        $o->lazy = true;
        $o->validators = array($v1, $v2);
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($v1->used);
        $this->assertFalse($v2->used);
    }

    public function test_stringLengthValidator_validate() {
        $o = new \cotcot\component\validator\StringLength();

        $o->inclusive = true;
        $o->multiByteString = true;
        $o->min = null;
        $o->max = null;
        $this->assertTrue($o->isValid(''));

        $o->inclusive = true;
        $o->multiByteString = true;
        $o->min = 1;
        $o->max = 2;
        $this->assertFalse($o->isValid(''));

        $o->inclusive = true;
        $o->multiByteString = true;
        $o->min = null;
        $o->max = 2;
        $this->assertTrue($o->isValid(''));

        $o->inclusive = true;
        $o->multiByteString = true;
        $o->min = 1;
        $o->max = null;
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($o->isValid('aa'));

        $o->inclusive = false;
        $o->multiByteString = true;
        $o->min = 1;
        $o->max = 3;
        $this->assertFalse($o->isValid('a'));
        $this->assertTrue($o->isValid('aa'));

        $o->inclusive = true;
        $o->multiByteString = false;
        $o->min = 1;
        $o->max = 1;
        $this->assertFalse($o->isValid('é'));
        $o->multiByteString = true;
        $this->assertTrue($o->isValid('é'));
    }

    public function test_rangeValidator_validate() {
        $o = new \cotcot\component\validator\Range();

        $o->inclusive = true;
        $o->min = 1;
        $o->max = 2;
        $this->assertFalse($o->isValid(0));
        $this->assertTrue($o->isValid(1));
        $this->assertTrue($o->isValid(2));
        $this->assertFalse($o->isValid(3));

        $o->inclusive = false;
        $o->min = 1;
        $o->max = 3;
        $this->assertFalse($o->isValid(0));
        $this->assertFalse($o->isValid(1));
        $this->assertTrue($o->isValid(2));
        $this->assertFalse($o->isValid(3));

        $o->inclusive = true;
        $o->min = null;
        $o->max = 3;
        $this->assertTrue($o->isValid(2));
        $this->assertTrue($o->isValid(3));
        $this->assertFalse($o->isValid(4));

        $o->inclusive = true;
        $o->min = 1;
        $o->max = null;
        $this->assertFalse($o->isValid(0));
        $this->assertTrue($o->isValid(1));
        $this->assertTrue($o->isValid(2));
    }

    public function test_stringPregMatchValidator_validate() {
        $o = new \cotcot\component\validator\StringPregMatch();

        $o->pattern = '/a/';
        $this->assertFalse($o->isValid('b'));
        $this->assertTrue($o->isValid('a'));
    }

    public function test_safeValidator_validate() {
        $o = new \cotcot\component\validator\Safe();

        $this->assertTrue($o->isValid(null));
        $this->assertTrue($o->isValid('a'));
        $this->assertTrue($o->isValid(0));
    }

    public function test_isNumeric_validate() {
        $o = new \cotcot\component\validator\IsNumeric();

        $this->assertFalse($o->isValid(null));
        $this->assertTrue($o->isValid(42));
        $this->assertTrue($o->isValid('42'));
        $this->assertTrue($o->isValid(0x539));
        $this->assertTrue($o->isValid('0x539'));
        $this->assertTrue($o->isValid(02471));
        $this->assertTrue($o->isValid('02471'));
        $this->assertTrue($o->isValid(1337e0));
        $this->assertTrue($o->isValid('1337e0'));
        $this->assertFalse($o->isValid('not numeric'));
        $this->assertFalse($o->isValid(array()));
        $this->assertTrue($o->isValid(9.1));
        $this->assertTrue($o->isValid('9.1'));
    }

    public function test_iteratorWalk_validate() {
        $v1 = new \cotcot\component\validator\Required();
        $v1->type = \cotcot\component\validator\Required::CONTENT_REQUIRED;
        $v2 = new \cotcot\component\validator\StringLength();
        $v2->min = 3;

        $v = new \cotcot\component\validator\IteratorWalk();
        $v->validators = array($v1, $v2);
        $this->assertFalse($v->isValid(array('')));
        $this->assertFalse($v->isValid(array('a')));
        $this->assertFalse($v->isValid(array('aa')));
        $this->assertTrue($v->isValid(array('aaa')));
        $this->assertFalse($v->isValid(array('', '')));
        $this->assertFalse($v->isValid(array('a', '')));
        $this->assertFalse($v->isValid(array('aa', '')));
        $this->assertFalse($v->isValid(array('aaa', '')));

        $v1 = new \cotcot\component\validator\Required();
        $v1->type = \cotcot\component\validator\Required::CONTENT_NOT_REQUIRED;
        $v2 = new \cotcot\component\validator\StringLength();
        $v2->min = 3;

        $v = new \cotcot\component\validator\IteratorWalk();
        $v->validators = array($v1, $v2);
        $this->assertTrue($v->isValid(array('')));
        $this->assertFalse($v->isValid(array('a')));
        $this->assertFalse($v->isValid(array('aa')));
        $this->assertTrue($v->isValid(array('aaa')));
        $this->assertTrue($v->isValid(array('', '')));
        $this->assertFalse($v->isValid(array('a', '')));
        $this->assertFalse($v->isValid(array('aa', '')));
        $this->assertTrue($v->isValid(array('aaa', '')));
    }

    public function test_directory() {
        $d = new \cotcot\component\validator\Directory();
        $d->init();
        
        $this->assertTrue($d->getValidator('and') instanceof \cotcot\component\validator\AndValidator);
        $this->assertTrue($d->getValidator('count') instanceof \cotcot\component\validator\ArrayCount);
        $this->assertTrue($d->getValidator('callback') instanceof \cotcot\component\validator\Callback);
        $this->assertTrue($d->getValidator('csrf') instanceof \cotcot\component\validator\Csrf);
        $this->assertTrue($d->getValidator('date') instanceof \cotcot\component\validator\DateString);
        $this->assertTrue($d->getValidator('equal') instanceof \cotcot\component\validator\Equal);
        $this->assertTrue($d->getValidator('notEqual') instanceof \cotcot\component\validator\Equal);
        $this->assertTrue($d->getValidator('equalTo') instanceof \cotcot\component\validator\EqualTo);
        $this->assertTrue($d->getValidator('notEqualTo') instanceof \cotcot\component\validator\EqualTo);
        $this->assertTrue($d->getValidator('in') instanceof \cotcot\component\validator\InArray);
        $this->assertTrue($d->getValidator('array') instanceof \cotcot\component\validator\IsArray);
        $this->assertTrue($d->getValidator('email') instanceof \cotcot\component\validator\IsEmail);
        $this->assertTrue($d->getValidator('float') instanceof \cotcot\component\validator\IsFloat);
        $this->assertTrue($d->getValidator('int') instanceof \cotcot\component\validator\IsInt);
        $this->assertTrue($d->getValidator('null') instanceof \cotcot\component\validator\IsNull);
        $this->assertTrue($d->getValidator('notNull') instanceof \cotcot\component\validator\IsNull);
        $this->assertTrue($d->getValidator('scalar') instanceof \cotcot\component\validator\IsScalar);
        $this->assertTrue($d->getValidator('string') instanceof \cotcot\component\validator\IsString);
        $this->assertTrue($d->getValidator('url') instanceof \cotcot\component\validator\IsUrl);
        $this->assertTrue($d->getValidator('range') instanceof \cotcot\component\validator\Range);
        $this->assertTrue($d->getValidator('or') instanceof \cotcot\component\validator\OrValidator);
        $this->assertTrue($d->getValidator('length') instanceof \cotcot\component\validator\StringLength);
        $this->assertTrue($d->getValidator('match') instanceof \cotcot\component\validator\StringPregMatch);
        $this->assertTrue($d->getValidator('required') instanceof \cotcot\component\validator\Required);
        $this->assertTrue($d->getValidator('notRequired') instanceof \cotcot\component\validator\Required);
        $this->assertTrue($d->getValidator('safe') instanceof \cotcot\component\validator\Safe);
        $this->assertTrue($d->getValidator('isNumeric') instanceof \cotcot\component\validator\IsNumeric);
        $this->assertTrue($d->getValidator('each') instanceof \cotcot\component\validator\IteratorWalk);
        $this->assertTrue($d->getValidator('fileSize') instanceof \cotcot\component\validator\FileSize);
        $this->assertTrue($d->getValidator('fileCallback') instanceof \cotcot\component\validator\FileCallback);
        $this->assertTrue($d->getValidator('fileRequired') instanceof \cotcot\component\validator\FileRequired);
        $this->assertTrue($d->getValidator('fileNotRequired') instanceof \cotcot\component\validator\FileRequired);
        $this->assertTrue($d->getValidator('isUploadedFile') instanceof \cotcot\component\validator\IsUploadedFile);
    }

}
