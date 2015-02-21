<?php

namespace cotcot\component\logger;

/**
 * Logger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Logger {

    const LEVEL_DEBUG = 0;
    const LEVEL_INFO = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;

    /**
     * Log a message.
     * @param string $message message
     * @param int $level level
     * @param string $category category
     * @return void
     */
    public abstract function log($message, $level = self::LEVEL_INFO, $category = null);

    /**
     * Return displayable level name.
     * @param int $level level
     * @return string|null level name or null if unknown
     */
    public function levelToString($level) {
        switch ($level) {
            case self::LEVEL_DEBUG:
                return 'DEBUG';
            case self::LEVEL_INFO:
                return 'INFO';
            case self::LEVEL_WARNING:
                return 'WARNING';
            case self::LEVEL_ERROR:
                return 'ERROR';
        }
        return null;
    }

    /**
     * Format a message.
     * @param string $message message
     * @param int $level level
     * @param string $category category
     * @return string formated message
     */
    public function formatMessage($message, $level = self::LEVEL_INFO, $category = null) {
        $output = array();
        $output[] = '[' . date('Y-m-d H:i:s') . '][' . $this->levelToString($level) . ']';
        if ($category !== null) {
            $output[] = '[' . $category . ']';
        }
        $output[] = ' ';
        $output[] = print_r($message, true);
        $output[] = PHP_EOL;
        return implode('', $output);
    }

}
