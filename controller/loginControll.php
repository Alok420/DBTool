<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$connection = new connection();
$conn = $connection->connect("localhost", "root", "");
$connection->attach_db($conn, "barcode");
$db = new DB($conn);
$info = $db->login($_POST["userid"], $_POST["password"], "user");
//var_dump($info);
if ($info["status_number"] == 1) {
    if ($info["role"] == "admin") {
        $db->sendTo("../admin/index.php");
    } else if ($info["role"] == "user") {
        $db->sendTo("../user/index.php");
    }
} else {
    $db->sendBack($_SERVER, "?info=" . $info["status_message"]);
}