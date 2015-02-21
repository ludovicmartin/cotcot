<?php

namespace cotcot\tools;

/**
 * Image filter params.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ImageFilterParams {

    /**
     * @var int
     */
    public $maxWidth;

    /**
     * @var int
     */
    public $maxHeight;

    /**
     * @var int
     */
    public $quality;

    /**
     * @var int
     */
    public $cropX;

    /**
     * @var int
     */
    public $cropY;

    /**
     * @var int
     */
    public $cropWidth;

    /**
     * @var int
     */
    public $cropHeight;

    /**
     * @var int
     */
    public $rotation;

    public function __construct($maxWidth, $maxHeight, $quality = 80, $cropX = null, $cropY = null, $cropWidth = null, $cropHeight = null, $rotation = null) {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->quality = $quality;
        $this->cropX = $cropX;
        $this->cropY = $cropY;
        $this->cropWidth = $cropWidth;
        $this->cropHeight = $cropHeight;
        $this->rotation = $rotation;
    }

}
