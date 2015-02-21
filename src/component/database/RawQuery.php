<?php

namespace cotcot\component\database;

/**
 * Raw query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class RawQuery extends Query implements \Countable {

    /** @var string SQL query */
    protected $sql = null;

    /**
     * Set the SQL query.
     * @param string $sqlQuery SQL query
     * @return RawQuery
     */
    public function sql($sqlQuery) {
        $this->sql = $sqlQuery;
        return $this;
    }

    /**
     * Execute a SELECT COUNT query.
     * @return int result count
     */
    public function count() {
        return $this->database->query('SELECT COUNT(*) FROM (' . PHP_EOL . $this->toSql() . PHP_EOL . ') AS cotcot_count_query')->fetchScalar();
    }

    public function toSql() {
        if ($this->sql !== null) {
            return $this->bindValuesToString($this->sql);
        }
        throw new \cotcot\component\exception\DatabaseException('SQL query must be set');
    }

}
