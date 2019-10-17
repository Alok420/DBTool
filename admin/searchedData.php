<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $(".generateTopic").click(function () {
                    $('.topics').append("<input type='text' class='form-control topic'>");
                    $('.descriptions').append("<textarea class='form-control topic'></textarea>");
                });
            });

            function clickTo(path) {
                window.location.href = "" + path;
            }
        </script>
      <style>
            table{
                border: thin solid gray;

            }
            table, td, th{
                border-collapse: collapse;
            }
            .sidebar{
                border: thin solid gray;
                min-height: 100vh;
                border-radius: 10px;
            }
            .sidebar a{
                text-decoration: none;
                color: black;
                cursor: pointer;
            }
            .sidebar li{
                padding: 5px!important;
                margin: 0px!important;
                transition: .2s;
            }
            .sidebar li:hover{
                background-color:rgba(1,1,1,.7);
                color: white;
            }
            .main{
                border: thin solid gray;
                border-radius: 10px;

            }
        </style>
    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <h3 style="text-align: center;">Reg</h3>

            <div class="row">
                <div class="col-sm-3">
                    <?php include './sidebar.php'; ?>
                </div>
                <div class="col-sm-9" style="border: thin solid red;">
                    <div>
                        <div style="overflow-x: scroll;">
                            <a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>">Go Back</a>
                            <?php
                            if (isset($_GET["searchCol"])) {
                                $searchCol = $_GET["searchCol"];
                                $value = $_GET["searching_data"];
                                $tbname = $_GET["tbname"];
                                $db->showInTable($tbname, "*", array("$searchCol" => $value));
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
</html>