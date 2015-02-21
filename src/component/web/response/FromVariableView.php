<?php

namespace cotcot\component\web\response;

/**
 * From variable view.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FromVariableView extends Response {

    /** @var string content sent to client */
    public $content;

    public function prepare() {
        if ($this->content !== null) {
            $this->headers['Content-Length'] = strlen($this->content);
        }
    }

    public function sendContent() {
        print($this->content);
    }

}
