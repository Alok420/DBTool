<?php
class Configuration {
    public $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    function tablesdata() {
        $tables = array(
            "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "contact" => "varchar:11:unique", "userid" => "varchar:50:unique NOT NULL", "email" => "varchar:50:unique NOT NULL", "password" => "text", "api_key" => "text", "role" => "varchar:50", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "blocked" => "int:1:default 0"),
            "service_category" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "description" => "text", "image" => "text"),
            "services" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "descriptions" => "text", "image" => "varchar:500"),
            "product_category" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "description" => "text", "image" => "text"),
            "product" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "descriptions" => "text", "image" => "varchar:500"),
            "portfolio_category" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "description" => "text", "image" => "text"),
            "portfolio" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500", "descriptions" => "text", "image" => "varchar:500"),
            "inquiry" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "contact" => "varchar:11", "email" => "varchar:30", "type" => "varchar:50", "message" => "text", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
            "profile" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "skill" => "varchar:500", "about" => "text", "image" => "varchar:500", "facebook" => "varchar:500", "twitter" => "varchar:500", "pintrest" => "varchar:500", "instagram" => "varchar:500", "youtube" => "varchar:500", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
            "projects" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:500", "expected_startdate" => "date", "expected_end_date" => "date", "actual_start_date" => "date", "actual_end_date" => "date", "cost" => "int", "domain_provider" => "varchar:50", "server_provider" => "varchar:50", "domain_userid" => "varchar:100", "domain_pass" => "varchar:50", "server_userid" => "varchar:100", "server_pass" => "varchar:50", "status" => "varchar:20", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "remarks" => "varchar:1000"),
            "experiences" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:500", "designation" => "varchar:100", "experience" => "text", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
            "employee_detail" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "joining_date" => "date", "job_type" => "varchar:30", "salary_per_month" => "int", "documents_given" => "varchar:500", "account_holder_name" => "varchar:100", "account_no" => "varchar:50", "bank_name" => "varchar:50", "bank_location" => "varchar:100", "creation_date" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "remarks" => "varchar:500")
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

    function configure($create_relate="creation", $operation = "create") {
        $db = new DB($this->conn);
        ini_set('max_execution_time', 300);
        if ($create_relate == "creation") {
            $info = $db->loadTables($this->tablesdata(), $operation);
        } else if ($create_relate == "relation") {
            $info = $db->relateTable($this->tableRelation());
        }
    }
    

}
