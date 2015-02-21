<?php

namespace cotcot\component\database;

/**
 * Delete query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class DeleteQuery extends FilteringQuery {

    /** @var string $table table */
    protected $table = null;

    /**
     * Set FROM clause.
     * @param string $table table name
     * @return InsertQuery query
     */
    public function from($table) {
        $this->table = $table;
        return $this;
    }

    public function toSql() {
        if ($this->table) {
            $parts = array();
            $parts[] = 'DELETE FROM ' . $this->table;
            $parts[] = implode(' ', $this->where);
            $parts[] = $this->limit;
            return $this->bindValuesToString(\cotcot\tools\StringUtils::implodePack(PHP_EOL, $parts));
        }
        throw new \cotcot\component\exception\DatabaseException('FROM clause must be set');
    }

}
