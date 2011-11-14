<?php

function current_user() {
  global $current_user;
  if(!isset($current_user) && isset($_SESSION['login'])){
    $current_user = User::active_find($_SESSION['login']);
  }

  if($current_user){
    return $current_user;
  }else{
    return false;
  }
}

function is_login() {
  //TODO:true/falseを返す
  return current_user();
}
