<?php

class Database
{

    /**
     * @var PDO
     */
    protected $db;

    /**
     * @var string
     */
    private $host = "localhost";
    /**
     * @var string
     */
    private $dbName = "dictionary_project";
    /**
     * @var string
     */
    private $username = "root";
    /**
     * @var string
     */
    private $password = "root";

    /**
     * Start database
     * connection
     */
    public function __construct()
    {
        try {
            $this->db = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8', $this->username, $this->password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

}