<? php
require_once("connect.php");
require 'form.php';

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
$form   ->post('surname')
        ->post("othername")
        ->post("email")
        ->post("mobile")
        ->post("location")
    
        ->post('password', false, true)
        ->post

$form->submit();
$data = $form->fetch();

$result = $connection->insert('staff',$data);

if($result){
    echo "success";
}else{
   echo "failed";
}











?>