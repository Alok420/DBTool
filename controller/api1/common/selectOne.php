<?php
session_start();
include '../../../../Config/ConnectionObjectOriented.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
if (isset($_REQUEST["id"])) {
    $query = "select * from " . $_REQUEST['tbname'] . " where id=" . $_REQUEST['id'];
    $result = $conn->query($query);
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i] = $row;
        $i++;
    }
    $string = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $string;
}