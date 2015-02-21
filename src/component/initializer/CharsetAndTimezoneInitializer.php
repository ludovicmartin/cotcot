<?php

namespace cotcot\component\initializer;

/**
 * Simple charset and timezone initializer.
 * Initilize default timezone and mbstring and iconv encoding.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class CharsetAndTimezoneInitializer implements \cotcot\core\Initializable {

    /** @var string charset name */
    public $charset = null;

    /** @var string timezone name */
    public $timezone = null;

    public function init() {
        if ($this->charset !== null) {
            //Default charset init
            ini_set('default_charset', $this->charset);
            if (PHP_VERSION_ID <= 50600) {
                //"mbstring" init
                if (function_exists('mb_get_info')) {
                    mb_internal_encoding($this->charset);
                    mb_regex_encoding($this->charset);
                }
            }
        }
        //Time zone init
        if ($this->timezone !== null) {
            date_default_timezone_set($this->timezone);
        }
    }

}
