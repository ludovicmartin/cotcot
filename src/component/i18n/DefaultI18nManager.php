<?php

namespace cotcot\component\i18n;

/**
 * Default i18n manager.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultI18nManager extends I18nManager{

    /** @var \cotcot\component\locale\LocaleDetector locale detector */
    public $localeDetector;

    /** @var string base path to locate translations */
    public $basePath = '';

    /** @var array message file cache */
    private $cache = array();

    /**
     * Translate a message.
     * @param string $category category
     * @param string $message message to translate
     * @param array $params params
     * @param string $localename locale name
     * @return string translated message
     */
    public function translate($category, $message, $params = array(), $localename = null) {
        //Getting local name
        if ($localename === null) {
            $localename = $this->localeDetector->getLocale()->name;
        }
        //Security check
        if (strpos($category, DIRECTORY_SEPARATOR) !== false) {
            throw new \Exception('unvalid category string');
        }
        if (strpos($localename, DIRECTORY_SEPARATOR) !== false) {
            throw new \Exception('unvalid locale name string');
        }
        //Translation loading
        $messageFilename = $this->basePath . DIRECTORY_SEPARATOR . $localename . DIRECTORY_SEPARATOR . $category . '.php';
        if (!isset($this->cache[$messageFilename])) {
            $this->cache[$messageFilename] = is_file($messageFilename) ? include $messageFilename : array();
        }
        $translatedMessage = isset($this->cache[$messageFilename][$message]) ? $this->cache[$messageFilename][$message] : '';
        //Params merging
        foreach ($params as $key => $value) {
            $translatedMessage = str_replace($key, $value, $translatedMessage);
        }
        return $translatedMessage;
    }

}