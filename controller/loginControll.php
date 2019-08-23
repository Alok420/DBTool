<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$co = new connection();
$host = isset($_POST["host"])?$_POST["host"]:"localhost";
$dbusername = isset($_POST["dbusername"])?$_POST["dbusername"]:"root";;
$pass = isset($_POST["dbpassword"])?$_POST["dbpassword"]:"";
$dbname = isset($_POST["dbname"])?$_POST["dbname"]:"bitsinfotec";;
$conn = $co->connect($host, $dbusername, $pass);
$co->attach_db($conn, $dbname);
$db = new DB($conn);
$info = $db->login($_POST["userid"], $_POST["password"], $_POST["tbname"]);
//var_dump($info);
if ($info["status_number"] == 1) {
    if ($info["role"] == "admin") {
        $db->sendTo("../admin/index.php");
    } else if ($info["role"] == "user") {
        $db->sendTo("../user/index.php");
    }
} else {
    $db->sendBack($_SERVER, "?" . http_build_query($info));
}