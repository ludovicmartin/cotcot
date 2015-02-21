<?php

namespace cotcot\component\web\flashMessage;

/**
 * flash messenger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class FlashMessenger {

    const TYPE_ERROR = 0;
    const TYPE_INFO = 1;
    const TYPE_SUCCESS = 2;
    const TYPE_WARNING = 3;

    /**
     * Add a message.
     * @param int $type type
     * @param string|array $message message
     */
    public abstract function addMessage($type, $message);

    /**
     * Get messages.
     * @param int $type type
     * @return array messages
     */
    public abstract function getMessages($type);

    /**
     * Get messages resulting of validation and empty it.
     * @param int $type type
     * @return array messages
     */
    public abstract function popMessages($type = null);

    /**
     * Add an error message
     * @param string|array $message message
     */
    public function addError($message) {
        $this->addMessage(self::TYPE_ERROR, $message);
    }

    /**
     * Add an error message
     * @param string|array $message message
     */
    public function addInfo($message) {
        $this->addMessage(self::TYPE_INFO, $message);
    }

    /**
     * Add a success message
     * @param string|array $message message
     */
    public function addSuccess($message) {
        $this->addMessage(self::TYPE_SUCCESS, $message);
    }

    /**
     * Add a warning message
     * @param string|array $message message
     */
    public function addWarning($message) {
        $this->addMessage(self::TYPE_WARNING, $message);
    }

}
