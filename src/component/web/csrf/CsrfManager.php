<?php

namespace cotcot\component\web\csrf;

/**
 * CSRF manager.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class CsrfManager {

    /** @var \cotcot\component\key\KeyManager key manager */
    public $keyManager;

    /** @var \cotcot\component\web\session\Session session */
    public $session;

    /** @var  string salt */
    public $salt = 'some salt...';

    /** @var string $sessionIndex index to store data in session */
    public $sessionIndex = 'COTCOT_CSRF';

    /** @var int time to live in second */
    public $ttl = 300;

    /**
     * Generate a CSRF key value value.
     * @return string key value
     */
    public function generateKey() {
        $seed = bin2hex(openssl_random_pseudo_bytes(20));
        $key = $this->keyManager->buildLimitedLifeTimeKey($this->salt . $seed);
        $this->session->setItem($this->sessionIndex, array('value' => $key, 'seed' => $seed));
        return $key;
    }

    /**
     * Validate a CSRF key value value.
     * @param string $value value
     * @return boolean true if validated, false otherwise
     */
    public function validateKey($value) {
        $key = $this->session->getItem($this->sessionIndex);
        return $key !== null && $key['value'] === $value && $this->keyManager->limitedLifeTimeKeyCheck($value, $this->salt . $key['seed'], $this->ttl);
    }

}
