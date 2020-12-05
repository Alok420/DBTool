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
        "likes" => array("id" => "int:11: PRIMARY KEY AUTO_INCREMENT", "date_time" => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP","liked_by"=>"varchar:20","personname"=>"varchar:20"),
    );
    return $tables;
  }

  /*
    To create the relation
   * (parenttable:primary_key_id(optional)=>childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key without droping foreign key column  put drop: before child table
   * (parenttable:primary_key_id(optional)=>drop:childtable:ondelete:onupdate:foreign_key_column_name(optional))
    To drop foreign key with droping foreign key column  put dropcol: before child table
   * (parenttable:primary_key_id(optional)=>dropcol:childtable:ondelete:onupdate:foreign_key_column_name(optional))
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
    $info2 = array();
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
