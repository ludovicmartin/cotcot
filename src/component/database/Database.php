<?php

namespace cotcot\component\database;

/**
 * Database access class.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Database {

    /** @var \cotcot\component\database\ConnectionParams connection params */
    public $params;

    /** @var Transaction current transaction */
    public $currentTransaction = null;

    /**
     * Test if connected to database.
     * @return boolean cennected status
     */
    public abstract function isConnected();

    /**
     * Open database connection.
     */
    public function connect() {
        $this->currentTransaction = null;
    }

    /**
     * Close database connection.
     */
    public function disconnect() {
        $this->currentTransaction = null;
    }

    /**
     * Escape a string.
     * @param string $string input string
     * @return string escaped string
     */
    public abstract function quote($string);

    /**
     * Escape elements of an array.
     * @param array $array input array
     * @return array item échappées ([x,y,z,NULL] => ['x','y','z',NULL])
     */
    public function arrayQuote($array) {
        return array_map(array($this, 'quote'), $array);
    }

    /**
     * Execute a query.
     * @param string $query SQL query string
     * @return ResultSet result
     * @throws \cotcot\component\exception\DatabaseException thrown on query error
     */
    public abstract function query($query);

    /**
     * Build a SELECT query.
     * @param array $select select items
     * @param boolean $distinct distinct flag
     * @return SelectQuery query
     */
    public function select($select = '*', $distinct = false) {
        $query = new SelectQuery();
        $query->database = $this;
        if ($select !== null || $distinct !== null) {
            $query->select($select, $distinct);
        }
        return $query;
    }

    /**
     * Build an INSERT query.
     * @param string $table table name
     * @return InsertQuery query
     */
    public function insert($table) {
        $query = new InsertQuery();
        $query->database = $this;
        $query->into($table);
        return $query;
    }

    /**
     * Build an UPDATE query.
     * @param string $table table name
     * @return UpdateQuery query
     */
    public function update($table) {
        $query = new UpdateQuery();
        $query->database = $this;
        $query->update($table);
        return $query;
    }

    /**
     * Build a DELETE query.
     * @param string $table table name
     * @return DeleteQuery query
     */
    public function delete($table) {
        $query = new DeleteQuery();
        $query->database = $this;
        $query->from($table);
        return $query;
    }

    /**
     * Build a raw query.
     * @param string $sql SQL query
     * @return RawQuery query
     */
    public function raw($sql) {
        $query = new RawQuery();
        $query->database = $this;
        $query->sql($sql);
        return $query;
    }

    /**
     * Get the next ID for a given table.
     * @param string $tableName table name
     * @return string new ID
     */
    public abstract function getNextId($tableName);

    /**
     * Get next value for a sequence.
     * @param string $sequenceName sequence name
     * @return string new ID
     */
    public abstract function getNextSequenceValue($sequenceName);

    /**
     * Check if a partial query doesn't contain a code injection.
     *
     * ex :
     *  "my_fonction(\'a;b--c/*\')" => true
     *  "test--A comment" => false
     *  "test/*A comment" => false
     *  "test;aaa" => false
     *  "test/aaa" => true
     *  "test-aaa" => true
     *  "test\\aaa" => true
     *
     * @param string $partialQuery partial query to check
     * @return boolean safe status
     */
    public function isPartialQuerySafe($partialQuery) {
        //0 - Normal
        //1 - Comment start "--"
        //2 - Comment start "/*"
        //3 - String start
        //4 - In an escape section (in a string)
        $status = 0;
        $length = strlen($partialQuery);
        for ($i = 0; $i < $length; $i++) {
            switch ($partialQuery{$i}) {
                case '-':
                    if ($status == 0) {
                        $status = 1;
                    } elseif ($status == 1) {
                        return false;
                    }
                    break;
                case '/':
                    if ($status == 0) {
                        $status = 2;
                    }
                    break;
                case '*':
                    if ($status == 2) {
                        return false;
                    }
                    break;
                case ';':
                    if ($status == 0 || $status == 1 || $status == 2) {
                        return false;
                    }
                    break;
                case '\'':
                    if ($status == 0) {
                        $status = 3;
                    } elseif ($status == 3) {
                        $status = 0;
                    }
                    break;
                case '\\':
                    if ($status == 3) {
                        $status = 4;
                    }
                    break;
                default:
                    if ($status == 4) {
                        $status = 3;
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * Start a transation.
     * @return Transaction transaction
     */
    public function getTransaction() {
        $transaction = new \cotcot\component\database\Transaction();
        $transaction->database = $this;
        $transaction->parentTransaction = $this->currentTransaction;
        $transaction->begin();
        $this->currentTransaction = $transaction;
        return $transaction;
    }

    /**
     * Notify database class that a transaction is terminated (commit or
     * rollback).
     * This method is called by transaction class.
     * You should never call it directly.
     * @param Transaction $transaction transaction
     */
    public function fireTerminatedTransaction($transaction) {
        $this->currentTransaction = $transaction->parentTransaction;
    }

}
