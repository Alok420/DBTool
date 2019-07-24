<?php

include '../../../../Config/ConnectionObjectOriented.php';
include '../../../../Config/DB.php';
$db = new DB($conn);
$info = array();
if (isset($_REQUEST["id"]) && isset($_REQUEST["tbname"])) {
    $sql = "delete from " . $_REQUEST["tbname"] . " where id=" . $_REQUEST["id"];
    if ($conn->query($sql)) {
        $info["status"] = "success";
        $info["message"] = "Like removed";
        echo json_encode($info);
    } else {
        $info["status"] = "failed";
        $info["message"] = "Like not removed";
        echo json_encode($info);
    }
} else {
    $info["status"] = "failed";
    $info["message"] = "user id or post id is not set";
    echo json_encode($info);
}
