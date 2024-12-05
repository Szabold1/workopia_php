<?php

class Database
{
    public $connection;

    /**
     * Constructor for Database class
     * @param array $config
     */
    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}"; // Data Source Name

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: {$e->getMessage()}");
        }
    }

    /**
     * Query the database
     * @param string $sql
     * @return PDOStatement
     * @throws Exception
     */
    public function query($sql)
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: {$e->getMessage()}");
        }
    }
}
