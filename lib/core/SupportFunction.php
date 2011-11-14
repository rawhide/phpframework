<?php
/*
  common functions
*/

/*
   変数がsetされていればtrue
*/
function is_presence($var) {
  //TODO:is_a?をつかって配列に対応させる。
  if(isset($var) && $var && $var != "") {
    return true;
  } else {
    return false;
  }
}

/*
  変数がsetされていない場合にtrue
*/
function is_blank($var) {
  return !is_presence($var);
}
