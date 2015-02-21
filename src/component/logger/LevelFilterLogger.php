<?php

namespace cotcot\component\logger;

/**
 * Level filter logger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class LevelFilterLogger extends Logger {

    const OPERATOR_NOT_EQUAL = 0;
    const OPERATOR_EQUAL = 1;
    const OPERATOR_GREATER = 2;
    const OPERATOR_GREATER_OR_EQUAL = 3;
    const OPERATOR_LOWER = 4;
    const OPERATOR_LOWER_OR_EQUAL = 5;

    /** @var Logger loggers */
    public $logger = null;

    /** @var int level */
    public $level = null;

    /** @var int operator */
    public $operator = self::OPERATOR_EQUAL;

    public function log($message, $level = self::LEVEL_INFO, $category = null) {
        $toLog = true;
        switch ($this->operator) {
            case self::OPERATOR_NOT_EQUAL:
                $toLog = $level != $this->level;
                break;
            case self::OPERATOR_EQUAL:
                $toLog = $level == $this->level;
                break;
            case self::OPERATOR_GREATER:
                $toLog = $level > $this->level;
                break;
            case self::OPERATOR_GREATER_OR_EQUAL:
                $toLog = $level >= $this->level;
                break;
            case self::OPERATOR_LOWER:
                $toLog = $level < $this->level;
                break;
            case self::OPERATOR_LOWER_OR_EQUAL:
                $toLog = $level <= $this->level;
                break;
        }
        if ($toLog) {
            $this->logger->log($message, $level, $category);
        }
    }

}

