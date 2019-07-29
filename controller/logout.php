
<?php

include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$connection = new connection();
$conn = $connection->connect("localhost", "root", "");
//$connection->attach_db($conn, "barcode");
$db = new DB($conn);
session_start();
session_destroy();
$db->sendTo("../index.php");
