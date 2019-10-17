<?php
include './session_conn_db.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style/common.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css">
        <script language="Javascript" src="../HtmlBox_4.0.3/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script language="Javascript" src="../HtmlBox_4.0.3/htmlbox.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $("#item").focus();
                $("#net_weight").focus(function () {
                    $(this).val($("#gross_weight").val());
                });
                var isCtrlHold = false;
                var isShiftHold = false;
                $(document).keyup(function (e) {
                    if (e.which == 17) //17 is the code of Ctrl button
                        isCtrlHold = false;
                    if (e.which == 16) //16 is the code of Shift button
                        isShiftHold = false;
                });
                $(document).keydown(function (e) {
                    if (e.which == 17)
                        isCtrlHold = true;
                    if (e.which == 16)
                        isShiftHold = true;
                    ShortcutManager(e, isCtrlHold);
                });
                $("#purity").on("change paste keyup", function () {
                    var selection = this.value;

                    if (selection == "Diamond & Stone") {
                        $(".caret,.mrp").show();
                    } else {
                        $(".caret,.mrp").hide();
                    }
                });
            });
            function ShortcutManager(e, isCtrlHold) {
                if (isCtrlHold && e.which == 73) { //75 is the code of K button
                    e.preventDefault(); //prevent browser from the default behavior
                    $("#formshowhide").click();
                }
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

            .main{
                border: thin solid gray;
                border-radius: 10px;

            }
            .caret,.mrp{
                display: none;
            }
        </style>
    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <h3 style="text-align: center;">Entries</h3>
            <div class="row">
                <div class="col-sm-2">
                    <?php include './sidebar.php'; ?>
                </div>
                <div class="col-sm-10 main">
                    <div>
                        <div style="color: green; font-size: 30px;"><?php echo isset($_REQUEST["info"]) ? $_REQUEST["info"] : ""; ?></div>
                        <?php
                        if (isset($_GET["recentinsertedid"])) {
                            $recentinsertedid = $_GET["recentinsertedid"];
                            echo '<h3>Successfully saved</h3>';
                            $data = $db->select("barcode", "item", array("id" => $recentinsertedid));
                            $onebarcode = $data->fetch_assoc();
                        }
                        ?>
                        <button id="formshowhide" class="btn btn-outline-success btn-sm" data-toggle="collapse" data-target="#addroleform">Add Product</button>
                        <form class="form-inline" style="display: inline;" method="POST" action="entry.php">
                            <input style="height: 31px; border: thin solid black;" class="form-control" placeholder="Set daily rate" type="text" name="dailyrate">
                            <button type="submit" class="btn btn-dark btn-sm">Set daily rate</button>
                            <input type="button" onclick="window.location.href = 'readfromexcel.php'" class="btn btn-sm btn-dark" value="Import Bulk">
                        </form>
                        <div id="addroleform" class="collapse show">
                            <form method="POST" action="../controller/insert2.php" enctype="multipart/form-data" >
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label>Item</label>
                                        <select class="form-control " name="item" id="item">

                                            <?php
                                            if (isset($_GET["recentinsertedid"])) {
                                                echo '<option selected>' . $onebarcode["item"] . '</option>';
                                            }
                                            $db->select_option_withcolasval("items", "item");
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label>Gross Weight</label>
                                        <input class="form-control" type="text" name="gross_weight" id="gross_weight" autocomplete="off">
                                    </div> 
                                    <div class="form-group col-sm-4">
                                        <label>Net Weight</label>
                                        <input class="form-control" type="text" name="net_weight" id="net_weight" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-2">
                                        <label for="pwd">Purity</label>
                                        <select name="purity" class="form-control" id="purity">
                                            <option value="916 (22k)">916 (22k)</option>
                                            <option value="750 (18k)">750 (18k)</option>
                                            <option value="580 (14k)">580 (14k)</option>
                                            <option value="Diamond & Stone">Diamond & Stone</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-2 caret">
                                        <label for="pwd">Caret</label>
                                        <input class="form-control" type="text" value="0.0" name="caret">
                                    </div>
                                    <div class="form-group col-sm-2 mrp">
                                        <label for="pwd">MRP</label>
                                        <input class="form-control" type="text" value="0.00" name="mrp">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="pwd">Making charges</label>
                                        <input class="form-control" type="text" name="making_charges">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="pwd">GST %</label>
                                        <input class="form-control" type="text" name="gst" value="3.0">
                                        <input type="hidden" name="tbname" value="barcode">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="pwd">&nbsp;</label>
                                        <input type="submit" class="form-control btn btn-dark" value="Save">
                                    </div>

                                </div>
                            </form> 
                        </div>
                    </div>
                    <script>
                        $(document).ready(function () {
                            $(".printExcel").click(function () {
                                $(function () {
                                    $("#myTable").table2excel({
                                        exclude: ".noExl",
                                        name: "Excel Document Name",
                                        filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                                        fileext: ".xls",
                                        exclude_img: true,
                                        exclude_links: true,
                                        exclude_inputs: true
                                    });
                                });
                            });
                        });
                    </script>
                    <div class="row" >
                        <div class="col-lg-12">
                            <div class="table-responsive table--no-card m-b-30" style="max-height: 300px; overflow: scroll;">
                                <?php
                                if (isset($_POST["dailyrate"])) {
                                    $dailyrate = $_POST["dailyrate"];
                                    $sql = "update barcode set rate='$dailyrate'";
                                    if ($conn->query($sql)) {
                                        echo $conn->error;
                                    } else {
                                        echo $conn->error;
                                    }
                                }
                                $db->showInTable("barcode", "*", array("status" => "0"), "update,delete", $externallinks = "Sellstatus", array(), $sort);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include '../Config/common_script.php'; ?>
    </body>
</html>
