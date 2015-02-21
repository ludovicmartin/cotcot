<?php

namespace cotcot\component\key;

/**
 * Key manager.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class KeyManager {

    /** @var string internal lifetime check date format */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * Build a limited life time key.
     * @param string $salt salt
     * @param string $date reference date ('AAAA-MM-JJ HH:MM:SS' format or null for current time)
     * @return string key
     */
    public function buildLimitedLifeTimeKey($salt, $date = null) {
        if ($date === null) {
            $date = date($this->dateFormat);
        }
        return strtolower(hash('sha256', $salt . '-' . $date));
    }

    /**
     * Check a limited life time key.
     * @param string $key key
     * @param string $salt salt
     * @param int $ttl time to live in seconde
     * @param string $date reference date ('AAAA-MM-JJ HH:MM:SS' format or null for current time)
     * @return boolean true if key is valid
     */
    public function limitedLifeTimeKeyCheck($key, $salt, $ttl = 0, $date = null) {
        if ($date === null) {
            $date = date($this->dateFormat);
        }
        $key = strtolower($key);
        $time = strtotime($date);
        for ($i = 0; $i <= $ttl; $i++) {
            if ($key === $this->buildLimitedLifeTimeKey($salt, date($this->dateFormat, $time - $i))) {
                return true;
            }
        }
        return false;
    }

}
