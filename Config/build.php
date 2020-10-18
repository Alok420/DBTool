<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
include '../Config/Configuration.php';
$db = new DB($conn);
//$connection = new connection();
//$conn = $connection->build($db->userIdWithRange($_POST["name"], 0, 10000), "root", "", "create");
$configure = new Configuration($conn);
$type = isset($_GET["type"]) ? $_GET["type"] : "creation";
$info2 = $configure->configure($type, isset($_GET["operation"]) ? $_GET["operation"] : "create");
if (isset($_GET["type"]) && $_GET["type"] == "creation") {
  echo 'Selected database is : ' . $db->getSelectedDB();
  echo "<br>Table found in database : " . count($db->getAllTableNameFromDB());
  echo "<br>Configured table : " . count($info2[1]);
} else if (isset($_GET["type"]) && $_GET["type"] == "relation") {
  echo 'Selected database is : ' . $db->getSelectedDB();
  echo "<br>Relation found in database : " . $db->getRelation()->num_rows;
  echo "<br>Configured relation : " . count($info2[1]);
}

$info = $info2[0];
for ($i = 0; $i < count($info); $i++) {
  if ($info[$i] == "0")
    echo '<div style="color:red;">';
  else if ($info[$i] == "1")
    echo '<div style="color:black;">';
  echo $i . " : " . $info[$i] . "<br>";
}

