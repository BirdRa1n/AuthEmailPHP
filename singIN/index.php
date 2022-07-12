<?php
require_once '../connect.php';
require_once '../header.php';

function singIN($username, $password, $connect, $db_name, $table)
{
    //verificar se o usuário existe
    $sql = "SELECT * FROM $db_name.$table WHERE username = '$username'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sql = "SELECT * FROM $db_name.$table WHERE username = '$username' and password = '$password'";
        $result = mysqli_query($connect, $sql);


        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_array($result);
            if ($data['verified_email'] == "1") {
                $obj = [
                    "login_state" => "success",
                    "data_user" => [
                        "name" => $data['name'],
                        "email" => $data['email']
                    ]
                ];
                echo json_encode($obj, JSON_UNESCAPED_SLASHES);
            } else {
                $obj = [
                    "error" => "email not verified"
                ];
                echo json_encode($obj, JSON_UNESCAPED_SLASHES);
            }
        } else {
            $obj = [
                "error" => "incorrect password"
            ];
            echo json_encode($obj, JSON_UNESCAPED_SLASHES);
        }
    } else {

        $obj = [
            "error" => "user does not exist"
        ];
        echo json_encode($obj, JSON_UNESCAPED_SLASHES);
    }
}

if (isset($_GET['username']) && isset($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    singIN($username, $password, $connect, $db_name, $table);
} else {
    $requested = [];

    if (!isset($_GET['username'])) {
        array_push($requested, "username");
    }
    if (!isset($_GET['password'])) {
        array_push($requested, "password");
    }
    $obj = [
        "error" => "does not contain all required parameters",
        "missing_parameters" => $requested
    ];
    echo json_encode($obj, JSON_UNESCAPED_SLASHES);
}
