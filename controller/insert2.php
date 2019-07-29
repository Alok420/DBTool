<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$connection = new connection();
$conn = $connection->connect("localhost", "root", "");
$connection->attach_db($conn, "barcode");
$db = new DB($conn);
$location = "../img";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];

if ($tbname == "user") {
    $location = "../img/user/";
} else if ($tbname == "product") {
    $location = "../img/product/";
} else if ($tbname == "product_category") {
    $location = "../img/product_category/";
} else if ($tbname == "services") {
    $location = "../img/services/";
} else if ($tbname == "service_category") {
    $location = "../img/service_category/";
} else if ($tbname == "portfolio") {
    $location = "../img/portfolio/";
} else if ($tbname == "portfolio_category") {
    $location = "../img/portfolio_category/";
} else if ($tbname == "profile") {
    $location = "../img/profile/";
}
unset($_POST["tbname"]);
$info = $db->insert($_POST, $tbname);
//var_dump($info);
// if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {
if (isset($_SESSION["recentinsertedid"])) {
    $recentinsertedid = $_SESSION["recentinsertedid"];
}
if ($info[0] == 1) {
    if (count($_FILES) > 0) {
        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,PNG,JFIF,jpg,png,jfif");
        $return = array();
        $return["status"] = "success";
        $return["message"] = "Data and image saved";
        $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
//        var_dump($return);
        $db->sendBack($_SERVER, $_REQUEST["info"]);
    } else {
        $info = array();
        $info["status"] = "success";
        $info["message"] = "Data  saved";
        $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
//        var_dump($info);
        $db->sendBack($_SERVER, $_REQUEST["info"]);
    }
} else if ($info[0] == 0) {

    $info["status"] = "failed";
    $info["message"] = "Data not saved";
//    var_dump($info);
    $db->sendBack($_SERVER, "?info=Data not saved");
}
    
//     }
//     else {
//         $info["status"] = "failed";
//         $info["message"] = "Not valid user (API NOT MATCHED)";
//     }
// } else {
//     $info["status"] = "failed";
//     $info["message"] = "Not valid user (API NOT MATCHED)";
// }
