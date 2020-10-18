<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$email_mobile = $_REQUEST["email_mobile"];
$names = explode("@", $_REQUEST["name"]);
$name = $names[0];
$ids = $_SESSION["ids"];
$type = "email";
$info = $db->insert(array("name" => $name, "email" => $email_mobile, "userid" => $email_mobile, "role" => "user"), "user");
$userid = $_SESSION["recentinsertedid"];
$info2 = $db->insert(array("user_id" => $userid, "service_category_id" => $_REQUEST["service_category_id"], "services_id" => $_REQUEST["service_id"], "location_id" => $_REQUEST["location_id"],"status"=>"pending","remarks"=>" "), "user_order");
if ($info[0] == 1) {
    $allid = explode("_", $ids);
    for ($i = 0; $i < count($allid); $i++) {
        if ($allid[$i] != "") {
            $sql = "update user_select_fields set user_id='" . $userid . "' where id='" . $allid[$i] . "'";
//            echo $sql;
            $info = $conn->query($sql);
        }
    }
    $_SESSION["ids"] = "null";
    $db->sendTo("../thankyou.php", "final_step=fininshed");
} else {
    var_dump($info);
}