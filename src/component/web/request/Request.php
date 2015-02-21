<?php

namespace cotcot\component\web\request;

/**
 * Request.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Request {

    /**
     * Get $_GET data.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getGet($index = null, $default = null);

    /**
     * Get $_POST data.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getPost($index = null, $default = null);

    /**
     * Get $_FILES data.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getFile($index = null, $default = null);

    /**
     * Get $_SERVER data.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getServer($index = null, $default = null);

    /**
     * Get $_COOKIE data.
     * @param string $index index to get
     * @param mixed $default default value
     * @return array|mixed data
     */
    public abstract function getCookie($index = null, $default = null);
}
