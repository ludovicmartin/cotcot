<?php

namespace cotcot\component\locale;

/**
 * Locale detector.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class LocaleDetector {

    /**
     * Get detected locale.
     * @return Locale|null detected locale or null if none
     */
    abstract public function getLocale();
}
