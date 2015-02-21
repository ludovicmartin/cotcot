<?php

namespace cotcot\component\database;

/**
 * Select query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class SelectQuery extends FilteringQuery implements \Countable {

    /** @var string select clause */
    protected $select = 'SELECT *';

    /** @var array from clause */
    protected $from = null;

    /** @var array join clause */
    protected $join = array();

    /** @var array group by clause */
    protected $groupBy = null;

    /** @var array order by clause */
    protected $orderBy = null;

    /**
     * Execute a SELECT COUNT query.
     * @return int result count
     */
    public function count() {
        $query = new SelectQuery();
        $query->database = $this->database;
        $query->bindedValues = $this->bindedValues;
        $query->where = $this->where;
        $query->having = $this->having;
        $query->limit = $this->limit;
        $query->from = $this->from;
        $query->join = $this->join;
        $query->groupBy = $this->groupBy;
        $query->orderBy = null;
        return $this->database->query('SELECT COUNT(*) FROM (' . PHP_EOL . $query->toSql() . PHP_EOL . ') AS cotcot_count_query')->fetchScalar();
    }

    /**
     * Set the SELECT clause.
     * @param string|array $select select items
     * @param boolean $distinct distinct flag
     * @return SelectQuery query
     */
    public function select($select = '*', $distinct = false) {
        $this->select = 'SELECT ' . ($distinct ? 'DISTINCT ' : '') . (is_array($select) ? implode(',', $select) : $select);
        return $this;
    }

    /**
     * Set the FROM clause.
     * @param string $table table name
     * @return SelectQuery query
     */
    public function from($table) {
        $this->from = 'FROM ' . $table;
        return $this;
    }

    /**
     * Append a join clause.
     * @param string $table table name
     * @param string $on condition
     * @param string $type join type
     * @return SelectQuery query
     */
    public function join($table, $on, $type = 'INNER') {
        $this->join[] = $type . ' JOIN ' . $table . ' ON ' . $on;
        return $this;
    }

    /**
     * Set the GROUP BY clause.
     * @param string|array $groupBy clause elements (null value delete the clause)
     * @return SelectQuery query
     */
    public function groupBy($groupBy) {
        if (is_array($groupBy)) {
            $groupBy = implode(',', $groupBy);
        }
        $this->groupBy = $groupBy !== null ? ('GROUP BY ' . $groupBy) : null;
        return $this;
    }

    /**
     * Set the ORDER BY clause.
     * @param string|array $orderBy clause elements (null value delete the clause)
     * @return SelectQuery query
     */
    public function orderBy($orderBy) {
        if (is_array($orderBy)) {
            $orderBy = implode(',', $orderBy);
        }
        $this->orderBy = $orderBy !== null ? ('ORDER BY ' . $orderBy) : null;
        return $this;
    }

    public function toSql() {
        return $this->bindValuesToString(\cotcot\tools\StringUtils::implodePack(PHP_EOL, array($this->select, $this->from, implode(PHP_EOL, $this->join), $this->having, implode(' ', $this->where), $this->groupBy, $this->orderBy, $this->limit)));
    }

}
