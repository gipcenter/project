<?php

namespace database;

use PDO;
use PDOException;

class DataBase
{

    private $connection;
    private $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    private $dbHost = DB_HOST;
    private $dbUserName = DB_USERNAME;
    private $dbName = DB_NAME;
    private $dbPassword = DB_PASSWORD;


    function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUserName, $this->dbPassword, $this->option);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }


    // select('SELECT * FROM users');
    // select('SELECT * FROM users WHERE id = ?', [2]);
    public function select($sql, $value = null)
    {
        try {

            $stmt = $this->connection->prepare($sql);
            if ($value == null) {
                $stmt->execute();
            } else {
                $stmt->execute($value);
            }
            $result = $stmt;
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    // insert('users', ['username', 'email', 'password'], ['iman', 'gipcenter3@gmail.com', '1234']);
    public function insert($tableName, $filds, $value)
    {

        try {

            //$stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt = $this->connection->prepare("INSERT INTO " . $tableName . " (" . implode(', ', $filds) . ", created_at) VALUE ( :" . implode(', :', $filds) . ", now() );");
            //
            $stmt->execute(array_combine($filds, $value));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // update('users', 2, ['username', 'password'], ['alik2', 12345]);
    public function update($tableName, $id, $fields, $values)
    {

        $sql = "UPDATE " . $tableName . " SET";
        foreach (array_combine($fields, $values) as $field => $value) {
            if ($value) {
                $sql .= " `" . $field . "` = ? ,";
            } else {
                $sql .= " `" . $field . "` = NULL ,";
            }
        }

        $sql .= " updated_at = now()";
        $sql .= " WHERE id = ?";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_merge(array_filter(array_values($values)), [$id]));
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    // delete('users', 2);
    public function delete($tableName, $id)
    {
        $sql = "DELETE FROM " . $tableName . " WHERE id = ? ;";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }



    public function createTable($sql)
    {
        try {
            $this->connection->exec($sql);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}