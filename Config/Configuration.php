<?php

class Configuration {

  public $conn;
  public $apikeyauthconsent = "AIzaSyDvNAqBSoNZbm6msvhz20sr6uoI7h73S2o";
  public $clientid = "1086193827988-o0pcar678li9fn9lufb35ane3qb7en1v.apps.googleusercontent.com";
  public $client_secret = "MbFllpafRM2vMNbnwd8cYuPZ";

  public function __construct($conn) {
    $this->conn = $conn;
  }

  /*
    create_relate ="creation/relation"
    operation="change/drop" add :drop before table name  to drop table or add : newname to rename after table
    to drop the column set drop: before column and change operation =drop
   */

  function tablesdata() {
    $tables = array(
        "user" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20:default 'NA'", "contact" => "varchar:11:unique", "userid" => "varchar:100:unique NOT NULL default 'NA'", "email" => "varchar:50:unique NOT NULL default 'NA'", "password" => "text", "api_key" => "text", "role" => "varchar:50", "blocked" => "int:1:default 0", "image" => "varchar:100", "about" => "varchar:200"),
        "jobs" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:50", "skills" => "varchar:30", "number_of_vacancies" => "int", "salary" => "int", "job_type" => "varchar:30", "salary_unit" => "varchar:10", "package_duration" => "varchar:10", "description" => "text", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "eng_buddy_hr_approval" => "int:1:default 0", "super_admin_approval" => "int:1:default 0"),
        "contact_us" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:50", "email" => "varchar:30", "message" => "text", "contact" => "varchar:11", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "article_category" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500"),
        "job_category" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:500"),
        "articles" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "title" => "varchar:1000", "like" => "int", "image" => "varchar:500", "views" => "int", "description" => "text", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "share_count" => "int", "tags" => "varchar:1000", "tech_admin_approval" => "int:0", "tech_super_admin_approval" => "int:0", "tags" => "text"),
        "client_hr_profile" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "company_name" => "varchar:100", "company_contact_number" => "varchar:12", "image" => "varchar:200", "local_address" => "varchar:500", "zip_code" => "varchar:10", "city" => "varchar:100", "country" => "varchar:50", "country_code" => "varchar:10", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "contact_person_name" => "varchar:50", "contact_person_cell" => "varchar:12", "contact_person_email" => "varchar:50", "description" => "text", "created_by_id" => "int"),
        "user_profile" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "resume" => "varchar:100", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "subscription" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "emaill" => "varchar:20:unique not null", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "ad_places" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "cost" => "int", "cost_unit" => "varchar:20", "status" => "varchar:20", "ads_id" => "int", "gst" => "float", "remarks" => "varchar:200", "offer_percentage" => "float"),
        "ads" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:20", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "image" => "varchar:20", "ad_text" => "varchar:100", "start_date" => "varchar:20", "end_date" => "varchar:20", "url" => "varchar:500", "status" => "varchar:20"),
        "comments" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "content" => "text", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "user_like" => "int", "status" => "varchar:20"),
        "reply" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "content" => "text", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "user_like" => "int", "status" => "varchar:20"),
        "job_application" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "status" => "varchar:20:default Not checked", "eng_buddy_hr_permission" => "varchar:20:default approve"),
        "invoice" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "total" => "float", "gst" => "float", "extra" => "float", "remarks" => "varchar:200", "payment_status" => "varchar:10"),
        "likes" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "pages" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "page" => "varchar:50:unique not null default 'NA'", "tags" => "text", "description" => "text", "author" => "varchar:20"),
        "searches" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "search_key" => "varchar:50", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "contacts" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "name" => "varchar:50", "email" => "varchar:50", "contact" => "varchar:15", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP"),
        "unsubscription" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "feedback" => "varchar:500", "other" => "varchar:500", "email" => "varchar:50", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP")
    );
    return $tables;
  }

  /*
    To create the relation
      *(parenttable:primary_key_id(optional)=>childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key without droping foreign key column  put drop: before child table
      *(parenttable:primary_key_id(optional)=>drop:childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key with droping foreign key column  put dropcol: before child table
      *(parenttable:primary_key_id(optional)=>dropcol:childtable:ondelete:onupdate:foreign_key_column_name(optional))
   */

  function tableRelation() {
    $rtable = array(
        "job_category" => "jobs:cascade:cascade",
        "user" => "jobs:cascade:cascade",
        "article_category" => "articles:cascade:cascade",
        "user" => "articles:cascade:cascade",
        "user" => "recruiters_profile:cascade:cascade",
        "client_hr_profile" => "jobs:cascade:cascade",
        "user" => "jobs:cascade:cascade",
        "user" => "client_hr_profile:cascade:cascade",
        "user" => "ads:cascade:cascade",
        "user" => "comments:cascade:cascade",
        "articles" => "comments:cascade:cascade",
        "comments" => "reply:cascade:cascade",
        "user" => "reply:cascade:cascade",
        "reply" => "reply:cascade:cascade",
        "jobs" => "job_application:cascade:cascade",
        "user" => "job_application:cascade:cascade",
        "user" => "user_profile:cascade:cascade",
        "ad_places" => "ads:cascade:cascade",
        "ads" => "invoice:cascade:cascade",
        "user" => "invoice:cascade:cascade",
        "user" => "likes:cascade:cascade",
        "articles" => "likes:cascade:cascade",
        "user" => "contacts:cascade:cascade",
    );
    return $rtable;
  }

  function configure($create_relate = "creation", $operation = "change") {
    $info2=array();
    $db = new DB($this->conn);
    ini_set('max_execution_time', 300);
    if ($create_relate == "creation") {
      $info = $db->loadTables($this->tablesdata(), $operation);
      array_push($info2, $info);
      array_push($info2, $this->tablesdata());
    } else if ($create_relate == "relation") {
      $info = $db->relateTable($this->tableRelation());
      array_push($info2, $info);
      array_push($info2, $this->tableRelation());
    }
    return $info2;
  }

  function loadPages() {
    
  }

}
