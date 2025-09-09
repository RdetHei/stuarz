<?php

class users{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }

    function login(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE username='".$this->username."' AND password='".$this->password."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}


?>