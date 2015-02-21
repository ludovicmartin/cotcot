<?php

namespace cotcot\component\database;

/**
 * Update query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class UpdateQuery extends FilteringQuery {

    /** @var string $table table */
    protected $table = null;

    /** @var array $values values */
    protected $values = array();

    /**
     * Set UPDATE clause.
     * @param string $table table name
     * @return InsertQuery query
     */
    public function update($table) {
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
            $values = array();
            foreach ($this->database->arrayQuote($this->values) as $key => $value) {
                $values[] = $key . '=' . $value;
            }
            $parts = array();
            $parts[] = 'UPDATE ' . $this->table;
            if (count($values) > 0) {
                $parts[] = 'SET ' . implode(',', $values);
            }
            $parts[] = implode(' ', $this->where);
            $parts[] = $this->limit;
            return $this->bindValuesToString(\cotcot\tools\StringUtils::implodePack(PHP_EOL, $parts));
        }
        throw new \cotcot\component\exception\DatabaseException('UPDATE clause must be set');
    }

}
