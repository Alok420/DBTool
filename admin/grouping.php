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
                <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css">

        <link rel="stylesheet" type="text/css" href="style/common.css">
        <script language="Javascript" src="../HtmlBox_4.0.3/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script language="Javascript" src="../HtmlBox_4.0.3/htmlbox.min.js" type="text/javascript"></script>
        <style>
            .table-responsive table .panel-group *{
                margin: 0px;
                padding: 0px;
            }
            .table-responsive table *{
                margin: 0px;
                padding: 3px;
                
            }
            .table-responsive table .panel-group .panel-title{
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <h3 style="text-align: center;">Stock report</h3>
            <div class="row">
                <div class="col-sm-2">
                    <?php include './sidebar.php'; ?>
                </div>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <?php
                                $data = $conn->query("select item,COUNT(item) as countof,sum(net_weight) as total_net_weight from barcode  where status = '0' group by item order by id asc");
                                ?>
                                <h3>Total items <?php echo $data->num_rows; ?></h3>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Item count</th>
                                            <th>Sum of Net Weight</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $data = $conn->query("select item,COUNT(item) as countof,sum(net_weight) as total_net_weight from barcode  where status = '0' group by item order by id asc");
                                    $loop = 0;
                                    $print1 = "";
                                    $print2 = "";
                                    $print3 = "";
                                    $print4 = "";
                                    $print5 = "";
                                    $print6 = "";
                                    $print7 = "";
                                    $print8 = "";
                                    $countof = 0;
                                    $total_net_weight = 0;
                                    while ($barcode = $data->fetch_assoc()) {
                                        $loop++;
                                        $countof += $barcode["countof"];
                                        $total_net_weight += $barcode["total_net_weight"];
                                        ?>
                                        <tr>
                                            <td style="text-align: left;">
                                                <div class="panel-group">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h6 class="panel-title">
                                                                <a style="text-decoration: none; color: black;" data-toggle="collapse" href="#collapse<?php echo $loop; ?>"><?php echo $barcode["item"]; ?> &blacktriangledown;</a>
                                                            </h6>
                                                        </div>


                                                        <?php
                                                        $query = "select item,COUNT(item) as counteditem,net_weight from barcode  where status = '0' and item = '" . $barcode["item"] . "' group by net_weight order by id asc";
                                                        $data2 = $conn->query($query);

                                                        $count1 = $count2 = $count3 = $count4 = $count5 = $count6 = $count7 = $count8 = $count9 = 0;
                                                        while ($barcode2 = $data2->fetch_assoc()) {
                                                            ?>

                                                            <?php
                                                            if ($barcode2["net_weight"] >= 0 && $barcode2["net_weight"] <= 5) {
                                                                $count1++;
                                                                $print1 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">0-5</div><div class="col-sm-4">' . $count1 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 5 && $barcode2["net_weight"] <= 10) {
                                                                $count2++;
                                                                $print2 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">5-10</div><div class="col-sm-4">' . $count2 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 10 && $barcode2["net_weight"] <= 20) {
                                                                $count3++;
                                                                $print3 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">10-20</div><div class="col-sm-4">' . $count3 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 20 && $barcode2["net_weight"] <= 40) {
                                                                $count4++;
                                                                $print4 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">20-40</div><div class="col-sm-4">' . $count4 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 40 && $barcode2["net_weight"] <= 70) {
                                                                $count5++;
                                                                $print5 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">40-70</div><div class="col-sm-4">' . $count5 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 70 && $barcode2["net_weight"] <= 100) {
                                                                $count6++;
                                                                $print6 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">70-100</div><div class="col-sm-4">' . $count6 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 100 && $barcode2["net_weight"] <= 150) {
                                                                $count7++;
                                                                $print7 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">100-150</div><div class="col-sm-4">' . $count7 . '</div></div></li>';
                                                            } else if ($barcode2["net_weight"] > 150 && $barcode2["net_weight"] <= 200) {
                                                                $count8++;
                                                                $print8 = '<li class="list-group-item"><div class="row"><div class="col-sm-4">' . $barcode2["item"] . '</div><div class="col-sm-4">150-200</div><div class="col-sm-4">' . $count8 . '</div></div></li>';
                                                            }
                                                            ?>

                                                            <?php
                                                        }
                                                        ?>
                                                        <div id="collapse<?php echo $loop; ?>" class="panel-collapse collapse">
                                                            <ul class="list-group">
                                                                <?php
                                                                echo $print1;
                                                                echo $print2;
                                                                echo $print3;
                                                                echo $print4;
                                                                echo $print5;
                                                                echo $print6;
                                                                echo $print7;
                                                                echo $print8;
                                                                $print1 = "";
                                                                $print2 = "";
                                                                $print3 = "";
                                                                $print4 = "";
                                                                $print5 = "";
                                                                $print6 = "";
                                                                $print7 = "";
                                                                $print8 = "";
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </td>
                                            <td><?php echo $barcode["countof"]; ?></td>
                                            <td><?php echo $barcode["total_net_weight"]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr style="font-weight: bold;">
                                        <td>Total</td>
                                        <td><?php echo $countof; ?></td>
                                        <td><?php echo $total_net_weight; ?></td>
                                    </tr>
                                </table>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive table--no-card m-b-30">
                                        <?php
                                        $data = $conn->query("select item,COUNT(item) as countof,sum(net_weight) as total_net_weight from barcode  where status = 'sold' group by item order by id asc");
                                        ?>
                                        <h3>Sold items <?php echo $data->num_rows; ?></h3>
                                        <table class = "table table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Item count</th>
                                                    <th>Sum of Net Weight</th>
                                                </tr>
                                            </thead>

                                            <?php
                                            $data = $conn->query("select item,COUNT(item) as countof,sum(net_weight) as total_net_weight from barcode  where status = 'sold' group by item order by id asc");
                                            while ($barcode = $data->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $barcode["item"]; ?></td>
                                                    <td><?php echo $barcode["countof"]; ?></td>
                                                    <td><?php echo $barcode["total_net_weight"]; ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../Config/common_script.php'; ?>

    </body>
</html>
