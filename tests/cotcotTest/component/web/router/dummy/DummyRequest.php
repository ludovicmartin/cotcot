<?php

namespace cotcotTest\component\web\router\dummy;

class DummyRequest extends \cotcot\component\web\request\Request {

    public $cookie = array();
    public $file = array();
    public $get = array();
    public $post = array();
    public $server = array();

    public function getGet($index = null, $default = null) {
        return $index === null ? $this->get : (isset($this->get[$index]) ? $this->get[$index] : $default);
    }

    public function getPost($index = null, $default = null) {
        return $index === null ? $this->post : (isset($this->post[$index]) ? $this->post[$index] : $default);
    }

    public function getFile($index = null, $default = null) {
        return $index === null ? $this->file : (isset($this->file[$index]) ? $this->file[$index] : $default);
    }

    public function getServer($index = null, $default = null) {
        return $index === null ? $this->server : (isset($this->server[$index]) ? $this->server[$index] : $default);
    }

    public function getCookie($index = null, $default = null) {
        return $index === null ? $this->cookie : (isset($this->cookie[$index]) ? $this->cookie[$index] : $default);
    }

    public function setCookie($index, $value) {
        
    }

    public function setFile($index, $value) {
        
    }

    public function setGet($index, $value) {
        
    }

    public function setPost($index, $value) {
        
    }

    public function setServer($index, $value) {
        
    }

}