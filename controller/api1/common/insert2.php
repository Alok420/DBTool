<?php

session_start();
include '../../../Config/ConnectionObjectOriented.php';
include '../../../Config/DB.php';
$db = new DB($conn);
$location = "../../../../iraw/mg/post";

// if (isset($_POST["api_key"])) {

$tbname = $_POST["tbname"];
if ($tbname == "proposal") {
    $_POST["proposal_key"] = $db->apiKey("proposal");
} else if ($tbname == "challan_copy") {
    $location = "../../../../raw/challan_copy";
}
unset($_POST["tbname"]);
$info = $db->insert($_POST, $tbname);
// if ($db->apichecker($_POST["api_key"], $_POST["user_id"], "user")) {
if (isset($_SESSION["recentinsertedid"])) {
    $recentinsertedid = $_SESSION["recentinsertedid"];
}
if ($info[0] == 1) {
    if (count($_FILES) > 0) {

        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "jpg,png");
        $return = array();
        $return["status"] = "success";
        $return["message"] = "Data and image saved";
        $return["recentinsertedid"] = $_SESSION["recentinsertedid"];
        echo json_encode($return);
    } else {
        $info = array();
        $info["status"] = "success";
        $info["message"] = "Data  saved";
        $info["recentinsertedid"] = $_SESSION["recentinsertedid"] or 0;
        echo json_encode($info);
    }
} else if ($info[0] == 0) {

    $info["status"] = "failed";
    $info["message"] = "Data not saved";
    echo json_encode($info);
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
