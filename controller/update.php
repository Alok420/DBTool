<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$location = "../../../../iraw/mg/post";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];
if ($tbname == "proposal_detail") {
    $location = "../img/proposal_detail/";
} else if ($tbname == "company") {
    $location = "../img/company/";
}
if (isset($_POST["id"])) {
    $recentinsertedid = $_POST["id"];
}
unset($_POST["tbname"]);
unset($_POST["id"]);
$info = $db->update($_POST, $tbname,$recentinsertedid);
if ($info[0] == 1) {
    if (count($_FILES) > 0) {
        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "jpg,png");
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
