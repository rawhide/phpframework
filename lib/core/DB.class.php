<?php
require "core/adodb5/adodb.inc.php";
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

class DB{
  public static function connection(){
    static $db = null;
    if ( $db == null ) {
      $c = parse_ini_file(APP_ROOT . "/config/database.ini");
      
      $db = NewADOConnection($c['database']);
      $db->pconnect($c['host'], $c['user'], $c['password'], $c['schema']);

      if (!$db) die("接続に失敗しました");
    }
    return $db;
  }
}
