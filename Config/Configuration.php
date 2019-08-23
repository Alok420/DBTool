<?php

class Configuration {

    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    function tablesdata() {
        $tables = array(
            "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "contact" => "varchar:11:unique", "userid" => "varchar:50:unique NOT NULL", "email" => "varchar:50:unique NOT NULL", "password" => "text", "api_key" => "text", "role" => "varchar:50", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "blocked" => "int:1:default 0"),
            "items" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "item" => "varchar:20"),
            "barcode" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "item" => "varchar:20", "gross_weight" => "decimal(10,2)", "net_weight" => "decimal(10,2)", "purity" => "decimal(10,2)", "purity_value" => "decimal(5,2)", "rate" => "decimal(10,2)", "making_charges" => "decimal(10,2)", "status" => "varchar:20:default 0"),
        );
        return $tables;
    }

    function tableRelation() {
        $rtable = array(
        );
        return $rtable;
    }

    function configure($create_relate = "creation", $operation = "create") {
        $db = new DB($this->conn);
        ini_set('max_execution_time', 300);
        if ($create_relate == "creation") {
            $info = $db->loadTables($this->tablesdata(), $operation);
        } else if ($create_relate == "relation") {
            $info = $db->relateTable($this->tableRelation());
        }
        return $info;
    }

}
