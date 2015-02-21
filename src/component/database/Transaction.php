<?php

namespace cotcot\component\database;

/**
 * Transaction class.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Transaction {

    /** @var int $counter transaction ID counter */
    private static $idCounter = 0;

    /** @var string $id transaction ID */
    private $id = null;

    public function __construct() {
        $this->id = 't' . self::$idCounter++;
    }

    /** @var \cotcot\component\database\Database database */
    public $database;

    /** @var Transaction parent transaction */
    public $parentTransaction;

    /**
     * Begin the transaction.
     */
    public function begin() {
        $this->database->query($this->parentTransaction !== null ? ('SAVEPOINT ' . $this->id) : 'BEGIN')->close();
    }

    /**
     * Commit the transaction.
     */
    public function commit() {
        if ($this->parentTransaction === null) {
            $this->database->query('COMMIT')->close();
            $this->database->fireTerminatedTransaction($this);
        }
    }

    /**
     * Rollback the transaction.
     */
    public function rollback() {
        $this->database->query($this->parentTransaction !== null ? ('ROLLBACK TO ' . $this->id) : 'ROLLBACK')->close();
        $this->database->fireTerminatedTransaction($this);
    }

}
