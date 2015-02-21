<?php

namespace cotcot\component\database;

/**
 * Database connection params.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ConnectionParams {

    public $host = 'localhost';
    public $port = null;
    public $user = null;
    public $password = null;
    public $databaseName = null;
    public $encoding = 'utf-8';
    public $ssl = false;

}
