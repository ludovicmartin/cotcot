<?php

namespace cotcot\component\locale;

/**
 * Locale detector (using HTTP header).
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class RequestHeaderLocaleDetector extends LocaleDetector {

    /** @var \cotcot\component\web\request\Request */
    public $request = null;

    public function getLocale() {
        $localeString = $this->request->getServer('HTTP_ACCEPT_LANGUAGE');
        $comaIndex = strpos($localeString, ',');
        if ($comaIndex !== false) {
            $localeString = substr($localeString, 0, $comaIndex);
        }
        return \cotcot\component\locale\Locale::parse(preg_replace('/;.*$/', '', $localeString));
    }

}
