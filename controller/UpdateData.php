<?php

include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$connection = new connection();
$conn = $connection->connect("localhost", "root", "");
$connection->attach_db($conn, "barcode");
$db = new DB($conn);
if (isset($_REQUEST["id"])) {
    $info1 = "";
    if (isset($_REQUEST["info"])) {
        $info1 = $_REQUEST["info"];
    }
    unset($_REQUEST["info"]);
    $tablename = $_REQUEST["tbname"];
    $id = $_REQUEST["id"];
    unset($_REQUEST["id"]);
    unset($_REQUEST["tbname"]);
    $info = $db->update($_REQUEST, $tablename, $id);
    if ($info[0] == 1) {
        $db->sendBack($_SERVER, $info1);
    } else {
        echo "Not updated";
//        echo $info[1];
//        echo $info[2];
//        echo $info[3];
    }
}