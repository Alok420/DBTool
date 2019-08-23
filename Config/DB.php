<?php

interface DBDeclare {

    public function sendTo($path, $param = "");

    public function login($username, $password, $type);

    public function fileUploadWithTable($files, $table, $id = 0, $location = "./", $size = "11m", $type = "jpg,png");

    public function fileUpload($files, $location = "./", $size = "11m", $type = "jpg,png");

    public function showInTable($table, $column = "*", $where = "", $toollist = "all", $externallinks = '', $columntype = "");

    public function showInTableWithoutTool($table, $column = "*", $where = "");

    public function select($table, $column = "*", $where = "", $sort = "id asc");

    public function relateTable($tables);

    public function delete($id, $table);

    public function update($data, $table, $id);

    public function insert($data, $table);

    public function loadTables($tables, $operation);

    public function getIndianDate();

    public function getIndianDateTime();

    public function loginCheck($id, $type);

    public function jqToSqlDate($post, $key);

    public function apiKey($anystring);

    public function userId($name);

    public function userIdWithRange($name, $startrange, $endrange);

    public function sendBack($server);

    public function exist($tbname, $columnvalue);

    public function select_option($tbname, $columnname);
}

class DB implements DBDeclare {

    public $recentinsertedid;
    public $conn;
    public $returnarray = array();

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function exist($tbname, $columnvalue) {
        $exist = "yes";
        foreach ($columnvalue as $key => $value) {
            $data = $this->select($tbname, "*", array($key => $value));
            if ($data->num_rows > 0) {
                $exist = "yes";
                return $exist;
            } else {
                $exist = "no";
                return $exist;
            }
        }
    }

    public function apiKey($anystring) {
        $rand = rand(0, 100000);
        $rawhashword = $anystring . "" . $rand;
        $hashed = password_hash($rawhashword, PASSWORD_DEFAULT);
        return $hashed;
    }

    public function userId($name) {
        $rand = rand(0, 1000);
        $userid = str_replace(" ", "_", $name) . $rand;
        return $userid;
    }

    public function userIdWithRange($name, $startrange, $endrange) {
        $rand = rand($startrange, $endrange);
        $userid = str_replace(" ", "_", $name) . $rand;
        return $userid;
    }

    public function loginCheck($id, $type) {
        if (isset($_SESSION["roleid"]) && isset($_SESSION["loginid"])) {
            if ($type == "role") {
                if ($id == $_SESSION["roleid"]) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == "user") {
                if ($id == $_SESSION["loginid"]) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function getIndianDate() {
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y/m/d");
        return $date;
    }

    public function getIndianDateTime() {
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y/m/d H:i:s");
        return $date;
    }

    public function myProfile($table, $columns = "*", $where = "") {
        $list = $this->select($table, $columns, $where);
        while ($row = $list->fetch_assoc()) {
            echo '<h1>' . $table . ' profile </h1>';
            echo '<table class="table table-bordered table-sm"> ';
            foreach ($row as $key => $val) {
                echo '<tr><td>' . $key . '</td><td>' . $val . '</td></tr>';
            }
            echo "</table>";
        }
    }

    public function login($username, $password, $table) {
        $returnarray = array();
        $query = "select * from $table where userid='$username' or contact='$username'  or email='$username' and blocked='0'";
        $data = $this->conn->query($query);

        if ($data->num_rows > 0) {
            $onedata = $data->fetch_assoc();
            $hash = $onedata["password"];
            if (password_verify($password, $hash)) {
                $candidates_id = $onedata["id"];
                $roles_id = $onedata["role"];
                $api_key = $onedata["api_key"];
                $_SESSION["loginid"] = $candidates_id;
                $_SESSION["role"] = $roles_id;
                $returnarray["status_number"] = 1;
                $returnarray["userid"] = $candidates_id;
                $returnarray["role"] = $roles_id;
                $returnarray["api_key"] = $api_key;
                return $returnarray;
            } else {
                $returnarray["status_number"] = 0;
                $returnarray["status_message"] = "Password not found";
            }
        } else {
            $returnarray["status_number"] = 0;
            $returnarray["status_message"] = "Username not found";
        }
        return $returnarray;
    }

    function fileUploadWithTable($files, $table, $id = 0, $location = "./", $size = "11m", $type = "jpg,png") {

        $returnarray = array();
        $sizearr = str_split($size);
        $sizeinnum = 0;
        $unit = "k";
        if ($id != 0) {
            $this->recentinsertedid = $id;
        } else {
            if (isset($_SESSION["recentinsertedid"])) {
                $this->recentinsertedid = $_SESSION["recentinsertedid"];
            } else {
                $this->recentinsertedid = 0;
            }
        }

        for ($i = 0; $i < count($sizearr); $i++) {
            if (ctype_digit($sizearr[$i])) {
                $sizeinnum .= $sizearr[$i];
            } else {
                $unit = $sizearr[$i];
                break;
            }
        }
        if ($unit != "" || $unit != NULL || $unit != " ") {
            if ($unit == "b") {
                $size = (int) $sizeinnum;
            } else if ($unit == "k") {
                $size = (((int) $sizeinnum) * 1024);
            } else if ($unit == "m") {
                $size = (((int) $sizeinnum) * 1024 * 1024);
            } else if ($unit == "g") {
                $size = (((int) $sizeinnum) * 1024 * 1024 * 1024);
            }
        } else {
            $size = ((int) $sizeinnum);
        }
        $boolean = FALSE;
        foreach ($files as $key1 => $file) {
            $filenamewextension = $file["name"];
            if (strpos($file["name"], "/") !== false) {
                $filepart1 = explode("/", $file["name"]);
                $filenamewextension = end($filepart1);
            } elseif (strpos($file["name"], "\\") !== false) {

                $filepart1 = explode("\\", $file["name"]);
                $filenamewextension = end($filepart1);
            } else {
                $filenamewextension = $file["name"];
            }


            $filepart = explode(".", $file["name"]);
            $extension = end($filepart);
            if ($file["size"] <= $size) {
                $boolean = TRUE;
            } else {
                $boolean = FALSE;
                array_push($returnarray, 0);
                array_push($returnarray, "File size exceed limits: Limit given=$size byte and file size=" . $file["size"] . " byte");
            }
            if (strpos($type, $extension) !== false) {
                $boolean = TRUE;
            } else {
                $boolean = FALSE;
                array_push($returnarray, 0);
                array_push($returnarray, "File type not matched: Type given=$type and file type=" . $extension);
            }
            if ($location == "./") {
                $name = "$location" . $file["name"];
            } else {
                $name = "$location/" . $file["name"];
            }

            if ($boolean === TRUE) {
                $uploadstatus = move_uploaded_file($file["tmp_name"], $name);

                if ($uploadstatus) {
                    $data = array($key1 => $filenamewextension);
                    var_dump($data);

                    if ($this->recentinsertedid > 0) {
                        $message = $this->update($data, $table, $this->recentinsertedid);
                        array_push($returnarray, $message);
                    } else {
                        $this->insert($data, $table);
                    }
                    array_push($returnarray, 1);
                    array_push($returnarray, "File uploaded file info: $name");
                } else {
                    array_push($returnarray, 0);
                    array_push($returnarray, "File not uploaded file info: $name");
                    array_push($returnarray, $uploadstatus);
                }
            } else {
                array_push($returnarray, 0);
                array_push($returnarray, "File not uploaded file info: $name");
            }
        }
        return $returnarray;
    }

    function fileUpload($files, $location = "./", $size = "11m", $type = "jpg,png") {
        $returnarray = array();
        $sizearr = str_split($size);
        $sizeinnum = 0;
        $unit = "k";
        for ($i = 0; $i < count($sizearr); $i++) {
            if (ctype_digit($sizearr[$i])) {
                $sizeinnum .= $sizearr[$i];
            } else {
                $unit = $sizearr[$i];
                break;
            }
        }
        if ($unit != "" || $unit != NULL || $unit != " ") {
            if ($unit == "b") {
                $size = (int) $sizeinnum;
            } else if ($unit == "k") {
                $size = (((int) $sizeinnum) * 1024);
            } else if ($unit == "m") {
                $size = (((int) $sizeinnum) * 1024 * 1024);
            } else if ($unit == "g") {
                $size = (((int) $sizeinnum) * 1024 * 1024 * 1024);
            }
        } else {
            $size = ((int) $sizeinnum);
        }
        $boolean = FALSE;
        foreach ($files as $key1 => $file) {
            $filepart = explode(".", $file["name"]);
            $extension = end($filepart);
            if ($file["size"] <= $size) {
                $boolean = TRUE;
            } else {
                $boolean = FALSE;
                array_push($returnarray, 0);
                array_push($returnarray, "File size exceed limits: Limit given=$size byte and file size=" . $file["size"] . " byte");
            }
            if (strpos($type, $extension) !== false) {
                $boolean = TRUE;
            } else {
                $boolean = FALSE;
                array_push($returnarray, 0);
                array_push($returnarray, "File type not matched: Type given=$type and file type=" . $extension);
            }
            if ($location == "./") {
                $name = "$location" . time() . $file["name"];
            } else {
                $name = "$location/" . time() . $file["name"];
            }
            if ($boolean === TRUE) {
                $uploadstatus = move_uploaded_file($file["tmp_name"], $name);
                if ($uploadstatus) {
                    array_push($returnarray, 1);
                    array_push($returnarray, "File uploaded file info: $name");
                    array_push($returnarray, $uploadstatus);
                } else {
                    array_push($returnarray, 0);
                    array_push($returnarray, "File not uploaded file info: $name");
                    array_push($returnarray, $uploadstatus);
                }
            } else {
                array_push($returnarray, 0);
                array_push($returnarray, "File not uploaded file info: $name");
            }
        }
        return $returnarray;
    }

    function showInTableWithoutTool($table, $column = "*", $where = "") {

        $this->returnarray = array();
        $columns = array();
        $list = $this->select($table, $column, $where);
        while ($row = $list->fetch_assoc()) {
            $tempcol = array();
            foreach ($row as $key => $val) {
                array_push($tempcol, "$key");
            }
            if (count($tempcol) >= count($columns)) {
                $columns = $tempcol;
            }
        }
        ?>
        <?php
        echo '<div id="search" class="table-responsive table-hover table-striped">' . '<caption><h1 style="text-align:center; background-color:rgba(5,5,5,.7); color:white; margin:0px; text-transform:capitalize;">' . $table . ' Records <span id="hideshow" style="font-size:20px;"></span></h1> </caption>';
        echo '<table class="table table-bordered table-sm">'
        . '<thead><tr class="thead-light">';
        for ($i = 0; $i < count($columns); $i++) {
            echo "<th>" . ucwords(str_replace("_", " ", $columns[$i])) . "</th>";
        }
        echo ' </tr></thead><tbody>';
        $j = 0;
        $list = $this->select($table, $column, $where);
        while ($row = $list->fetch_assoc()) {
            $j++;
            echo '<tr>';
            foreach ($row as $key => $val) {
                echo '<td>' . $val . '</td>';
            }

//            echo "<td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord(" . $row["id"] . ",\"" . $table . "\")'  id='updatebtn'>Update</button></td>";
//            echo "<td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord(" . $row["id"] . ",\"" . $table . "\")' id='deletebtn'>Delete</button></td>";
            echo '</tr>';
        }
        echo '</tbody></table></div></div>';
        echo '<script>
            function deleteRecord(id, table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                        location.reload();
                    } else if (this.readyState < 4) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                    }
                };
                xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                xhttp.send();
            }
            



        </script>';
        echo " <script>
            function updateRecord(id,column, table) {
                var a = $('#updateinfo').text();
                $('.modal-body').css(\"background-color\", \"red\");
                selectRecordById(id,column,table);
            }
            function selectRecordById(id,column,table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var obj = JSON.parse(xhttp.responseText);
                        var array = new Array();
                        var array2 = new Array();

                        array = Object.keys(obj);
                        array2 = Object.values(obj);
                         $('#updatemodel .modal-body').text(\"\");
                        for (var i = 0; i < Object.keys(obj).length; i++) {
                            $('#updatemodel .modal-body').append('<label>'+ array[i] +'</label><input type=\"text\" value=\"' + array2[i] + '\" name=\"' + array[i] + '\" class=\"form-control\">');

                        }
                        $('#updatemodel .modal-body').append('<button class=\"btn btn-default btnsub\" onclick=\"getData()\">Update</button>');
                    }
                };
                xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column\", true);
                xhttp.send();
            }
            function getData() {
                var data = \"\", keys = \"\";
                for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                    var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                    var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                    if (i == $('#updatemodel .modal-body input').length - 1) {
                        data += key + \"=\" + single;

                    } else {
                        data += key + \"=\" + single + \"&\";
                    }
                }
                $.get(\"../controller/UpdateData.php?tbname=$table&\"+ data, function (rdata, status) {
                    alert('updated :---'+rdata);
                    location.reload();
                });
            }
        </script>";
        echo '<div class="modal fade" id="updatemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update data</h4>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

        echo '<div class="modal fade" id="deletemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteinfo">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
    }

    function showInTable($table, $column = "*", $where = "", $toollist = "all", $externallinks = '', $columntype = array("key" => "value"), $sort = "id asc") {

        $this->returnarray = array();
        $columns = array();
        $list = $this->select($table, $column, $where, $sort);
        $searchDropDown = array();

        while ($row = $list->fetch_assoc()) {
            $tempcol = array();
            $searchDropDowns = array();
            foreach ($row as $key => $val) {
                array_push($tempcol, "$key");
                array_push($searchDropDowns, "<option value='$key'>$key</option>");
            }
            if (count($tempcol) >= count($columns)) {
                $columns = $tempcol;
                $searchDropDown = $searchDropDowns;
            }
        }
        ?>

        <?php
        echo '        
            <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        ';
        echo '<div class="form-group form-inline"><form action="searchedData.php"> <label>Search.....</lable> <span><select class="form-control" name="searchCol">';
        for ($i = 0; $i < count($searchDropDown); $i++) {
            echo $searchDropDown[$i];
        }
        echo '</select></span><input class="form-control" id="myInput" type="text" name="searching_data" placeholder="Search.."> <input type="submit" value="Srarch" class="btn btn-success"><input type="hidden" name="tbname" value="' . $table . '"></form></div>';
        echo '<table id="myTable" class="table table-sm">'
        . '<thead><tr class="" style="background-color:#4272d7; color:white;">';
        if ($toollist == "update") {
            ?>
            <th></th>
            <?php
        } else if ($toollist == "delete") {
            ?>
            <th></th>
            <?php
        } else if ("all") {
            ?>
            <th></th>
            <th></th>
            <?php
        } else {
            ?>

            <?php
        }
        for ($i = 0; $i < count($columns); $i++) {
            if ($sort == "id asc") {
                $sort = "id desc";
            } else if ($sort == "id desc") {
                $sort = "id asc";
            }
            if ($columns[$i] == "id") {
                echo "<th></i><a href='?sort=$sort' style='text-decoration:none;color:white;text-decoration:underline;'><i class='fa fa-fw fa-sort'>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</a></th>";
            } else {
                echo "<th>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</th>";
            }
        }
        echo ' </tr></thead><tbody>';
        $j = 0;
        $list = $this->select($table, $column, $where, $sort);
        while ($row = $list->fetch_assoc()) {
            $j++;
            echo '<tr>';

            if ($toollist == "update") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")'  id='updatebtn'>Update</button></td>
                <?php
            } else if ($toollist == "delete") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'>Delete</button></td>
                <?php
            } else if ("all") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")'  id='updatebtn'>Update</button></td>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'>Delete</button></td>

                <?php
            } else {
                ?>

                <?php
            }
            foreach ($row as $key => $val) {
                if (strpos($key, "_date") > 0) {
                    $phpdate = strtotime($val);
                    $val = date('d-m-Y', $phpdate);
                }
                if (strpos($key, "_id") > 0) {
                    $startpos = 0;
                    $endpos = strpos($key, "_id");
                    $tbname = substr($key, $startpos, ($endpos) - $startpos);
                    echo '<td><a style="text-decoration:underline;padding:2px;" href="detail.php?id=' . $val . '&tbname=' . $tbname . '">' . $val . '</a></td>';
                } elseif (array_key_exists($key, $columntype)) {
                    $filepath = $columntype[$key];
                    $ext = pathinfo($val, PATHINFO_EXTENSION);
                    if ($ext == "jpg" || $ext == "png" || $ext == "gif") {
                        echo '<td><a href="' . $filepath . $val . '">' . $val . '<iframe height="30" width="30" style"margin:10px; padding:5px;" src="' . $filepath . $val . '"></iframe>' . $val . '</a></td>';
                    } else {
                        echo '<td><a href="' . $filepath . $val . '">' . $val . '</a></td>';
                    }
                } else {
                    echo '<td>' . $val . '</td>';
                }
            }
            if ($externallinks == "addTaskPermissions") {
                $link = "<td><a href=addTaskPermissions.php?id=" . $row["id"] . ">Add task and permissions</a></td>";
//                $startpos = strpos($externallinks, "{");
//                $endpos = strpos($externallinks, "}");
//                $key = substr($externallinks, $startpos + 1, ($endpos - 1) - $startpos);
//                $key2 = '{' . $key . '}';
//                $key = $key . "=" . $row[$key];
//                $externallinks = str_replace($key2, $key, $externallinks);
                echo $link;
            } else if ($externallinks == "addRole") {
                $link = "<td><a href=addRole.php?id=" . $row["id"] . ">Add role</a></td>";
                echo $link;
            } else if ($externallinks == "print_receipt") {
                $link = "<td><a href=receipt.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            } else if ($externallinks == "Receipt for tip") {
                $link = "<td><a href=receipt_trip.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            } else if ($externallinks == "Receipt for challan") {
                $link = "<td><a href=receipt_challan.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            }
            ?>
            </tr>
            <?php
        }
        echo '</tbody></table></div></div>';
        echo '<script>
            function deleteRecord(id, table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                        location.reload();
                    } else if (this.readyState < 4) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                    }
                };
                xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                xhttp.send();
            }
            



        </script>';
        echo " <script>
            function updateRecord(id,column,table) {
                var a = $('#updateinfo').text();
                $('.modal-body').css(\"background-color\", \"whitesmoke\");
                selectRecordById(id,column,table);
            }
            function selectRecordById(id,column,table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var obj = JSON.parse(xhttp.responseText);
                        var array = new Array();
                        var array2 = new Array();

                        array = Object.keys(obj);
                        array2 = Object.values(obj);
                         $('#updatemodel .modal-body').text(\"\");
                        for (var i = 0; i < Object.keys(obj).length; i++) {
                            $('#updatemodel .modal-body').append(<label>'+ array[i] +'</label><input type=\"text\" value=\"' + array2[i] + '\" name=\"' + array[i] + '\" class=\"form-control\">');

                        }
                        $('#updatemodel .modal-body').append('<input type=\"hidden\" name=\"tbname\" value=\"'+table+'\">');
                        $('#updatemodel .modal-body').append('<button class=\"btn btn-success btn-sm btnsub\" onclick=\"getData()\">Update</button>');
                    }
                };
                xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column, true);
                xhttp.send();
            }
            function getData() {
                var data = \"\", keys = \"\";
                for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                    var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                    var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                    if (i == $('#updatemodel .modal-body input').length - 1) {
                        data += key + \"=\" + single;

                    } else {
                        data += key + \"=\" + single + \"&\";
                    }
                }
                $.get(\"../controller/UpdateData.php?\"+ data, function (rdata, status) {
                    alert('updated :---'+rdata);
                    location.reload();
                });
            }
        </script>";
        echo '<div class="modal fade" id="updatemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update data</h4>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

        echo '<div class="modal fade" id="deletemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteinfo">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
    }
//-------------------------this function is not tested yet-------------
    function showInTableByQuery($query="",$toollist = "all", $externallinks = '', $columntype = array("key" => "value"), $sort = "id asc") {

        $this->returnarray = array();
        $columns = array();
        $list = $this->select($query, $column, $where, $sort);
        $searchDropDown = array();

        while ($row = $list->fetch_assoc()) {
            $tempcol = array();
            $searchDropDowns = array();
            foreach ($row as $key => $val) {
                array_push($tempcol, "$key");
                array_push($searchDropDowns, "<option value='$key'>$key</option>");
            }
            if (count($tempcol) >= count($columns)) {
                $columns = $tempcol;
                $searchDropDown = $searchDropDowns;
            }
        }
        ?>

        <?php
        echo '        
            <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        ';
        echo '<div class="form-group form-inline"><form action="searchedData.php"> <label>Search.....</lable> <span><select class="form-control" name="searchCol">  ';
        for ($i = 0; $i < count($searchDropDown); $i++) {
            echo $searchDropDown[$i];
        }
        echo '</select></span><input class="form-control" id="myInput" type="text" name="searching_data" placeholder="Search.."> <input type="submit" value="Srarch" class="btn btn-outline-success btn-sm"><input type="hidden" name="tbname" value="' . $table . '"></form></div><br>';
        echo '<table id="myTable" class="table  ">'
        . '<thead><tr class="" style="background-color:#4272d7; color:white;">';
        if ($toollist == "update") {
            ?>
            <th></th>
            <?php
        } else if ($toollist == "delete") {
            ?>
            <th></th>
            <?php
        } else if ("all") {
            ?>
            <th></th>
            <th></th>
            <?php
        } else {
            ?>

            <?php
        }
        for ($i = 0; $i < count($columns); $i++) {
            if ($sort == "id asc") {
                $sort = "id desc";
            } else if ($sort == "id desc") {
                $sort = "id asc";
            }
            if ($columns[$i] == "id") {
                echo "<th></i><a href='?sort=$sort' style='text-decoration:none;color:white;text-decoration:underline;'><i class='fa fa-fw fa-sort'>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</a></th>";
            } else {
                echo "<th>" . ucwords(str_replace("_", "&nbsp;", $columns[$i])) . "</th>";
            }
        }
        echo ' </tr></thead><tbody>';
        $j = 0;
        $list = $this->select($table, $column, $where, $sort);
        while ($row = $list->fetch_assoc()) {
            $j++;
            echo '<tr>';

            if ($toollist == "update") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")'  id='updatebtn'>Update</button></td>
                <?php
            } else if ($toollist == "delete") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'>Delete</button></td>
                <?php
            } else if ("all") {
                ?>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#updatemodel' onclick='updateRecord("<?php echo $row["id"]; ?>", "<?php echo $column; ?>", "<?php echo $table; ?>")'  id='updatebtn'>Update</button></td>
                <td><button class='btn btn-outline-success btn-sm' data-toggle='modal' data-target='#deletemodel' onclick='deleteRecord("<?php echo $row["id"]; ?>", "<?php echo $table; ?>")' id='deletebtn'>Delete</button></td>

                <?php
            } else {
                ?>

                <?php
            }
            foreach ($row as $key => $val) {
                if (strpos($key, "_date") > 0) {
                    $phpdate = strtotime($val);
                    $val = date('d-m-Y', $phpdate);
                }
                if (strpos($key, "_id") > 0) {
                    $startpos = 0;
                    $endpos = strpos($key, "_id");
                    $tbname = substr($key, $startpos, ($endpos) - $startpos);
                    echo '<td><a style="text-decoration:underline;padding:2px;" href="detail.php?id=' . $val . '&tbname=' . $tbname . '">' . $val . '</a></td>';
                } elseif (array_key_exists($key, $columntype)) {
                    $filepath = $columntype[$key];
                    $ext = pathinfo($val, PATHINFO_EXTENSION);
                    if ($ext == "jpg" || $ext == "png" || $ext == "gif") {
                        echo '<td><a href="' . $filepath . $val . '">' . $val . '<iframe height="30" width="30" style"margin:10px; padding:5px;" src="' . $filepath . $val . '"></iframe>' . $val . '</a></td>';
                    } else {
                        echo '<td><a href="' . $filepath . $val . '">' . $val . '</a></td>';
                    }
                } else {
                    echo '<td>' . $val . '</td>';
                }
            }
            if ($externallinks == "addTaskPermissions") {
                $link = "<td><a href=addTaskPermissions.php?id=" . $row["id"] . ">Add task and permissions</a></td>";
//                $startpos = strpos($externallinks, "{");
//                $endpos = strpos($externallinks, "}");
//                $key = substr($externallinks, $startpos + 1, ($endpos - 1) - $startpos);
//                $key2 = '{' . $key . '}';
//                $key = $key . "=" . $row[$key];
//                $externallinks = str_replace($key2, $key, $externallinks);
                echo $link;
            } else if ($externallinks == "addRole") {
                $link = "<td><a href=addRole.php?id=" . $row["id"] . ">Add role</a></td>";
                echo $link;
            } else if ($externallinks == "print_receipt") {
                $link = "<td><a href=receipt.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            } else if ($externallinks == "Receipt for tip") {
                $link = "<td><a href=receipt_trip.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            } else if ($externallinks == "Receipt for challan") {
                $link = "<td><a href=receipt_challan.php?id=" . $row["id"] . ">Print Receipt</a></td>";
                echo $link;
            }
            ?>
            </tr>
            <?php
        }
        echo '</tbody></table></div></div>';
        echo '<script>
            function deleteRecord(id, table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                        location.reload();
                    } else if (this.readyState < 4) {
                        document.getElementById("deleteinfo").innerHTML = "loading...";
                    }
                };
                xhttp.open("GET", "../controller/DeleteController.php?id=" + id + "&table_name=" + table, true);
                xhttp.send();
            }
            



        </script>';
        echo " <script>
            function updateRecord(id,column,table) {
                var a = $('#updateinfo').text();
                $('.modal-body').css(\"background-color\", \"whitesmoke\");
                selectRecordById(id,column,table);
            }
            function selectRecordById(id,column,table) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var obj = JSON.parse(xhttp.responseText);
                        var array = new Array();
                        var array2 = new Array();

                        array = Object.keys(obj);
                        array2 = Object.values(obj);
                         $('#updatemodel .modal-body').text(\"\");
                        for (var i = 0; i < Object.keys(obj).length; i++) {
                            $('#updatemodel .modal-body').append('<br><label>'+ array[i] +'</label><input type=\"text\" value=\"' + array2[i] + '\" name=\"' + array[i] + '\" class=\"form-control\">');

                        }
                        $('#updatemodel .modal-body').append('<br><input type=\"hidden\" name=\"tbname\" value=\"'+table+'\">');
                        $('#updatemodel .modal-body').append('<br><button class=\"btn btn-default btnsub\" onclick=\"getData()\">Update</button>');
                    }
                };
                xhttp.open(\"GET\", \"../controller/UpdateFormSelection.php?id=\" + id + \"&table_name=\"+table+\"&column=\"+column, true);
                xhttp.send();
            }
            function getData() {
                var data = \"\", keys = \"\";
                for (var i = 0; i < $('#updatemodel .modal-body input').length; i++) {
                    var single = $('#updatemodel .modal-body input:eq(' + i + ')').val();
                    var key = $('#updatemodel .modal-body input:eq(' + i + ')').attr(\"name\");
                    if (i == $('#updatemodel .modal-body input').length - 1) {
                        data += key + \"=\" + single;

                    } else {
                        data += key + \"=\" + single + \"&\";
                    }
                }
                $.get(\"../controller/UpdateData.php?\"+ data, function (rdata, status) {
                    alert('updated :---'+rdata);
                    location.reload();
                });
            }
        </script>";
        echo '<div class="modal fade" id="updatemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update data</h4>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

        echo '<div class="modal fade" id="deletemodel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteinfo">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
    }

    function select($table, $columns = "*", $where = "", $sort = "id asc") {
        $this->returnarray = array();

        if ($where != "" && count($where) > 0) {
            $SQL = "select $columns from $table where ";
            $i = 0;
            $j = 0;
            $operator = "=";
            $operatoroc = "or";
            foreach ($where as $column => $value) {
                if ($column != "urlparam") {
                    $i++;
                    if (strpos($column, "conditiontype") !== FALSE) {
                        $operator = $value;
                        if ($operator == "like") {
                            $value = "%$value%";
                        }
                    } else if (strpos($column, "operatoroc") !== FALSE) {
                        $operatoroc = $value;
                        if ($operator == "like") {
                            $value = "%$value%";
                        }
                    } else {

                        $j++;
                        if ($j > 1) {
                            $column = $operatoroc . " " . $column;
                        }
                        $SQL .= "$column $operator '$value' ";


                        $operator = "=";
                    }
                }
            }
        } else {
            $SQL = "select $columns from $table";
        }
        $SQL .= " order by $sort";
//        echo "$SQL";
        if (!empty($columns)) {
            $data = $this->conn->query($SQL);
            return $data;
        } else {
            return $this->conn->error;
        }
    }

    function query($query = "") {
        $this->returnarray = array();
        if (!empty($query)) {
            $data = $this->conn->query($query);
            return $data;
        } else {
            return $this->conn->error;
        }
    }

    function relateTable($tables) {
        $this->returnarray = array();

        foreach ($tables as $key => $value) {
            $childpart = explode(":", $value);
            if ($childpart[0] != "drop" && $childpart[0] != "dropcol") {
                if (count($childpart) > 0) {
                    $on_delete = "on delete " . $childpart[1] or "";
                    $on_update = "on update " . $childpart[2] or "";
                    $SQL1 = "alter table $childpart[0] add $key" . "_id int(10)";
                    if ($this->conn->query($SQL1)) {
                        echo "~info:new column ($key" . "_id) added. ";
                    } else {
                        echo "<br>~info:Foreign key column not added. $SQL1 " . $this->conn->error;
                    }
                    $SQL = "ALTER TABLE $childpart[0] ADD constraint $key" . "_" . "$childpart[0]" . "_" . "fkey FOREIGN KEY ($key" . "_id) REFERENCES $key(id) $on_delete $on_update";
                    if ($this->conn->query($SQL)) {
                        echo "<br>~info:Now $key is parrent and $childpart[0] is child ";
                    } else {
                        echo "<br>~info: Relation creation was unsuccessful.$SQL " . $this->conn->error;
                    }
                } else {
                    echo "<br>~info:No delete and update rule found " . $this->conn->error;
                }
            } else if ($childpart[0] == "drop" || $childpart[0] == "dropcol") {

                if (count($childpart) > 1) {
                    if ($childpart[0] == "dropcol") {
                        $SQL1 = "alter table $childpart[1] drop column $childpart[1]" . "_id";

                        $SQL = "alter table $childpart[1] drop foreign key " . $key . "_" . $childpart[1] . "_" . "fkey";
                        if ($this->conn->query($SQL)) {
                            echo "<br>~info: Constraint dropped ($key and $childpart[1] relationship is dropped.)";
                            if ($this->conn->query($SQL1)) {
                                echo "<br>~info:Column ($childpart[1]" . "_id) is removed.";
                            } else {
                                echo "<br>~info:column not dropped $SQL1 " . $this->conn->error;
                            }
                        } else {
                            echo "<br>~info: Constraint dropping was unsuccessful. $SQL " . $this->conn->error;
                        }
                    } else {
                        $SQL = "alter table $childpart[1] drop foreign key " . $key . "_" . $childpart[1] . "_" . "fkey";
                        if ($this->conn->query($SQL)) {
                            echo "<br>~info: Constraint dropped ($key and $childpart[1] relationship is dropped.)";
                        } else {
                            echo "<br>~info: Constraint dropping was unsuccessful. $SQL " . $this->conn->error;
                        }
                    }
                }
            } else if ($operation == "change") {
                
            }
        }
    }

    function delete($id, $table) {
        $this->returnarray = array();

        $SQL = "delete from $table where id=$id";
        $m = $this->conn->query($SQL);
        if ($m) {
            array_push($this->returnarray, 1);
            array_push($this->returnarray, "<br>~info:A row id ($id) is deleted from $table");
        } else {
            array_push($this->returnarray, 0);
            array_push($this->returnarray, "<br>~info:there is some problem in deletion " . $this->conn->error);
        }
        return $this->returnarray;
    }

    function update($data, $table, $id) {
//        $this->returnarray = array();

        foreach ($data as $column => $value) {
            $value = str_replace("'", "''", $value);
            if (count($data) > 0) {
                if ($column == "password") {
                    $pass = password_hash($value, PASSWORD_DEFAULT);
                    $value = $pass;
                }
                $SQL = "update $table set $column='$value' where id=$id";
//                echo $SQL;
                $m = $this->conn->query($SQL);
                if ($m) {
                    array_push($this->returnarray, 1);
                    array_push($this->returnarray, "<br>~info: $table updated:-($SQL)");
                } else {
                    array_push($this->returnarray, 0);
                    array_push($this->returnarray, "<br>~info:not updated there is some reason " . $this->conn->error);
                }
            }
        }
        return $this->returnarray;
    }

    function insert($data, $table) {
        $this->returnarray = array();
        $i = 0;
        $id = 0;
        foreach ($data as $column => $value) {
            $value = str_replace("'", "''", $value);
            $i++;
            if (count($data) >= 1) {
                if ($i == 1) {
                    if ($column == "password") {
                        $pass = password_hash($value, PASSWORD_DEFAULT);
                        $value = $pass;
                    }


                    $SQL = "insert into $table($column) values('$value')";
                    $m = $this->conn->query($SQL);
                    $id = $this->conn->insert_id;
                    $_SESSION["recentinsertedid"] = $id;
                    if ($m) {
                        array_push($this->returnarray, 1);
                        array_push($this->returnarray, "<br>~info: $table inserted:-($SQL)");
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "<br>~info:not inserted there is some reason " . $this->conn->error);
                    }
                } else {
                    if ($column == "password") {
                        $pass = password_hash($value, PASSWORD_DEFAULT);
                        $value = $pass;
                    }

                    $SQL = "update $table set $column='$value' where id=$id";
                    $m = $this->conn->query($SQL);
                    if ($m) {
                        array_push($this->returnarray, 1);
                        array_push($this->returnarray, "<br>~info: $table updated:-($SQL)");
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "<br>~info:not updated there is some reason " . $this->conn->error);
                    }
                }
            }
        }
        return $this->returnarray;
    }

    function loadTables($tables, $operation) {
        $this->returnarray = array();
        if (count($tables) > 0) {

            foreach ($tables as $key => $value) {
//  -------------------------- column level code---------------------------
                $i = 0;
                $tables = explode(":", $key);
//                echo "<br>~info:$operation  detected-----------------------";
                if (count($tables) > 1 && $operation == "change") {
//                    echo "<br>~info:Renaming table " . $tables[count($tables) - 2] . " to " . $tables[count($tables) - 1];

                    $SQL = "ALTER TABLE " . $tables[count($tables) - 2] . " RENAME " . $tables[count($tables) - 1];
//                    echo "<br>$SQL";
                    $m = $this->conn->query($SQL);
                    if ($m) {
                        array_push($this->returnarray, 1);
                        array_push($this->returnarray, "table altered " . $SQL);
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "table not altered " . $SQL . " error: " . $this->conn->error);
                    }
                } else if (count($tables) > 1 && $operation == "drop") {
                    if ($tables[0] == "drop") {
                        $SQL = "drop table " . $tables[count($tables) - 1];
                        $m = $this->conn->query($SQL);
                        if ($m) {
                            array_push($this->returnarray, 1);
                            array_push($this->returnarray, "~info:" . $tables[count($tables) - 1] . " removed");
                        } else {
                            array_push($this->returnarray, 0);
                            array_push($this->returnarray, "table not removed " . $SQL . " : error " . $this->conn->error);
                        }
                    }
                }
//  -------------------------- column level code---------------------------
                foreach ($value as $column => $type) {
                    $datatype = "";
                    $datatypesize = "";
                    $columnconstraint = "";
                    $columnconstraint2 = "";
                    $i++;
                    $typeinfo = explode(":", $type);
                    if (count($typeinfo) == 1) {
                        $datatype = $typeinfo[0];
                    } else if (count($typeinfo) == 2) {
                        $datatype = $typeinfo[0];
                        $datatypesize = $typeinfo[1];
                    } else if (count($typeinfo) == 3) {
                        $datatype = $typeinfo[0];
                        $datatypesize = $typeinfo[1];
                        $columnconstraint = $typeinfo[2];
                    } else if (count($typeinfo) == 4) {
                        $datatype = $typeinfo[0];
                        $datatypesize = $typeinfo[1];
                        $columnconstraint = $typeinfo[2];
                        $columnconstraint2 = $typeinfo[3];
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "You have passed multiple constraint that is not supported max 2 constraint : " . $SQL);
                    }
                    if (count($value) > 0) {
                        if ($operation == "create") {
//                            echo "<br>~info:create table selected-------------";
                            if ($i == 1) {
                                $SQL = "create table IF NOT EXISTS $key($column $datatype($datatypesize) $columnconstraint $columnconstraint2)";
//                                echo "<br>$SQL<br>";
                                if ($this->conn->query($SQL)) {
                                    array_push($this->returnarray, 1);
                                    array_push($this->returnarray, "table created " . $SQL);
                                } else {
                                    array_push($this->returnarray, 0);
                                    array_push($this->returnarray, "Table not created because of" . $this->conn->error . " : " . $SQL);
                                }
                            } else {
                                if (empty($datatypesize)) {
                                    $SQL = "ALTER TABLE $key ADD $column $datatype $columnconstraint $columnconstraint2";
                                } else {
                                    $SQL = "ALTER TABLE $key ADD $column $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                }
//                                echo "<br>$SQL<br>";
                                if ($this->conn->query($SQL)) {
                                    array_push($this->returnarray, 1);
                                    array_push($this->returnarray, "table altered " . $SQL);
                                } else {
                                    array_push($this->returnarray, 0);
                                    array_push($this->returnarray, "Table not created because of" . $this->conn->error . " : " . $SQL);
                                }
                            }
                        } else if ($operation == "change") {
                            $cols = explode(":", $column);

                            if (count($cols) > 1) {
                                if (count($tables) > 0) {
                                    $SQL = "ALTER TABLE " . (count($tables) - 1) . " change " . $cols[(count($cols) - 1)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
//                                    echo "$SQL";
                                    $m = $this->conn->query($SQL);
                                    if ($m) {

                                        array_push($this->returnarray, 1);
                                        array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                    }
                                } else {
                                    $SQL = "ALTER TABLE " . $tables[(count($tables) - 1)] . " change " . $cols[(count($cols) - 2)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
//                                    echo "$SQL";
                                    $m = $this->conn->query($SQL);
                                    if ($m) {
                                        array_push($this->returnarray, 1);
                                        array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                    } else {
                                        array_push($this->returnarray, 0);
                                        array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                    }
                                }
                            } else if (count($cols) == 1) {
                                $SQL = "ALTER TABLE " . $tables[(count($tables) - 1)] . " change " . $cols[(count($cols) - 1)] . " " . $cols[(count($cols) - 1)] . " $datatype($datatypesize) $columnconstraint $columnconstraint2";
                                $m = $this->conn->query($SQL);
                                if ($m) {
                                    array_push($this->returnarray, 1);
                                    array_push($this->returnarray, "column $cols[0] changed to $cols[1]----" . $SQL);
                                } else {
                                    array_push($this->returnarray, 0);
                                    array_push($this->returnarray, "column $cols[0] not changed to $cols[1]----" . $this->conn->error . " : " . $SQL);
                                }
                            }
                        }
                    } else {
                        array_push($this->returnarray, 0);
                        array_push($this->returnarray, "please pass some column: " . $SQL);
                    }
                }
            }
        }
        return $this->returnarray;
    }

    public function jqToSqlDate($post, $key) {
        $form_date = $post[$key];
        $date = DateTime::createFromFormat('m/d/Y', $form_date);
        return $date->format("Y-m-d");
    }

    public function sendBack($server, $info = "?info=Record modified successfully") {
        $returnpath = "";
        $returnpath = $server["HTTP_REFERER"] . $info;
        echo '<script>window.location.href="' . $returnpath . '";</script>';
    }

    public function select_option($tbname, $columnname, $col = "*", $where = "") {
        if ($where != "") {
            $data = $this->select($tbname, $col, $where);
        } else {
            $data = $this->select($tbname, $col);
        }

        while ($one = $data->fetch_assoc()) {
            ?>
            <option value="<?php echo $one["id"]; ?>"><?php echo $one[$columnname]; ?></option>
            <?php
        }
    }

    public function select_option_withcolasval($tbname, $columnname) {
        $data = $this->select($tbname);

        while ($one = $data->fetch_assoc()) {
            ?>
            <option value="<?php echo $one[$columnname]; ?>"><?php echo $one[$columnname]; ?></option>
            <?php
        }
    }

    public function sendTo($path, $param = "") {
        $returnpath = "";
        echo '<script>window.location.href="' . $path . '?' . $param . '";</script>';
    }

}
