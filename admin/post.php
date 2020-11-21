<?php include './common/session_db.php'; ?>
<!doctype html>
<html class="no-js" lang="en">


    <head>
        <?php include './common/head.php'; ?>
        <?php include '../Config/common_script.php'; ?>
    </head>
    <body>
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
                            <h3 class="service_title" style="text-align: center;">Create post for(Projects or News)</h3>
                            <div style="">
                                <form action="../controller/insert2.php" class="p-5 bg-white" method="POST" enctype="multipart/form-data">
                                    <div class="row form-group">
                                        <div class="row form-group col-md-12">
                                            <div class="col-md-6">
                                                <label class="text-black" for="company">Post title</label> 
                                                <input type="text" id="company" name="title" class="form-control" placeholder="Enter title">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-black" for="company">Post type</label> 
                                                <select class="form-control" name="article_category_id">
                                                    <?php
                                                    $sql = "select id,title from article_category where title in ('Projects','News')";
                                                    $rows = $conn->query($sql);
                                                    while ($row = $rows->fetch_assoc()) {
                                                        ?>
                                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["title"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row form-group col-md-12">
                                            <div class="col-md-12">
                                                <label class="text-black" for="company">Image</label> 
                                                <input type="file" id="company" name="image" class="form-control" placeholder="Enter Company Name">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="text-black" for="description">Post description</label> 
                                            <textarea class="form-control" name="description" id="description"></textarea>
                                        </div>

                                        <input type="hidden" name="api_key" value="<?php echo $user["api_key"]; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                                        <input type="hidden" name="tbname" value="articles">
                                        <div class="col-md-12">
                                            <input type="submit" value="Add post" style="float:right; margin-top: 10px;" class="btn btn-primary py-2 px-4 text-white">
                                        </div>
                                    </div>
                                </form>

                            </div>

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
        <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('description');
        </script>
        <?php include './common/footer_script.php'; ?>
    </body>

</html>