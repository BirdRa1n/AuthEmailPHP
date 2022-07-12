<?php
$servername = "localhost:3306";
$username = "";
$password = "";
$db_name = "authEmailPHP";
$table = "users";

$connect = mysqli_connect($servername, $username, $password, $db_name);
if(mysqli_connect_error()):
    $obj = array("DB_connection_error" => utf8_encode(mysqli_connect_error()));
    echo json_encode($obj, JSON_UNESCAPED_SLASHES);
endif;
?>