<?php

namespace cotcotTest\component\logger\dummy;

class DummyLogger extends \cotcot\component\logger\Logger {

    public $messages = array();

    public function log($message, $level = self::LEVEL_INFO, $category = null) {
        $this->messages[] = $this->formatMessage($message, $level, $category);
    }

}
