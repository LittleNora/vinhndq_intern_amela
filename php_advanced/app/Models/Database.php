<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{

    private $connect;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'amela_bai_tap_ket_noi_voi_workbench';

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->connect = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);

            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->connect;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function query($sql)
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.

        $this->connect = null;
    }
}