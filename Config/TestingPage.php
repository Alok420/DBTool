<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include './ConnectionObjectOriented.php';
        include './DB.php';
        $db=new DB($conn);
        $where = array("user_name" =>"alok","operatoroc"=>"and","status" =>1,"operatoroc"=>"and","bapproval"=>1,"operatoroc"=>"and","mbapproval"=>1);
        $user = $db->select("login_credentials", "*", $where);
        var_dump($user);
        $one = $user->fetch_assoc();
        echo $one["user_name"];
        ?>
    </body>
</html>
