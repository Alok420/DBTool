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


    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <h3 style="text-align: center;">Items</h3>
            <div class="row">
                <div class="col-sm-2">
                    <?php include './sidebar.php'; ?>
                </div>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive table--no-card m-b-30">
                                <form method="POST" action="../controller/insert2.php" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input placeholder="Item" type="text" name="item" class="form-control" >
                                        </div>
                                        <input type="hidden" name="tbname" value="items">
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-dark">Save</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <button class="btn btn-success printExcel"> Print Excel</button>
                                <br>
                                <?php
                                $db->showInTable("items", "*");
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
