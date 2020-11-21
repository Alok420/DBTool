<?php include './common/session_db.php'; ?>
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <?php include './common/head.php'; ?>
        <?php include '../Config/common_script.php'; ?>
    </head>

    <body>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->

        <div class="left-sidebar-pro">
            <!-- nav_siderbar php -->
            <?php include './common/nav_sidebar.php'; ?>
            <!-- nav_siderbar End php -->
        </div>
        <!-- Start Welcome area -->
        <div class="all-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="logo-pro">
                            <a href="index.html"><img class="main-logo" src="img/logo/logo.png" alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-advance-area">

                <!-- haeder_menu  start-->
                <?php include './common/header_menu.php'; ?>
                <!-- haeder_menu  end -->
                <!-- Mobile Menu start -->
                <div class="mobile-menu-area">
                    <?php include './common/mobile_menu_area.php'; ?>
                </div>
                <!-- Mobile Menu end -->            
            </div>
            <div class="product-status mg-tb-15">
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <DIV class="container-fluid">
                            <input type="text" id="myInput" class="form-control form-inline" placeholder="Search">
                            <table id="myTable" class="table table-bordered table-condensed">
                                <caption class="text-center">
                                    Posted news / projects

                                </caption>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Type</th>
                                    <th>more</th>
                                </tr>

                                <?php
                                $sql = "select * from articles where article_category_id in (select id from article_category where title in ('Projects','News'))";
                                $rows = $conn->query($sql);

                                while ($row = $rows->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row["id"]; ?></td>
                                        <td><?php echo $row["title"]; ?></td>
                                        <td><img src="../img/articles/<?php echo $row["image"]; ?>" height="50" width="50"> <?php echo $row["image"]; ?></td>

                                        <td>
                                            <?php
                                            $title = $db->select("article_category", "title", array("id" => $row["article_category_id"]))->fetch_assoc()["title"];
                                            if ($title == 'Projects') {
                                                echo "<span class='label label-primary'>Projects</span>";
                                            } else if ($title == 'News') {
                                                echo "<span class='label label-success'>News</span>";
                                            }
                                            ?>
                                        </td>

                                        <td><a href="post_more.php?id=<?php echo $row["id"]; ?>&tbname=articles" class="btn btn-success">More</a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </DIV>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        <?php include './common/footer_script.php'; ?>
    </body>

</html>