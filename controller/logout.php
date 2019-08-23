
<?php

include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
session_start();
session_destroy();
$db->sendTo("../index.php");
