<?php

namespace cotcot\component\web\response;

/**
 * PHP view.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class PhpView extends Response {

    /** @var string base path for view files */
    public $basePath;

    /** @var string view filename */
    public $filename = null;

    /** @var string view filename */
    public $defaultFilenameExtension = 'php';

    /** @var layout filename */
    public $layoutFilename = null;

    public function sendContent() {
        $this->renderPartial($this->layoutFilename !== null ? $this->layoutFilename : $this->filename, $this->variables);
    }

    /**
     * Render a partial view.
     * @param string $filename file name (relative to the base path)
     * @param array $variables variables injected to the view when rendered
     */
    public function renderPartial($filename, $variables = null) {
        if (is_array($variables)) {
            extract($variables);
        }
        include $this->buildFullPath($filename);
    }

    /**
     * Build the full path filename by adding the base path and the extension.
     * @param string $filename filename
     * @return string full path
     */
    public function buildFullPath($filename) {
        return $this->basePath . DIRECTORY_SEPARATOR . (strrpos($filename, '.') !== false ? $filename : ($filename . '.' . $this->defaultFilenameExtension));
    }

}
