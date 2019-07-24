<?php

session_start();
include '../../../../Config/ConnectionObjectOriented.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$info = $db->login($_POST["userid"], $_POST["password"], $_POST["tbname"]);
if ($info["status_number"] == 1) {
    $info["status"] = "success";
    echo json_encode($info);
} else {
    $info["status"]="failed";
    echo json_encode($info);
}
