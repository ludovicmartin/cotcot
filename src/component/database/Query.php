<?php

namespace cotcot\component\database;

/**
 * Basic query.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Query {

    /** @var Database database */
    public $database = null;

    /** @var array binded values */
    protected $bindedValues = array();

    /**
     * Execute the query.
     * @return ResultSet result
     */
    public function execute() {
        return $this->database->query($this->toSql());
    }

    /**
     * Execute the query and close immediately the result.
     */
    public function executeAndClose() {
        $this->database->query($this->toSql())->close();
    }

    /**
     * Test if the query is safe.
     * If more that one query is found, it's because someone tryed to inject
     * something. So, a safe query is a single query. 
     * @return boolean safe status
     */
    public function isSafe() {
        return $this->database->isPartialQuerySafe($this->toSql());
    }

    /**
     * Bind a value.
     * @param string $key key
     * @param mixed $value value
     * @return Query
     */
    public function bindValue($key, $value) {
        $this->bindedValues[$key] = $value;
        return $this;
    }

    /**
     * Bind some values.
     * @param array $values values
     * @param boolean $replace replace all binded values flag
     * @return Query
     */
    public function bindValues($values, $replace = true) {
        $this->bindedValues = $replace ? $values : array_merge($this->bindedValues, $values);
        return $this;
    }

    /**
     * Replace binding key by binded values.
     * @param string $string SQL query
     * @return string SQL query
     */
    protected function bindValuesToString($string) {
        $database = $this->database;
        $bindedValues = $this->bindedValues;
        return preg_replace_callback('/:[a-z0-9_]+/i', function($matches) use ($bindedValues, $database) {
            $key = $matches[0];
            if (array_key_exists($key, $bindedValues)) {
                $value = $bindedValues[$key];
                return is_array($value) ? implode(',', $database->arrayQuote($value)) : $database->quote($value);
            }
            return $key;
        }, $string);
    }

    /**
     * Transform this query to an SQL query string.
     * @return string SQL query string
     */
    public abstract function toSql();
}
