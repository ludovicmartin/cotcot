<?php

namespace cotcot\component\web\flashMessage;

/**
 * Default flash messenger.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DefaultFlashMessenger extends FlashMessenger {

    /** @var cotcot\component\web\session\Session session */
    public $session;

    /** @var string $sessionIndex index to store data in session */
    public $sessionIndex = 'COTCOT_MESSENGER';

    public function addMessage($type, $message) {
        $data = $this->session->getItem($this->sessionIndex, array());
        if (!isset($data[$type])) {
            $data[$type] = array();
        }
        if (is_array($message)) {
            foreach ($message as $itemMessage) {
                $this->addMessage($type, $itemMessage);
            }
            return;
        }
        $data[$type][] = $message;
        $this->session->setItem($this->sessionIndex, $data);
    }

    public function getMessages($type) {
        $data = $this->session->getItem($this->sessionIndex);
        return isset($data[$type]) ? $data[$type] : array();
    }

    public function popMessages($type = null) {
        $messages = $this->getMessages($type);
        if ($type !== null) {
            $data = $this->session->getItem($this->sessionIndex);
            $data[$type] = array();
            $this->session->setItem($this->sessionIndex, $data);
        }
        return $messages;
    }

}
