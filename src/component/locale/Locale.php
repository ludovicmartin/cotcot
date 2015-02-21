<?php

namespace cotcot\component\locale;

/**
 * Locale.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Locale {

    /** @var string locale language */
    public $language = null;

    /** @var string locale region */
    public $region = null;

    /** @var string locale name */
    public $name = null;

    /**
     * Parse a locale name.
     * @param string $inputString locale string to parse
     */
    public static function parse($inputString) {
        if (preg_match('/^[a-z]+$|^[a-z]+-[a-z]+$/i', $inputString)) {
            $data = explode('-', $inputString, 2);
            $locale = new Locale();
            $locale->language = strtolower($data[0]);
            $locale->region = count($data) > 1 ? strtoupper($data[1]) : null;
            $locale->name = $locale->language . ($locale->region !== null ? ('-' . $locale->region) : '');
            return $locale;
        }
        return null;
    }

    public function __toString() {
        return $this->name;
    }

}