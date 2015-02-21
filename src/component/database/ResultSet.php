<?php

namespace cotcot\component\database;

/**
 * Database result set.
 * It implements the Iterator interface. The "close" method is not automaticaly
 * called after iterating .
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class ResultSet implements \Iterator {

    /** @var ressource query result */
    public $result = null;

    /** @var mixed current result */
    private $currentResult = null;

    /** @var mixed current result index */
    private $currentIndex = 0;

    /**
     * Get last insert ID.
     * @return string id
     */
    public abstract function getLastInsertId();

    /**
     * Get affected row count.
     * @return int count
     */
    public abstract function getAffectedRows();

    /**
     * Fetch next row.
     * @return array|null row content as an associative array or null if no element
     */
    public abstract function fetch();

    /**
     * Fetch all rows and close the result set.
     * @return array|null all rows content as an associative array or null on error
     */
    public abstract function fetchAll();

    /**
     * Fetch the first row and close the result set.
     * @return array|null row content as associative array or null if no element
     */
    public abstract function fetchRow();

    /**
     * Fetch the first column of all rows an close the result set.
     * @param int $column column number
     * @return array|null column values or null on error
     */
    public abstract function fetchCol($column = 0);

    /**
     * Fetch the first column of the first row and close the result set.
     * @return mixed|null value or null if none
     */
    public abstract function fetchScalar();

    /**
     * Close the result set.
     * @return void
     */
    public abstract function close();

    public function current() {
        $this->checkCurrentResult();
        return $this->currentResult;
    }

    public function key() {
        return $this->currentIndex;
    }

    public function next() {
        $this->currentResult = $this->fetch();
        $this->currentIndex++;
    }

    public function rewind() {
        //Can't rewind
    }

    public function valid() {
        $this->checkCurrentResult();
        return $this->currentResult !== null;
    }

    private function checkCurrentResult() {
        if ($this->currentResult === null) {
            $this->currentResult = $this->fetch();
        }
    }

}
