
<?php
session_start();
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
include './barcode.php';
$db = new DB($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            var padding = 0;
            $(function () {
                $('.print').click(function () {
//                    var pageTitle = 'Page Title',
//                            stylesheet = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
//                            win = window.open('', 'Print', 'width="100%",height="100%"');
//                    win.document.write('<html><head><title>' + pageTitle + '</title>' +
//                            '<link rel="stylesheet" href="' + stylesheet + '">' +
//                            '<style>.tag *{font-size: 8px;text-align: center;vertical-align: central;}</style></head><body>' + $('#table')[0].outerHTML + '</body></html>');
//                    win.document.close();
//                    win.print();
//                    win.close();
//                    return false;
                    printDiv();
                });
            });
            function printDiv() {
                var divName = "outerdiv";

                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
            $(document).ready(function () {
                $("#space").on("change paste keyup", function () {
                    var padding = ($("#space").val()) * ($(".tag").height());
                    $("#table").css(
                            {"top": padding + "px"}
                    );
                });
                $("#leftspace").on("change paste keyup", function () {
                    var padding = ($("#leftspace").val()) * 10;
                    $("#table").css(
                            {"left": padding + "px"}
                    );
                });
            });

        </script>
        <style>
            .tag *{
                font-size: 8px;
                text-align: center;
                padding-left: 5px;
                line-height: 8px!important;
                vertical-align: middle;
            }
            .col-sm-12{
                /*line-height: 12px;*/
            }
            .tag{
                width: 248px!important;
                margin-top: 8px; 
                height: 40px;
                /*border:thin solid black;*/
                padding-top: 1px;
            }
            #right{
                left:30px;
            }
            .row{
                margin-left: 0px;
                margin-right: -31px;
            }

            .detail{
                font-weight: bold;
            }
            @page {
                size: auto; 
                width: 137mm;
                margin: 0mm; 
                height: 168mm;
                page-break-after:always;

            }

            @media print {
                @page {
                    size: auto; 
                    margin: 0mm; 
                    width: 137mm;
                    height: 168mm;
                    page-break-after:always;

                }
                .tag *{
                    font-size: 8px;
                    text-align: center;
                    padding-left: 5px;

                }

                .tag{
                    width: 248px!important;
                    margin-top: 8px; 
                    height: 40px;
                    /*border:thin solid black;*/
                }
                #right{
                    left:30px;
                }
                .row{
                    margin-left: 0px;
                    margin-right: -31px;
                }
                .detail{
                    font-weight: bold;
                }
            }
            .navbar-container>.navbar li a{
                text-decoration: none;
                color: white!important;
            }
            .navbar-container>.navbar .navbar-nav li{
                margin-left: 30px;
                font-weight: 300;
                transition: .5s;
                padding-bottom: 2px;
                min-height: 30px;

            }
          
        </style>
    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container" style="margin-top: 50px;">
            <form action="tags.php" method="GET">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>
                                Tag creation range 
                            </label>
                            <input type="text" class="form-control" name="creationstart" placeholder="Start number to fetch tag">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label>
                            &nbsp;
                        </label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="creationend" placeholder="End number to fetch tag">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label>
                            ID Range
                        </label>
                        <div class="form-group">
                            <input value="0" type="text" class="form-control" name="id_range" placeholder="1,2,3,4">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <label>Columns</label>
                        <input type="text" class="form-control" placeholder="columns" name="cols">
                    </div>
                    <div class="col-sm-2">
                        <label>Top</label>
                        <input type="number" class="form-control" id="space">

                    </div>
                    <div class="col-sm-2">
                        <label>Left</label>
                        <input type="number" class="form-control" id="leftspace">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-success btn-sm" type="submit">Filter</button>
                        <button class="btn btn-success btn-sm print" type="button">Print bar-code</button>
                    </div>
                </div>
            </form>
            <br>
        </div>

        <div id="outerdiv" class="row" style="width: 510px;">
            <div id="table" class="col-sm-12" style="padding-top:10px; width: 510px; position: absolute;z-index: -1;left:0; right:0;margin-left:auto;margin-right:auto;">
                <div style="display: inline;">
                    <table style="float: left;" id="left">
                    </table>
                    <table style="margin: 0px; position: relative; top: 24px;" id="right" >
                    </table>
                </div>
                <br>
            </div> 
        </div>
        <?php
        $starttag = isset($_REQUEST["creationstart"]) ? $_REQUEST["creationstart"] : "0";
        $endtag = isset($_REQUEST["creationend"]) ? $_REQUEST["creationend"] : "100";

        $starttag = empty($starttag) ? "0" : $starttag;
        $endtag = empty($endtag) ? "0" : $endtag;

        $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : "10";
        $cols = isset($_REQUEST["cols"]) ? $_REQUEST["cols"] : "3";
        if (isset($_GET["id_range"])) {
            $sql = "select * from barcode where id between $starttag and $endtag or id in (" . $_GET["id_range"] . ")";
        } else {
            $sql = "select * from barcode where id between $starttag and $endtag ";
        }

        $barcodes = $conn->query($sql);
        $flag = 0;
        if ($barcodes->num_rows > 0) {

            while ($barcode = $barcodes->fetch_assoc()) {
                barcode("barcodeimage/" . $barcode["id"] . ".png", $barcode["id"], $size, $orientation, $code_type, $print, $sizefactor);
                $db->update(array("printing_date" => $db->getIndianDateTime()), "barcode", $barcode["id"]);
                if ($barcode["purity"] == "Diamond & Stone") {
                    if ($flag == 0) {
                        $flag = 1;
                        ?>
                        <script>
                            var tag = '<tr><td><div class="row tag " style="width: 100%;"><div class="col-sm-2"><div class="row"><div class="col-sm-12" style="text-align:left;"><?php echo $barcode["id"]; ?></div></div><div class="row"><div class="col-sm-12" style="text-align:left;margin-left:0px;margin-left:-15px;"><img src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20" width="60">                                                </div>                                            </div>                                        </div>                                        <div class="col-sm-2">                                            <div class="row">                                                <div class="col-sm-12">                                                    <img src="../img/logo.JPG" class="logo" height="25" width="55" class="img-responsive" style="margin-top:3px;">                                                </div>                                            </div>                                        </div>                                        <div class="col-sm-1"></div><div class="col-sm-7 detail"><div class="row" ><div class="col-sm-12"><span><?php echo $barcode["item"]; ?></span><span>&nbsp;<?php echo "750"; ?></span></div></div><div class="row"><div class="col-sm-12"><span><?php echo "Dia/Stone"; ?></span><span>&nbsp;NWT:<?php echo $barcode["net_weight"]; ?></span></div></div><div class="row"><div class="col-sm-12"><span>CT:<?php echo $barcode["caret"]; ?></span><span>MRP:<?php echo $barcode["mrp"]; ?></span></div></div></div></tr></td>';
                            document.getElementById("left").innerHTML += tag;
                        </script> 
                        <?php
                    } else {
                        $flag = 0;
                        ?>
                        <script>
                            var tag = '<tr><td><div class="row tag"><div class="col-sm-6 detail" style="margin-left:-12px;"><div class="row"  ><div class="col-sm-12"><span><?php echo $barcode["item"]; ?></span><span>&nbsp;<?php echo "750"; ?></span></div></div><div class="row"><div class="col-sm-12"><span><?php echo "Dia/stone"; ?></span>&nbsp;<span>NWT:<?php echo $barcode["net_weight"]; ?></span></div></div><div class="row"><div class="col-sm-12"><span>CT:<?php echo $barcode["caret"]; ?></span><span>MRP:<?php echo $barcode["mrp"]; ?></span></div></div></div><div class="col-sm-4" style="margin-left:-15px;"><div class="row"> <div class="col-sm-12"><img src="../img/logo.JPG" class="logo" height="25" width="55" class="img-responsive" style="margin-top:3px;"></div></div></div><div class="col-sm-2" style="margin-left:-3px;"><div class="row" style="width:100%; margin:0px;"><div class="col-sm-12" style="text-align:left;"><?php echo $barcode["id"]; ?></div>                                            </div>                                            <div class="row">                                                <div class="col-sm-12" style="text-align:left;margin-left:0px;margin-left:-15px;">                                                    <img  src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20" width="60">                                                </div>                                            </div>                                        </div>                                        </div></tr></td>';
                            document.getElementById("right").innerHTML += tag;
                        </script> 
                        <?php
                    }
                } else {

                    if ($flag == 0) {
                        $flag = 1;
                        ?>
                        <script>
                            var tag = '<tr><td><div class="row tag " style="width: 100%;"><div class="col-sm-2"><div class="row"><div class="col-sm-12" style="text-align:left;"> <?php echo $barcode["id"]; ?></div></div><div class="row"><div class="col-sm-12" style="text-align:left;margin-left:0px;margin-left:-15px;"><img src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20" width="60"></div></div></div><div class="col-sm-2"><div class="row"><div class="col-sm-12"><img src="../img/logo.JPG" class="logo" height="25" width="55" class="img-responsive" style="margin-top:3px;"></div></div></div>                                        <div class="col-sm-1"></div><div class="col-sm-7 detail"><div class="row" ><div class="col-sm-12"><span><?php echo $barcode["item"]; ?></span><span>&nbsp;<?php echo substr($barcode["purity"], 0, strlen($barcode["purity"]) - (stripos($barcode["purity"], "(", 0) + 1)) ?></span></div></div><div class="row"><div class="col-sm-12"><span>GWT:<?php echo $barcode["gross_weight"]; ?></span>&nbsp;<span>NWT:<?php echo $barcode["net_weight"]; ?></span></div></div><div class="row"><div class="col-sm-12"><span>MC:<?php echo $barcode["making_charges"]; ?></span></div></div></div></tr></td>';
                            document.getElementById("left").innerHTML += tag;
                        </script> 
                        <?php
                    } else {
                        $flag = 0;
                        ?>
                        <script>
                            var tag = '<tr><td><div class="row tag"><div class="col-sm-6 detail" style="margin-left:-12px;"><div class="row"  ><div class="col-sm-12"><span><?php echo $barcode["item"]; ?></span><span>&nbsp;<?php echo substr($barcode["purity"], 0, strlen($barcode["purity"]) - (stripos($barcode["purity"], "(", 0) + 1)) ?></span></div></div><div class="row"><div class="col-sm-12"><span>GWT:<?php echo $barcode["gross_weight"]; ?></span>&nbsp;<span>NWT:<?php echo $barcode["net_weight"]; ?></span></div></div><div class="row"><div class="col-sm-12"><span>MC:<?php echo $barcode["making_charges"]; ?></span></div></div></div><div class="col-sm-4" style="margin-left:-10px;"><div class="row"> <div class="col-sm-12"><img src="../img/logo.JPG" class="logo" height="25" width="55" class="img-responsive" style="margin-top:3px;"></div></div></div><div class="col-sm-2" style="margin-left:-3px;"><div class="row" style="width:100%; margin:0px;"><div class="col-sm-12" style="text-align:left;"><?php echo $barcode["id"]; ?></div>                                            </div>                                            <div class="row">                                                <div class="col-sm-12" style="text-align:left;margin-left:0px;margin-left:-20px;"><img  src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20" width="60"></div></div></div></div></tr></td>';
                            document.getElementById("right").innerHTML += tag;
                        </script> 
                        <?php
                    }
                }
            }
        }
        ?>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    </body>
</html>
