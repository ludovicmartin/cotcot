<?php

namespace cotcotTest\component\logger;

class LoggerTest extends \PHPUnit_Framework_TestCase {

    public function test_levelToString() {
        $o = new dummy\DummyLogger();
        $this->assertEquals('DEBUG', $o->levelToString(\cotcot\component\logger\Logger::LEVEL_DEBUG));
        $this->assertEquals('INFO', $o->levelToString(\cotcot\component\logger\Logger::LEVEL_INFO));
        $this->assertEquals('WARNING', $o->levelToString(\cotcot\component\logger\Logger::LEVEL_WARNING));
        $this->assertEquals('ERROR', $o->levelToString(\cotcot\component\logger\Logger::LEVEL_ERROR));
        $this->assertNull($o->levelToString(-1));
    }

    public function test_formatMessage() {
        $o = new dummy\DummyLogger();
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', $o->formatMessage('aaa', \cotcot\component\logger\Logger::LEVEL_INFO, null));
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\]\[bbb\] aaa/', $o->formatMessage('aaa', \cotcot\component\logger\Logger::LEVEL_INFO, 'bbb'));
    }

    public function test_chainLogger_log() {
        $l = new dummy\DummyLogger();

        $o = new \cotcot\component\logger\ChainLogger();
        $o->loggers = array($l);
        $o->log('aaa');
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', $l->messages[0]);
    }

    public function test_fileLogger_log() {
        $o = new \cotcot\component\logger\FileLogger();
        $o->filename = tempnam('/tmp', 'cotcotTest');
        $o->charset = 'utf-8';
        $o->log('aaa');
        $content = file_get_contents($o->filename);
        $this->assertEquals(32, strlen($content));
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', rtrim($content));
        unlink($o->filename);

        $o = new \cotcot\component\logger\FileLogger();
        $o->filename = tempnam('/tmp', 'cotcotTest');
        $o->charset = 'utf-8';
        $o->log('ééé', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $content = file_get_contents($o->filename);
        $this->assertEquals(36, strlen($content));
        unlink($o->filename);

        $o = new \cotcot\component\logger\FileLogger();
        $o->filename = tempnam('/tmp', 'cotcotTest');
        $o->charset = 'ISO-8859-1';
        $o->log('ééé', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $content = file_get_contents($o->filename);
        $this->assertEquals(33, strlen($content));
        unlink($o->filename);

        $o = new \cotcot\component\logger\FileLogger();
        $o->filename = '/nevrer_ever/no_one';
        try {
            $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
            $this->fail('file write control error');
        } catch (\Exception $ex) {
            $this->assertEquals('unable to write into log file "/nevrer_ever/no_one"', $ex->getMessage());
        }
    }

    public function test_levelFilterLogger_log() {
        $l = new dummy\DummyLogger();
        $o = new \cotcot\component\logger\LevelFilterLogger();
        $o->logger = $l;
        $o->level = \cotcot\component\logger\Logger::LEVEL_INFO;

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_NOT_EQUAL;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_WARNING);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[WARNING\] aaa/', $l->messages[0]);

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_EQUAL;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', $l->messages[0]);

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_GREATER;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_WARNING);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[WARNING\] aaa/', $l->messages[0]);

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_GREATER_OR_EQUAL;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', $l->messages[0]);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_WARNING);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[WARNING\] aaa/', $l->messages[0]);

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_LOWER_OR_EQUAL;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_WARNING);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[INFO\] aaa/', $l->messages[0]);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[DEBUG\] aaa/', $l->messages[0]);

        $o->operator = \cotcot\component\logger\LevelFilterLogger::OPERATOR_LOWER;
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_WARNING);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_INFO);
        $this->assertCount(0, $l->messages);
        $l->messages = array();
        $o->log('aaa', \cotcot\component\logger\Logger::LEVEL_DEBUG);
        $this->assertCount(1, $l->messages);
        $this->assertRegexp('/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\]\[DEBUG\] aaa/', $l->messages[0]);
    }

}

