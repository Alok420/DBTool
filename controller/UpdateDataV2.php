<?php

include '../Config/ConnectionObjectOriented.php';
include '../Config/DB.php';
$db = new DB($conn);
if (isset($_REQUEST["id"])) {

    $tablename = $_REQUEST["tbname"];
    $id = $_REQUEST["id"];
    $column = $_REQUEST["column"];
    $value = $_REQUEST["value"];
    unset($_REQUEST["id"]);
    unset($_REQUEST["column"]);
    unset($_REQUEST["tbname"]);
    $proformas = $db->select("proforma", "*", array("id" => $id));
    $proforma = $proformas->fetch_assoc();
    if ($column == "client_approval") {
        if ($proforma["client_approval"] == "client not approve" && $value == "client approve") {
            $info = $db->update(array("client_approval" => $_REQUEST["value"]), $tablename, $id);
            $proforma_product = $db->select("proforma_product", "*", array("proforma_id" => $id));
            while ($pp = $proforma_product->fetch_assoc()) {
                $quantity = $pp["quantity"];
                $product_id = $pp["products_id"];
                $query = "update products set quantity=quantity-$quantity where id=$product_id";
                $info = $conn->query($query);
            }
        } else if ($proforma["client_approval"] == "client approve" && ($value == "client not approve" || $value == "client cancel")) {
            $info = $db->update(array("client_approval" => $_REQUEST["value"]), $tablename, $id);
            $proforma_product = $db->select("proforma_product", "*", array("proforma_id" => $id));
            while ($pp = $proforma_product->fetch_assoc()) {
                $quantity = $pp["quantity"];
                $product_id = $pp["products_id"];
                $query = "update products set quantity=quantity+$quantity where id=$product_id";
                $info = $conn->query($query);
            }
        }
    }
    if ($info[0] == 1) {
        echo 'Success';
    } else {
        echo "Failed";
        var_dump($info);
    }
}