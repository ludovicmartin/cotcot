<?php

namespace cotcot\component\i18n;

/**
 * I18n manager.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class I18nManager {

    /**
     * Translate a message.
     * @param string $category category
     * @param string $message message to translate
     * @param array $params params
     * @param string $localename locale name
     * @return string translated message
     */
    public abstract function translate($category, $message, $params = array(), $localename = null);
}
