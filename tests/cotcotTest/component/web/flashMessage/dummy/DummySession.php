<?php

namespace cotcotTest\component\web\flashMessage\dummy;

class DummySession extends \cotcot\component\web\session\Session {

    public $items = array();
    public $status = PHP_SESSION_NONE;

    public function close() {
        
    }

    public function getId() {
        return 'id';
    }

    public function getItem($key, $default = null) {
        return isset($this->items[$key]) ? $this->items[$key] : $default;
    }

    public function getStatus() {
        return $this->status;
    }

    public function open() {
        $this->status = PHP_SESSION_ACTIVE;
    }

    public function renew() {
        
    }

    public function setItem($key, $value) {
        $this->items[$key] = $value;
    }

    public function writeClose() {
        
    }

}