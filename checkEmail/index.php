<?php
require_once '../connect.php';
require_once '../header.php';

if(isset($_GET['tokenCheck']) && isset($_GET['username'])){
    $token = $_GET['tokenCheck'];
    $username = $_GET['username'];

    $sql = "SELECT * FROM $db_name.$table where token_check_email = '$token' and username = '$username'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sql = "UPDATE `$db_name`.`$table` SET `verified_email` = '1', `token_check_email` = '0' WHERE token_check_email = '$token' and username = '$username'";
        $result = mysqli_query($connect, $sql);

        $sql = "SELECT * FROM $db_name.$table where username = '$username' and verified_email = '1';";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $obj = [
                "success" => "email successfully verified",
            ];
            echo json_encode($obj, JSON_UNESCAPED_SLASHES);
        }else{
            $obj = [
                "error" => "a verification error occurred",
            ];
            echo json_encode($obj, JSON_UNESCAPED_SLASHES);
        }

    }else{
        $obj = [
            "error" => "invalid token",
        ];
        echo json_encode($obj, JSON_UNESCAPED_SLASHES);
    }
}
?>