<?php
//$dbname="justpkpj_bitsinfotec";
//$username="justpkpj_bits";
//$password="6Sht=iq4,RXo";
   $dbname="ngo";
   $username="root";
   $password="";
$conn = new mysqli('localhost',$username, $password, $dbname);
if ($conn->connect_error){
    die("not connected") . $connect_error;
}
?>