<?php

namespace LMS;

class Database
{
    private $connection;

    public function __construct($host, $username, $password, $database)
    {
        $this->connection = new \mysqli($host, $username, $password, $database);
        if ($this->connection->connect_error) {
            throw new \Exception("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }

    public function close()
    {
        $this->connection->close();
    }
}