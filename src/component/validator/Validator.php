<?php

namespace cotcot\component\validator;

/**
 * Validator.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Validator {

    /** @var string corresponding error message */
    public $message = null;

    /** @var string corresponding error message */
    private $messages = array();

    /**
     * Validate a value.
     * @param mixed $value value to validate
     * @param mixed $context all values of the set
     * @return bool true if data is validated, false otherwize
     */
    public function isValid($value, $context = array()) {
        $this->messages = array();
        if (!$this->validate($value, $context)) {
            $this->addMessage($this->message);
            return false;
        }
        return true;
    }

    /**
     * Performe value validation.
     * @param mixed $value value to validate
     * @param mixed $context context
     * @return bool true if data is validated, false otherwize
     */
    abstract protected function validate($value, $context);

    /**
     * Get messages resulting of validation.
     * @return array messages
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Test if has message.
     * @return boolean
     */
    public function hasMessage() {
        return count($this->messages) > 0;
    }

    /**
     * Add a message.
     * @param string|array $message message
     * @return void
     */
    protected function addMessage($message) {
        if ($message !== null) {
            if (!is_array($message)) {
                $message = array($message);
            }
            foreach ($message as $messageItem) {
                if ($messageItem !== null && strlen($messageItem)) {
                    if (!in_array($messageItem, $this->messages)) {
                        $this->messages[] = $messageItem;
                    }
                }
            }
        }
    }

}
