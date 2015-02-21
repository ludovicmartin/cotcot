<?php

namespace cotcot\component\web\response;

/**
 * Redirect response.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Redirect extends Response {

    /** @var string target URL */
    public $location = '/';

    public function sendContent() {
        
    }

    public function sendHeaders() {
        $this->httpCode = 302;
        $this->headers['Location'] = $this->location;
        parent::sendHeaders();
    }

}
