<?php
define("APP_ROOT", realpath(dirname(__FILE__) . "/../"));
define("DOMAIN", 'soundcrickets');

set_include_path(APP_ROOT."/lib/core:".APP_ROOT."/lib:".APP_ROOT."/app/models");

define("VIEW_PATH", APP_ROOT . "/app/views/");

$initializer = APP_ROOT . "/config/initializers/";
$dh = opendir($initializer);
while (($file = readdir($dh)) !== false) {
  if (is_dir($file) || preg_match('/^\..+$/',$file))
    continue;
  require_once $initializer . $file;
}
closedir();
 
function __autoload($class){
  require_once $class . ".class.php";
}

function filter($filter_name) {
  require_once APP_ROOT . "/app/filters/$filter_name.php";
}

function helper($helper_name) {
  require_once APP_ROOT . "/app/helpers/$helper_name.php";
}
