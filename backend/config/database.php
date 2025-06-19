<?php
class Database
{
    protected $dbh;
    protected $error;

    public function __construct()
    {
        $host = 'db';
        $user = 'root';
        $pass = 'root';
        $dbname = 'php-project';
        $port = '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ]);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            throw new Exception("Database Connection Failed: " . $this->error);
        }
    }
}
