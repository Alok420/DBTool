<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#id").focus();
                $("#id").on("change paste keyup", function () {
                    var offer = $("#offer").val();
                    var discount_making_charges;
                    setTimeout(
                            function ()
                            {
                                var id = $("#id").val();
                                id = $.trim(id);

                                var tbname = "barcode";
                                if (id != "" || id != " ") {
                                    $.post("../controller/selectOne.php", {
                                        id: id,
                                        tbname: tbname
                                    }, function (result) {
                                        if (result.length > 2) {
                                            $.each($.parseJSON(result), function (idx, obj) {
                                                $("#name").val(obj.item);
                                                $("#gross").val(obj.gross_weight);
                                                $("#net").val(obj.net_weight);
                                                $("#purity").val(obj.purity);
                                                $("#rate").val(obj.rate);
                                                discount_making_charges = obj.making_charges;
                                                $("#mkch").val(discount_making_charges);
                                                if (offer != "" && offer != " " && offer != null) {
                                                    var discount = (offer / 100) * obj.making_charges;
                                                    $("#discount").val(discount);
                                                    discount_making_charges = obj.making_charges - discount;
                                                }
                                                var purityvalue;
                                                if (obj.purity == "Diamond & Stone") {
                                                    $("#cost").html("<i class='fa fa-rupee'></i> " + parseFloat(parseFloat(obj.mrp).toFixed(2)).toLocaleString() + "/-");
                                                } else {
                                                    if (obj.purity == "916 (22k)") {
                                                        purityvalue = 1;
                                                    } else if (obj.purity == "750 (18k)") {
                                                        purityvalue = 0.90;
                                                    } else if (obj.purity == "580 (14k)") {
                                                        purityvalue = 0.75;
                                                    }
                                                    var purity_ratemulti = parseFloat(purityvalue) * (parseFloat(obj.rate) / 10);

                                                    if (obj.gst == 0) {
                                                        $("#cost").html("<i class='fa fa-rupee'></i> " + parseFloat(parseFloat(parseFloat($("#net").val()) * (parseFloat(discount_making_charges) + purity_ratemulti)).toFixed(2)).toLocaleString() + "/-");
                                                    } else {
                                                        var gstval = parseFloat(parseFloat($("#net").val()) * (parseFloat(discount_making_charges) + purity_ratemulti)) * (obj.gst / 100);
                                                        $("#cost").html("<i class='fa fa-rupee'></i> " + parseFloat(parseFloat(parseFloat(parseFloat($("#net").val()) * (parseFloat(discount_making_charges) + purity_ratemulti)) + gstval).toFixed(2)).toLocaleString() + "/-");
                                                    }
                                                }
                                                $("#id").val("");
                                                $("#id").attr("placeholder", id);
                                                $("#id").focus();
                                            });
                                        } else {
                                            $("#id").val("");
                                            $("#id").attr("placeholder", "No data for id: " + id);
                                            $("#id").focus();
                                        }

                                    });
                                }
                            }, 2000);
                });
            });

            function clickTo(path) {
                window.location.href = "" + path;
            }
        </script>
        <style>
            *{
                font-family: arial;
                color: black;
            }
            .logobox{
                /*border: thin solid red;*/
            }
            .logobox img{
                position: relative;
                /*height:75%;*/
                width:  50%;
                top:-20px;
            }
            #cost{
                width: 100%;
                text-align: center;
                height: 200px;
                font-size: 13vw;

            }
            .rs{
                transform: scaleY(1.1);
                width: 12%;
                top: -50px;
                vertical-align: central;
                height: auto;
            }
            td{
                padding: 0px!important;
                margin: 0px!important;
            }
            .ratehead{
                font-size: 150%;
            }
            .container-fluid *{
                margin: 0px!important;
            }
            #gross,#net,#name{
                font-weight: bold;
                font-size: 18px;

            }
            .costcontainer{
                margin-top:70px;

                background-color: #000000;
                background-image: linear-gradient(315deg, #000000 0%, #414141 74%);
            }
            #cost{
                color: #FDE08D;
                height: 300px;
                margin:0;
                margin-top:10px;
                padding:5px;	font-family: 'Open Sans', sans-serif;
                background: #28292d;
                background: -webkit-linear-gradient(top, #8f6B29, #FDE08D, #DF9F28);
                background: linear-gradient(top, #8f6B29, #FDE08D, #DF9F28);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }


        </style>
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-bottom" style="color:white;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-content" aria-controls="nav-content" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="nav-content">   
                    <ul class="navbar-nav">
                        <?php
                        $userid = "";
                        if (isset($_SESSION["loginid"])) {
                            $userid = $_SESSION["loginid"];
                            echo '<li style="margin-left: 30px;" class="nav-item"><a href="../controller/logout.php" target="_top" style="color:white;"><div class="btn-square-solid " style="color:white;">Logout</div></a></li> &nbsp;';
                            echo ' <li style="margin-left: 30px;" class="nav-item"><a href="entry.php" target="_top"><div class="btn-square-solid " style="color:white;">Home</div></a></li>';
                        } else {
//                            $db->sendTo("../login.php");
                            echo ' <li style="margin-left: 30px;" class="nav-item active"><a href="../login.php" target="_top" style="color:white;"><div class="btn-square-solid " style="color:white;">Login</div></a></li>';
                        }
                        ?>
                        <li class="nav-item active" style="margin-left: 30px;"><a href="entry.php" target="_BLANCK" ><div class="btn-square-solid " style="color:white;">Entries</div></a></li>
                        <!--                    <li style="margin-left: 20px;">
                                                 <div class="dailyrate">
                        <?php
//                            $bc = $db->select("barcode");
//                            $bc2 = $bc->fetch_assoc();
//                            echo "<span class='ratehead'>Gold rate :" . $bc2["rate"] . "</span>";
//                            
                        ?>
                                                </div>
                                            </li>-->
                        <li>

                            <input style="margin-left: 20px;" id="offer" class="form-control" type="text" name="offer" placeholder="Offer %">

                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container-fluid" style="margin-top:70px;">
            <div class="row" >
                <div class="col-sm-4 logobox">
                    <img src="../img/prs.png" class="img-responsive">
                </div>
                <div class="col-sm-8">
                    <form method="POST" action="../controller/insert.php" enctype="multipart/form-data" >

                        <div class="form-group row">
                            <div class="col-sm-3">

                                <label>ID</label>
                                <input  class="form-control" type="text" name="id" id="id">
                            </div>
                            <div class="col-sm-3">

                                <label>Name</label>
                                <input readonly class="form-control" type="text" id="name" name="name">
                            </div>

                            <div class="col-sm-3">
                                <label>Gross Weight</label>
                                <input readonly id="gross" class="form-control" type="text" name="rate">

                            </div>
                            <div class="col-sm-3">
                                <label>Net Weight</label>
                                <input readonly id="net" class="form-control" type="text" name="net_weight">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">

                                <label for="pwd">Purity</label>
                                <input readonly id="purity" class="form-control" type="text" name="purity">
                            </div>
                            <div class="col-sm-3">

                                <label for="pwd">Rate</label>
                                <input readonly id="rate" class="form-control" type="text" name="gross_weight">

                            </div>
                            <div class="col-sm-3">
                                <label for="pwd">Making charges</label>
                                <input readonly id="mkch" class="form-control" type="text" name="mkch" value="0">
                            </div>

                            <div class="col-sm-3">
                                <label for="pwd">Discount</label>
                                <input readonly id="discount" class="form-control" type="text" name="mkch" value="0">
                            </div>
                        </div>

                    </form> 
                </div>
            </div>
        </div>
        <div style="background-color: #28292d;" class="costcontainer">
            <div id="cost">

            </div>
        </div>
    </body>
</html>
