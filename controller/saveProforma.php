<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
define('UPLOAD_DIR', '../img/proformas/');  
      $img = $_POST['data'];  
      $id=explode("?",$_POST['id']);
      $id=$id[0];
      $img = str_replace('data:image/jpeg;base64,', '', $img);  
      $img = str_replace(' ', '+', $img);  
      $data = base64_decode($img);
      $imgname="proforma".rand().$id. '.jpeg';  
      $file = UPLOAD_DIR.$imgname;  
      $success = file_put_contents($file, $data);  
   
     $db->update(array("proforma_image"=>$imgname),"proforma",$id);
     echo $imgname;
?>  