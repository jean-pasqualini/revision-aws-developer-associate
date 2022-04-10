<?php

namespace Darkilliant\Database;

class Mysql
{
    private \PDO $pdo;

    public function __construct()
    {
        $dbhost = $_SERVER['RDS_HOSTNAME'];
        $dbport = $_SERVER['RDS_PORT'];
        $dbname = $_SERVER['RDS_DB_NAME'];
        $charset = 'utf8' ;

        $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";
        $username = $_SERVER['RDS_USERNAME'];
        $password = $_SERVER['RDS_PASSWORD'];

        $this->pdo = new \PDO($dsn, $username, $password);
    }

    public function createSchema()
    {
        $this->pdo->query(<<<EOF
            CREATE TABLE `character` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255),
                `color` VARCHAR(255),
                `power` VARCHAR(255),
                `life` INT,
                PRIMARY KEY (`id`)
            );
            EOF
        );

    }

    public function showTables()
    {
        $query = $this->pdo->query("SHOW TABLES");
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }
}