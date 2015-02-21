<?php

namespace cotcot\component\database;

/**
 * Insert query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class InsertQuery extends Query {

    /** @var string $table table */
    protected $table = null;

    /** @var array $values values */
    protected $values = array();

    /**
     * Set INTO clause.
     * @param string $table table name
     * @return InsertQuery query
     */
    public function into($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Set values to insert
     * @param type $values
     * @return InsertQuery query
     */
    public function values($values) {
        $this->values = $values;
        return $this;
    }

    public function toSql() {
        if ($this->table) {
            return $this->bindValuesToString('INSERT INTO ' . $this->table . ( count($this->values) > 0 ? (' (' . implode(',', array_keys($this->values)) . ') VALUES (' . implode(',', $this->database->arrayQuote($this->values)) . ')') : ''));
        }
        throw new \cotcot\component\exception\DatabaseException('INTO clause must be set');
    }

}
