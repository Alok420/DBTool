<?php

session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$location = "../img";

if (isset($_SESSION["loginid"])) {
    if (isset($_POST["api_key"]) && isset($_SESSION["loginid"])) {
        $user_api_key = $_POST["api_key"];
        $loginid = $_SESSION["loginid"];
        $users = $db->select("user", "*", array("id" => $loginid));
        $user = $users->fetch_assoc();
        $db_user_api_key = $user["api_key"];
        if ($db_user_api_key == $user_api_key) {
            
        } else {
            die("<h2>API key is invalid</h2>");
        }
    } else {
        die("<h2>Login first</h2>");
    }
    unset($_POST["api_key"]);
    $tbname = $_POST["tbname"];
    if ($tbname == "user") {
        $location = "../img/user/";
    } else if ($tbname == "services_category") {
        $location = "../img/service_category/";
    } else if ($tbname == "services") {
        $location = "../img/services/";
    }
    unset($_POST["tbname"]);
    $info = $db->insert($_POST, $tbname);
//var_dump($info);
// if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {

    if ($info[0] == 1) {
        if (count($_FILES) > 0) {
            $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,PNG,JFIF,jpg,png,jfif");
            $return = array();
            $return["status"] = "success";
            $return["message"] = "Data and image saved";
            $return["recentinsertedid"] = $_REQUEST["recentinsertedid"];
//        var_dump($return);
            $db->sendBack($_SERVER, "?" . http_build_query($return));
        } else {
            $info = array();
            $info["status"] = "success";
            $info["message"] = "Data  saved";
            $info["recentinsertedid"] = $_REQUEST["recentinsertedid"];

//        var_dump($info);
            $db->sendBack($_SERVER, "?" . http_build_query($info));
        }
    } else if ($info[0] == 0) {

        $info["status"] = "failed";
        $info["message"] = "Data not saved";
//    var_dump($info);
        $db->sendBack($_SERVER, "?" . http_build_query($info));
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
} else {
    echo 'Log in first';
}    