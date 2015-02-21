<?php

namespace cotcot\component\web\response;

/**
 * From file view.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FromFileView extends Response {

    /** @var string name of file to sent to client */
    public $filename;

    public function prepare() {
        if (is_readable($this->filename)) {
            $size = @filesize($this->filename);
            if ($size !== false) {
                $this->headers['Content-Length'] = $size;
            }
            return;
        }
        throw new \Exception('content file not found');
    }

    public function sendContent() {
        readfile($this->filename);
    }

}
