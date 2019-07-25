<?php
include './Config/DB.php';
include './Config/ConnectionObjectOriented.php';
include './Config/Configuration.php';
$connection=new connection();
$conn=$connection->build("abcd","root", "", "create");
$config=new Configuration($conn);
$config->configure("creation", "create");