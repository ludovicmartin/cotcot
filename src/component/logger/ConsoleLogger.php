<?php

namespace cotcot\component\logger;

/**
 * Console logger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ConsoleLogger extends Logger {

    /** @var string console charset */
    public $charset = 'UTF-8';

    public function log($message, $level = self::LEVEL_INFO, $category = null) {
        $stream = $level == self::LEVEL_ERROR ? STDERR : STDOUT;
        fwrite($stream, mb_convert_encoding($this->formatMessage($message, $level, $category), $this->charset));
        fflush($stream);
    }

}

