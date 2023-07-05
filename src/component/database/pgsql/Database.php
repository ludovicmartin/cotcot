<?php

namespace cotcot\component\database\pgsql;

/**
 * PostgreSQL database implementation.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class Database extends \cotcot\component\database\Database {

    /** @var ressource database handle */
    private $handle = null;

    public function isConnected() {
        return $this->handle !== null;
    }

    public function connect() {
        parent::connect();
        $connectionString = array();
        if ($this->params->ssl !== null) {
            $connectionString[] = ($this->params->ssl ? 'sslmode=require' : 'sslmode=disable');
        }
        if ($this->params->host !== null) {
            $connectionString[] = 'host=' . $this->params->host;
        }
        if ($this->params->port !== null) {
            $connectionString[] = 'port=' . $this->params->port;
        }
        if ($this->params->user !== null) {
            $connectionString[] = 'user=' . $this->params->user;
        }
        if ($this->params->password !== null) {
            $connectionString[] = 'password=' . $this->params->password;
        }
        if ($this->params->databaseName !== null) {
            $connectionString[] = 'dbname=' . $this->params->databaseName;
        }
        $handle = pg_connect(implode(' ', $connectionString));
        if ($handle !== false) {
            $this->handle = $handle;
            if ($this->params->encoding !== null) {
                pg_set_client_encoding($this->handle, $this->params->encoding);
            }
            return;
        }
        throw new \cotcot\component\exception\DatabaseException('unable to connect to database server');
    }

    public function disconnect() {
        parent::disconnect();
        pg_close($this->handle);
        $this->handle = null;
    }

    public function quote($string) {
        return $string !== null ? '\'' . pg_escape_string($this->handle, $string) . '\'' : 'NULL';
    }

    public function query($query) {
        $result = pg_query($this->handle, $query);
        if ($result !== false) {
            $resultSet = new ResultSet();
            $resultSet->result = $result;
            return $resultSet;
        }
        throw new \cotcot\component\exception\DatabaseException(pg_last_error($this->handle) . PHP_EOL . $query);
    }

    public function getNextId($tableName) {
        return $this->getNextSequenceValue($tableName . '_id_seq');
    }

    public function getNextSequenceValue($sequenceName) {
        return $this->select(array('NEXTVAL(:sn::REGCLASS)'))->bindValue(':sn', $sequenceName)->execute()->fetchScalar();
    }

}
