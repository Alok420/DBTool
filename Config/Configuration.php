<?php

class Configuration {

    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    function tablesdata() {
        $tables = array(
             "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "contact" => "varchar:11:unique", "userid" => "varchar:50:unique NOT NULL", "email" => "varchar:50:unique NOT NULL", "password" => "text", "api_key" => "text", "role" => "varchar:50", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "blocked" => "int:1:default 0"),
            "vendors"=>array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "company_name" => "varchar:20","company_logo" => "varchar:20", "email_id" => "varchar:11:unique","company_contact" => "varchar:11:unique","altenative_contact" => "varchar:11:unique","address" => "varchar:500","featured" => "boolean:4"),
            "services"=>array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "service_name" => "varchar:20","service_icon" => "varchar:20","service_image" => "varchar:20","service_desc" => "varchar:20","status" => "boolean:2"),
            "location"=>array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "location_name" => "varchar:20","location_cost" => "varchar:20","status" => "varchar:20"),
            "Keyword"=>array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "keyword_name" => "varchar:20","keyword_cost" => "varchar:20"),
            "images"=>array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "image_name" => "varchar:20","image_path" => "varchar:200")
        );
        return $tables;
    }

    function tableRelation() {
        $rtable = array(
            "service_category" => "services:cascade:cascade",
            "product_category" => "product:cascade:cascade",
            "portfolio_category" => "portfolio:cascade:cascade",
            "user" => "profile:cascade:cascade",
            "user" => "projects:cascade:cascade",
            "user" => "experiences:cascade:cascade",
            "user" => "employee_detail:cascade:cascade",
            "user" => "salary:cascade:cascade",
            "employee_detail" => "salary:cascade:cascade"
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
    }

}
