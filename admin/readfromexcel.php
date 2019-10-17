<form method="POST" action="readfromexcel.php" enctype="multipart/form-data" >
    <input type="file" name="excel">
    <input type="submit" name="save">
</form>
Download Sample File: <a href="dummy.xlsx">Download</a>

<?php
include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';

// Include Spout library                
require_once './spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$db = new DB($conn);
if (isset($_POST["save"])) {
    $pathinfo = pathinfo($_FILES["excel"]["name"]);
    if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['excel']['size'] > 0) {
        $inputFileName = $_FILES['excel']['tmp_name'];
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($inputFileName);
        $count = 1;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($count >= 2) {
                    $item = $row[0];
                    $items = $db->select("items", "*", array("item" => $item));
                    $item = $items->fetch_assoc();
                    if ($item->num_rows > 0) {
                        $gross_weight = $row[1];
                        $net_weight = $row[2];
                        $purity = $row[3];
                        $rate = $row[4];
                        $making_charges = $row[5];
                        $caret = $row[6];
                        $mrp = $row[7];
                        $gst = $row[8];
                        $status = "0";
                        $sql = "INSERT INTO barcode(item,gross_weight,net_weight,purity,rate,making_charges,status,caret,mrp,gst) VALUES ('$item','$gross_weight','$net_weight','$purity','$rate','$making_charges','$status','$caret','$mrp','$gst')";
                        if ($conn->query($sql)) {
                            echo '<br>Added row : ' . $conn->insert_id;
                        }
                    }
                } else {
                    echo "Item is not available in database.";
                }
                $count++;
            }
        }
    } else {
        echo 'select a valid Excel file';
    }
}
?>