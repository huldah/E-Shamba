<?php
require_once("connect.php");
require 'form.php';

if(!isset($_SESSION))
{
    session_start();
}
/**
Create new connect instance
the connection can be used to connect to the database and docrud ops
*/
$con = new Connect();
//Set another variable to the connection variable
$connection = $con->getConn();
/**The database class uses mysql_pdo to perform crud ops
Using $connection, you have access to these crud operations
*/
$form = new Form();
$form   ->post('username')
        ->post('password', false, true);

$form->submit();
$data = $form->fetch();

$result = $connection->select('SELECT * from staff where username=:username and password=:password',$data);

if(count($result)>0){
    echo "success";
}else{
   echo "failed";
}




?>