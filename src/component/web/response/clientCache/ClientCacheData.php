<?php

namespace cotcot\component\web\response\clientCache;

/**
 * Client cache data.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ClientCacheData {

    /** @var int expire timestamp (in second) */
    public $expires = null;

    /** @var int last modified timestamp (in second) */
    public $lastModified = null;

    /** @var string pragma string */
    public $pragma = null;

    /** @var string cache control string */
    public $cacheControl = null;

    /**
     * Get data as HTTP header array.
     * @return array headers
     */
    public function getHeaders() {
        $headers = array();
        if ($this->expires !== null) {
            $headers['Expires'] = gmdate('D, d M Y H:i:s', $this->expires) . ' GMT';
        }
        if ($this->lastModified !== null) {
            $headers['Last-Modified'] = gmdate('D, d M Y H:i:s', $this->lastModified) . ' GMT';
        }
        if ($this->pragma !== null) {
            $headers['Pragma'] = $this->pragma;
        }
        if ($this->cacheControl !== null) {
            $headers['Cache-Control'] = $this->cacheControl;
        }
        return $headers;
    }

}
