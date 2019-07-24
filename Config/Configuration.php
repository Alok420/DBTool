<?php

include 'DB.php';
include 'ConnectionObjectOriented.php';

function tablesdata() {

    $tables = array(
        "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "contact" => "varchar:11:unique", "userid" => "varchar:50:unique NOT NULL", "email" => "varchar:50:unique NOT NULL", "password" => "text", "api_key" => "text", "role" => "varchar:50", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "blocked" => "int:1:default 0"));
    return $tables;
}

function tableRelation() {
    $rtable = array(
        "service_category" => "services:cascade:cascade",
       
    );
    return $rtable;
}

$db = new DB($conn);
ini_set('max_execution_time', 300);
$info = $db->loadTables(tablesdata(), "create");
//addIntasks(tablesdata());
//$info = $db->relateTable(tableRelation());
if (count($info) > 0) {
    for ($i = 0; $i < count($info); $i++) {
        echo $info[$i];
    }
}
$db->insert($_POST, "s");

//$db->update($_POST, "s",1);
//$db->delete(1, "s");
//$db->relateTable(tableRelation());
function addIntasks($tables) {
    global $db;
    global $conn;
    foreach ($tables as $column => $value) {
        $date = $db->getIndianDateTime();
        $sql = "insert into tasks (task_name,creation_date,status) values('$column','$date','0')";
        if ($conn->query($sql)) {
            echo 'addede';
        } else {
            echo 'not';
        }
    }
}
