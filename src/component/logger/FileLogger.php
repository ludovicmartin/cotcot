<?php

namespace cotcot\component\logger;

/**
 * File logger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FileLogger extends Logger {

    /** @var string console charset */
    public $charset = 'UTF-8';

    /** @var string log file name */
    public $filename;

    /** @var ressource file handle */
    private $stream = null;

    public function log($message, $level = self::LEVEL_INFO, $category = null) {
        if ($this->stream === null) {
            $stream = @fopen($this->filename, 'a+');
            if ($stream === false) {
                throw new \Exception('unable to write into log file "' . $this->filename . '"');
            }
            $this->stream = $stream;
        }
        fwrite($this->stream, mb_convert_encoding($this->formatMessage($message, $level, $category), $this->charset));
        fflush($this->stream);
    }

    public function __destruct() {
        if ($this->stream !== null) {
            fclose($this->stream);
        }
    }

}

