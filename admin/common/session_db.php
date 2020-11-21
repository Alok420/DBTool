<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
if (!isset($_SESSION["loginid"]) || !isset($_SESSION["role"])) {
    echo '<script>window.location.href="../index.php?";</script>';
}
$userid = $_SESSION["loginid"];
$role = $_SESSION["role"];
if ($role != "tech_admin") {
    echo '<script>window.location.href="../login.php?";</script>';
}
$users = $db->select("user", "*", array("id" => $userid));
if ($users->num_rows > 0) {
    $user = $users->fetch_assoc();
}
?>