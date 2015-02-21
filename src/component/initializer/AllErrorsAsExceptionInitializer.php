<?php

namespace cotcot\component\initializer;

/**
 * Initializer that converts all errors to exception.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class AllErrorsAsExceptionInitializer implements \cotcot\core\Initializable {

    /** @var int error types (default: E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING) */
    public $errorTypes;

    public function init() {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \Exception($errstr, $errno);
        }, $this->errorTypes !== null ? $this->errorTypes : (E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING));
    }

}
