<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';

$db = new DB($conn);
//$db->showInTable("user");
$info = $db->login($_POST["userid"], $_POST["password"], $_POST["tbname"]);
//var_dump($info);
if ($info["status_number"] == 1) {
    $_SESSION["conn"] = $conn;
    if ($info["role"] == "admin") {
        $db->sendTo("../admin/index.php");
    }if ($info["role"] == "employee") {
        $db->sendTo("../employee/index.php");
    }if ($info["role"] == "client") {
        $db->sendTo("../client/index.php");
    }if ($info["role"] == "superadmin") {
        $db->sendTo("../superadmin/index.php");
    }if ($info["role"] == "godown") {
        $db->sendTo("../godown/index.php");
    }
} else {
    $db->sendBack($_SERVER, "?" . http_build_query($info));
}