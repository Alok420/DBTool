<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
$location = "../mg/post";
$tbname = $_REQUEST["tbname"];
if ($tbname == "proposal_detail") {
    $location = "../img/proposal_detail/";
} else if ($tbname == "company") {
    $location = "../img/company/";
}
if (isset($_REQUEST["id"])) {
    $recentinsertedid = $_REQUEST["id"];
}
unset($_REQUEST["tbname"]);
unset($_REQUEST["id"]);
$info = $db->update($_REQUEST, $tbname, $recentinsertedid);
if ($info[0] == 1) {
    if (count($_FILES) > 0) {
        $return = $db->fileUploadWithTable($_FILES, $tbname, $recentinsertedid, $location, "50m", "jpg,png");

        $return["status"] = "success";
        $return["message"] = "Data and image saved";
        $return["recentinsertedid"] = $recentinsertedid;
//        var_dump($return);
        $db->sendBack($_SERVER, "?" . http_build_query($return));
    } else {

        $info["status"] = "success";
        $info["message"] = "Data  updated";
//        var_dump($info);
        $db->sendBack($_SERVER, "?" . http_build_query($info));
    }
} else if ($info[0] == 0) {

    $info["status"] = "failed";
    $info["message"] = "Data not updated";
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
