<?php

namespace cotcot\component\web\session;

/**
 * Default session.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultSession extends Session implements \cotcot\core\Initializable {

    /** @var boolean auto start on each query */
    public $autoStart = true;

    public function init() {
        if ($this->autoStart) {
            $this->open();
        }
    }

    public function getItem($key, $default = null) {
        if (!isset($_SESSION[$key])) {
            return $default;
        }
        return $_SESSION[$key];
    }

    public function setItem($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function getId() {
        return session_id();
    }

    public function open() {
        session_start();
    }

    public function close() {
        session_destroy();
    }

    public function writeClose() {
        session_write_close();
    }

    public function getStatus() {
        return session_status();
    }

    public function renew() {
        session_regenerate_id();
    }

}
