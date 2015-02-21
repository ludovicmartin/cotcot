<?php

namespace cotcot\component\web\response;

/**
 * Response.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Response implements \cotcot\core\RuntimeContextAware {

    /** @var int HTTP code */
    public $responseCode = 200;

    /** @var array headers sent whene "sendHeaders" is invoked */
    public $headers = array(
        'Content-Type' => 'text/html; charset=utf-8',
    );

    /** @var array variables injected to the view when rendered */
    public $variables = array();

    /** @var clientCache\ClientCacheData client cache data */
    public $clientCacheData;

    /** @var \cotcot\coreRuntimeContextAware runtime context */
    public $runtimeContext;

    /**
     * Send response headers.
     * @return void
     */
    public function sendHeaders() {
        http_response_code($this->responseCode);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value, true);
        }
    }

    /**
     * Send response content.
     * @return void
     */
    public abstract function sendContent();

    /**
     * Prepare the response before content sending.
     * @return void
     */
    public function prepare() {
        
    }

}
