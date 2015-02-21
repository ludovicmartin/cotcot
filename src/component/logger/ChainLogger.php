<?php

namespace cotcot\component\logger;

/**
 * Chain logger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ChainLogger extends Logger {

    /** @var Logger loggers */
    public $loggers = null;

    public function log($message, $level = self::LEVEL_INFO, $category = null) {
        foreach ($this->loggers as $logger) {
            $logger->log($message, $level, $category);
        }
    }

}
