<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
include '../Config/Configuration.php';
//$connection = new connection();
//$conn = $connection->build($db->userIdWithRange($_POST["name"], 0, 10000), "root", "", "create");
$configure = new Configuration($conn);
$info=$configure->configure("creation", "create");
var_dump($info);