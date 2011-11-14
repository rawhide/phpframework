<?php

function set_flash($message, $key='notice'){
  $_SESSION['flash'] = array($key => $message);
}
 
function get_flash($key){
  $message = isset($_SESSION['flash']) && isset($_SESSION['flash'][$key]) ? $_SESSION['flash'][$key] : null;
  //session clear
  $_SESSION['flash'][$key] = null;

  return $message;
}
