<?php

namespace cotcot\component\locale;

/**
 * Default locale detector.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultLocaleDetector extends LocaleDetector {

    /** @var LocaleDetector local detectors */
    public $detectors = array();

    /** @var array allowed locale names */
    public $allowedLocales = array('fr-FR');

    /** @var Locale */
    private $locale = null;

    public function getLocale() {
        if ($this->locale === null) {
            foreach ($this->detectors as $detector) {
                $locale = $detector->getLocale();
                if ($locale !== null && in_array($locale->name, $this->allowedLocales)) {
                    $this->locale = $locale;
                    break;
                }
            }
        }
        return $this->locale;
    }

}
