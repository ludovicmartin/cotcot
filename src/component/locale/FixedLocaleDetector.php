<?php

namespace cotcot\component\locale;

/**
 * Locale detector (fixed by configuration).
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class FixedLocaleDetector extends LocaleDetector {

    /** @var string locale name */
    public $localeName = 'fr-FR';

    public function getLocale() {
        return Locale::parse($this->localeName);
    }

}
