<?php
error_reporting(E_ALL ||~ E_NOTICE);

include(dirname(__FILE__) . "/../config/environment.php");

$options = $_SERVER['argv'];
array_shift($options);

$type = array_shift($options);

switch($type) {
 case 'migration':
   create_migration($options);
   break;
 case 'model':
   create_model($options);
   break;
}

function create_model($opts) {
  $filename = filename($opts[0]);
  
  $text = <<<"MODEL"
<?php

class $filename extends Model {
  const TABLE = "$opts[0]";

}

MODEL;

  $fh = fopen(APP_ROOT . '/app/models/' . $filename . '.class.php', 'w');
  fwrite($fh, $text);
  fclose($fh);
  
  create_migration($opts);
}

function create_migration($opts) {
  $tablename = array_shift($opts);
  $columns = $opts;
  $column_types = column_types();
  $text = "create table $tablename (\n";
  foreach ($columns as $row) {
    list($key, $value) = explode(':', $row);
    
    $column_type = isset($column_types[$value]) ? $column_types[$value] : $value;
    
    $text .= <<<"MIGRATION"
    $key $column_type,\n
MIGRATION;
  }
  $text .= "    primary key (id)\n) ENGINE=InnoDB DEFAULT CHARSET utf8";

  $fh = fopen(APP_ROOT . '/db/migrate/tmp_' . $tablename . '.sql', 'w');
  fwrite($fh, $text);
  fclose($fh);
}

function filename($name) {
  return ucfirst(preg_replace("/_(.)/e", "strtoupper($1)", $name));
}


function column_types() {
  return array(
               'primary' => 'int(11) auto_increment',
               'integer' => 'int(11)',
               'string'  => 'varchar(255)',
               'text'    => 'text',
               'datetime' => 'timestamp',
               'timestamp' => 'timestamp'
               );
}