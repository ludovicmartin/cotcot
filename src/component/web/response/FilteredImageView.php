<?php

namespace cotcot\component\web\response;

/**
 * From file view.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FilteredImageView extends Response {

    /** @var string ImageMagick executable path */
    public $imageMagickPath = '/usr/bin/convert';

    /** @var string cache path */
    public $cachePath = '/tmp';

    /** @var string name of file to sent to client */
    public $filename;

    /** @var \Tools\ImageFilterParams filter params */
    public $params;

    /** @var int client cache TTL */
    public $clientCacheTtl = 31536000;

    /** @var \cotcot\component\web\request\Request request */
    public $request;

    /** @var string cache filename */
    private $cacheFilename;

    public function prepare() {
        if ($this->params !== null && $this->params instanceof \cotcot\tools\ImageFilterParams) {
            $this->cacheFilename = \cotcot\tools\ImageFilterUtils::filterImage($this->imageMagickPath, $this->cachePath, $this->filename, $this->params);
            $sourceFileTimestamp = is_file($this->filename) && is_readable($this->filename) ? filemtime($this->filename) : null;
            if ($this->cacheFilename !== null && $sourceFileTimestamp !== null) {
                $this->headers['Content-Type'] = 'image/jpeg';
                $this->headers['Content-Length'] = @filesize($this->cacheFilename);
                if ($this->clientCacheData !== null) {
                    $this->clientCacheData->lastModified = $sourceFileTimestamp;
                    if ($this->clientCacheTtl !== null) {
                        $this->clientCacheData->expires = $sourceFileTimestamp + $this->clientCacheTtl;
                    }
                }
                return;
            }
        }
        throw new \Exception('unable to filter image');
    }

    public function sendContent() {
        if ($this->cacheFilename !== null) {
            readfile($this->cacheFilename);
        }
    }

}
