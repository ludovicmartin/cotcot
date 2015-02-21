<?php

namespace cotcot\component\web\request;

/**
 * Default request.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultRequest extends Request {

    public function getGet($index = null, $default = null) {
        return $index === null ? $_GET : (isset($_GET[$index]) ? $_GET[$index] : $default);
    }

    public function getPost($index = null, $default = null) {
        return $index === null ? $_POST : (isset($_POST[$index]) ? $_POST[$index] : $default);
    }

    public function getFile($index = null, $default = null) {
        if ($index !== null) {
            return isset($_FILES[$index]) ? $this->rebuildFileArray($_FILES[$index]) : $default;
        }
        $result = array();
        foreach ($_FILES as $key => $fileData) {
            $result[$key] = $this->rebuildFileArray($fileData);
        }
        return $result;
    }

    /**
     * Rebuild file data.
     * @param type $fileData file data
     * @return array rebuilt file data
     */
    private function rebuildFileArray($fileData) {
        if (
                isset($fileData['name']) && is_array($fileData['name']) &&
                isset($fileData['type']) && is_array($fileData['type']) &&
                isset($fileData['tmp_name']) && is_array($fileData['tmp_name']) &&
                isset($fileData['error']) && is_array($fileData['error']) &&
                isset($fileData['size']) && is_array($fileData['size'])
        ) {
            $result = array();
            foreach ($fileData as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $result[$key2][$key1] = $value2;
                }
            }
            return $result;
        }
        return $fileData;
    }

    public function getServer($index = null, $default = null) {
        return $index === null ? $_SERVER : (isset($_SERVER[$index]) ? $_SERVER[$index] : $default);
    }

    public function getCookie($index = null, $default = null) {
        return $index === null ? $_COOKIE : (isset($_COOKIE[$index]) ? $_COOKIE[$index] : $default);
    }

}
