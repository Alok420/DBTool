<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$info = "No fields are selected";
foreach ($_GET as $key => $value) {
    if (strpos($key, "step_info_id") !== false || strpos($key, "step_info_count") !== false || strpos($key, "service_category_id") !== false || strpos($key, "service_id") !== false || strpos($key, "location_id") !== false) {
//        echo $key;
    } else {
        $info = $db->insert(array("ordernow_process_fields_id" => $value), "user_select_fields");
        $inserted_id = $conn->insert_id;
        if (!isset($_SESSION["ids"]) || $_SESSION["ids"] == "" || ($_SESSION["ids"] == "null" || $_SESSION["ids"] == null)) {
            $_SESSION["ids"] = $inserted_id . "_";
        } else {
            $_SESSION["ids"] = $_SESSION["ids"] . $inserted_id . "_";
        }
    }
}
if ($info[0] == 1) {
    $db->sendTo("../ordernow.php", "step_info_id=" . $_GET["step_info_id"] . "&step_info_count=" . $_GET["step_info_count"] . "&service_category_id=" . $_GET["service_category_id"] . "&service_id=" . $_GET["service_id"] . "&location_id=" . $_GET["location_id"]);
} else {
    var_dump($info);
}