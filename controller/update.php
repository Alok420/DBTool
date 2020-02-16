<?php

session_start();
//error_reporting(0);

include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$location = "../mg/post";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];
if ($tbname == "user") {
    $location = "../img/user/";
} else if ($tbname == "products") {
    $location = "../img/products/";
} else if ($tbname == "client_info") {
    $location = "../img/clients/";
}
if (isset($_POST["id"])) {
    $recentinsertedid = $_POST["id"];
}
unset($_POST["tbname"]);
unset($_POST["id"]);
$info = $db->update($_POST, $tbname, $recentinsertedid);
if ($info[0] == 1) {
    if (count($_FILES) > 0) {
        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "JPG,PNG,JFIF,jpg,png,jfif");
        $return["status"] = "success";
        $return["message"] = "Data and image saved";
        $return["recentinsertedid"] = $recentinsertedid;
        $db->sendBack($_SERVER, "?" . http_build_query($return));
    } else {
        $info = array();
        $info["status"] = "success";
        $info["message"] = "Data  saved";
        $info["recentinsertedid"] = $recentinsertedid;
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
