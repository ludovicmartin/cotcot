<?php

namespace cotcot\component\database;

/**
 * Filtering query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class FilteringQuery extends Query {

    /** @var array where clause */
    protected $where = array();

    /** @var string having clause */
    protected $having = null;

    /** @var string limit clause */
    protected $limit = null;

    /**
     * Append a "WHERE id=?" condition. 
     * @param string $id search id
     * @param string $operator operator
     * @return FilteringQuery query
     */
    public function whereId($id, $operator = 'AND') {
        return $this->where('id=' . $this->database->quote($id), $operator);
    }

    /**
     * Append a WHERE condition. 
     * @param string $condition condition
     * @param string $operator operator
     * @return FilteringQuery query
     */
    public function where($condition, $operator = 'AND') {
        if ($condition === ')' || substr(end($this->where), -1) == '(') {
            $this->where[] = $condition;
        } else {
            $this->where[] = (count($this->where) > 0 ? ($operator . ' ') : 'WHERE ' ) . $condition;
        }
        return $this;
    }

    /**
     * Append a WHERE condition. 
     * @param string $condition condition
     * @return FilteringQuery query
     */
    public function orWhere($condition) {
        return $this->where($condition, 'OR');
    }

    /**
     * Append a WHERE condition. 
     * @param string $condition condition
     * @return FilteringQuery query
     */
    public function andWhere($condition) {
        return $this->where($condition, 'AND');
    }

    /**
     * Set the HAVING clause. 
     * @param array $conditions conditions
     * @return FilteringQuery query
     */
    public function having($conditions) {
        $this->having = !empty($conditions) ? ('HAVING ' . implode(',', $conditions)) : null;
        return $this;
    }

    /**
     * Set the LIMIT clause. 
     * @param int $offset offset
     * @param int $count count
     * @return FilteringQuery query
     */
    public function limit($offset, $count) {
        $this->limit = $offset !== null && $count !== null ? ('LIMIT ' . $count . ' OFFSET ' . $offset) : null;
        return $this;
    }

}
