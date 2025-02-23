<?php

namespace App\Commands;

use App\Database\Dsn;

class ConnectDatabase
{
    public function execute()
    {
        try {
            $dsn = new Dsn();
            $db = new \PDO("pgsql:host={$dsn->getHost()};dbname={$dsn->getDbName()};port={$dsn->getPort()}", $dsn->getUser(), $dsn->getPassword());
            return $db;
        } catch (\PDOException $e) {
            echo $e->getMessage();

            $db = null;
            return false;
        }
    }
}
