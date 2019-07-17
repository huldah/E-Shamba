<?php
require 'database.php';

class Connect{
    private $conn;
    
    public function __construct(){
        $this->conn = new Database("mysql", "localhost", "e~shamba", "root" , "");
    }
    
    public function getConn(){
        return $this->conn;
    }
}

//$db = mysqli_connect();

?>