<?php

namespace cotcot\component\database\pgsql;

/**
 * Database result set.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ResultSet extends \cotcot\component\database\ResultSet {

    public function getAffectedRows() {
        return pg_affected_rows($this->result);
    }

    public function close() {
        pg_free_result($this->result);
    }

    public function fetch() {
        $row = pg_fetch_assoc($this->result);
        return $row !== false ? $row : null;
    }

    public function fetchAll() {
        $rowCount = pg_num_rows($this->result);
        $rows = pg_fetch_all($this->result);
        $this->close();
        return $rowCount === 0 ? array() : ($rows !== false ? $rows : null);
    }

    public function fetchCol($column = 0) {
        $col = pg_fetch_all_columns($this->result, $column);
        $this->close();
        return $col !== false ? $col : null;
    }

    public function fetchRow() {
        $row = $this->fetch();
        $this->close();
        return $row !== false ? $row : null;
    }

    public function fetchScalar() {
        $row = pg_fetch_row($this->result);
        $this->close();
        return $row !== false && count($row) ? $row[0] : null;
    }

    public function getLastInsertId() {
        throw new Exception('not available');
    }

}
