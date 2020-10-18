
<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$loginid = $_SESSION["loginid"];
$role = $_SESSION["role"];
$loginallowed = $_REQUEST["loginallowed"];
if ($role == "superadmin" && $loginallowed == "1") {
    $sql = "update user set loginallowed=1 where role<>'superadmin'";
    $conn->query($sql);
} else if ($role == "superadmin" && $loginallowed == "0") {
    $sql = "update user set loginallowed=0 where role<>'superadmin'";
    $conn->query($sql);
}
$db->sendBack($_SERVER);
