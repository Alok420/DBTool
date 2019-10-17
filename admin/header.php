<div class="navbar-container">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <ul class="navbar-nav">
            <?php
            $userid = "";
            if (isset($_SESSION["loginid"]) && $_SESSION["role"] == "admin") {
                $userid = $_SESSION["loginid"];
                echo '<li class="nav-item"><a href="../controller/logout.php" target="_top"><div class="btn-square-solid ">Logout</div></a></li>';
                echo ' <li class="nav-item"><a href="entry.php" target="_top"><div class="btn-square-solid ">Home</div></a></li>';
            } else {
                $db->sendTo("../login.php");
                echo ' <li class="nav-item active"><a href="../login.php" target="_top"><div class="btn-square-solid ">Login</div></a></li>';
            }
            ?>
        </ul>
    </nav>
</div>