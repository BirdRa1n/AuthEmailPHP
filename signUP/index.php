<?php
require_once '../connect.php';
require_once '../header.php';


function singUP($username, $password, $connect, $db_name, $email, $name, $table)
{
    $sql = "SELECT * FROM $db_name.$table WHERE username = '$username'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        $obj = [
            "error" => "user already exists"
        ];
        echo json_encode($obj, JSON_UNESCAPED_SLASHES);
    } else {
        //verificar se o email já está sendo utilizado
        $sql = "SELECT * FROM $db_name.$table WHERE email = '$email' and verified_email = '1'";
        $result = mysqli_query($connect, $sql);

        if (mysqli_num_rows($result) > 0) {
            $obj = [
                "error" => "the email is already being used"
            ];
            echo json_encode($obj, JSON_UNESCAPED_SLASHES);
        } else {
            $code_generate = rand();
            $radio = $code_generate + 23984636758934759;
            $create_value = "$code_generate $email $radio configset $username";
            $cripto = hash('sha256', $create_value);

            $sql = "INSERT INTO `$db_name`.`$table` (`username`, `name`, `email`, `password`, `verified_email`, `token_check_email`) VALUES ('$username', '$name', '$email', '$password', '0', '$cripto');";
            $result = mysqli_query($connect, $sql);

            $to = $email;
           $message = $_SERVER['SERVER_NAME']."/AuthMail/checkEmail/?tokenCheck=".$cripto."&username=".$username;

            $headers = 'From: AuthEmail@birdra1n.x10.bz' . "\r\n" .
                'Reply-To: AuthEmail@birdra1n.x10.bz' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);


            $obj = [
                "success" => "account created successfully",

            ];
            echo json_encode($obj, JSON_UNESCAPED_SLASHES);
        }
    }
}
if (isset($_GET['username']) && isset($_GET['password']) && isset($_GET['email']) && isset($_GET['name'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];
    $email = $_GET['email'];
    $name = $_GET['name'];

    singUP($username, $password, $connect, $db_name, $email, $name, $table);
} else {
    $requested = [];

    if (!isset($_GET['username'])) {
        array_push($requested, "username");
    }
    if (!isset($_GET['password'])) {
        array_push($requested, "password");
    }
    if (!isset($_GET['email'])) {
        array_push($requested, "email");
    }
    if (!isset($_GET['name'])) {
        array_push($requested, "name");
    }
    $obj = [
        "error" => "does not contain all required parameters",
        "missing_parameters" => $requested
    ];
    echo json_encode($obj, JSON_UNESCAPED_SLASHES);
}
