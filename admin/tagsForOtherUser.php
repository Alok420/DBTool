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
        <title>Bootstrap Example</title>
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
                $("#space").focus();
                $("#space").on("change paste keyup", function () {
                    var padding = $("#space").val();
                    $("#table").css(
                            {"padding-top": padding + "px"}
                    );
                });
            });

        </script>
        <style>

            .tag *{
                font-size: 9px;
                text-align: center;
                vertical-align: central;
            }
            .col-sm-12,.col-sm-4,.col-sm-6{
                padding-left: 13px!important;
            }
            .logo {
                position: relative;
                top: 6px;
            }
            .tag{
                border:thin solid black;
            }
        </style>
    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container">
            <form action="tags.php" method="GET">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>
                                Tag creation range 
                            </label>
                            <input type="text" class="form-control" name="creationstart" placeholder="Start number to fetch tag">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>
                            &nbsp;
                        </label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="creationend" placeholder="End number to fetch tag">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <label>Number of columns</label>
                        <input type="text" class="form-control" placeholder="columns" name="cols">
                    </div>
                    <div class="col-sm-3">
                        <label>Add spaces from top</label>
                        <input type="number" class="form-control" id="space">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <br>
                        <button class="btn btn-block btn-success" type="submit">Filter</button>
                    </div>
                </div>
            </form>
            <br>

            <div id="outerdiv" style="border: thin solid black;">
                <div id="table">
                    <table align="center" id="table" style="border-radius: ">
                        <?php
                        $starttag = isset($_REQUEST["creationstart"]) ? $_REQUEST["creationstart"] : "0";
                        $endtag = isset($_REQUEST["creationend"]) ? $_REQUEST["creationend"] : "100";
                        $starttag = empty($starttag) ? "0" : $starttag;
                        $endtag = empty($endtag) ? "100" : $endtag;

                        $endtag = $endtag - $starttag;
                        $endtag++;
                        $rows = isset($_REQUEST["rows"]) ? $_REQUEST["rows"] : "10";
                        $cols = isset($_REQUEST["cols"]) ? $_REQUEST["cols"] : "3";
                        $sql = "select * from barcode limit $endtag OFFSET $starttag";

                        $barcodes = $conn->query($sql);
                        $row = true;
                        $col = 1;
                        if ($barcodes->num_rows > 0) {
                            while ($barcode = $barcodes->fetch_assoc()) {
                                barcode("barcodeimage/" . $barcode["id"] . ".png", $barcode["id"], $size, $orientation, $code_type, $print, $sizefactor);

                                if ($row == true) {
                                    $row = false;
                                    echo '<tr class="container"><td>';
                                    $col++;
                                    ?>
                                    <div class="row tag " style="width: 100%;">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?php echo $barcode["id"]; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img src="../img/logo.jpeg" class="logo" height="30" width="30">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    NWT:<?php echo $barcode["net_weight"]; ?>  GWT:<?php echo $barcode["gross_weight"]; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <span><?php echo $barcode["item"]; ?></span><span>&nbsp;MC:<?php echo $barcode["making_charges"]; ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
                                    echo '</td>';
                                } else {
                                    $col++;
                                    echo '<td>';
                                    ?>
                                    <div class="row tag second" style="width: 100%;">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?php echo $barcode["id"]; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img src="barcodeimage/<?php echo $barcode["id"]; ?>.png" height="20">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" >
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img src="../img/logo.jpeg" class="logo" height="30" width="30">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    NWT:<?php echo $barcode["net_weight"]; ?>  GWT:<?php echo $barcode["gross_weight"]; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <span><?php echo $barcode["item"]; ?></span><span>&nbsp;MC:<?php echo $barcode["making_charges"]; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    echo '</td>';
                                }
                                if ($col > $cols) {
                                    $col = 1;
                                    echo '</tr>';
                                    $row = true;
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>    </div>
        <br>
        <div class="container">
            <button class="btn btn-block btn-success print" type="button">Print bar-code</button>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
</html>
