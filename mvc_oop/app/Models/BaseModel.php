<?php

namespace App\Models;

use PDO;

class BaseModel
{

    protected $connect;

    protected string $host = "localhost";

    protected string $username = "root";

    protected string $password = "password";

    protected string $database = "intern_amela_mvc_oop";

    protected string $table;

    public function __construct()
    {
        try {
            $this->connect = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->connect->prepare($sql);

        $stmt->execute($params);

        return $stmt;
    }

}