<?php
class Database {

    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'prog_05';

    public function connect() {
        $connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($connection->connect_error) {
            die('Connection failed: ' . $connection->connect_error);
        }

        return $connection;
    }
}

?>