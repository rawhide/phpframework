<?php

class Validate{
  public $errors = array();
  
  function not_null($name, $value, $mes = null) {
    //trim
    $value = preg_replace('/　/', '', trim($value));
    if(is_null($value) || $value === ""){
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : "not null";
    }
  }
  
  function range($name, $value, $min = 0, $max = 0, $mes = null) {
    //TODO: max = 0のときはチェックしないように
    if ((int)$min > mb_strlen($value) || (int)$max < mb_strlen($value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : "range";
    }
  }
  
  function not_equal($name, $value, $check, $mes = null) {
    if ($value !== $check) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'not equal';
    }
  }
  
  function mail_lite($name, $value, $mes = null) {
    if (!preg_match('/.+@.+/', $value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'mail';
    }
  }
  
  function contain($name, $value, $array = array(), $mes = null) {
    if (!in_array($value, $array)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'contain';
    }
  }
  
  function numeric($name, $value, $mes = null) {
    if (!preg_match("/^\d+$/", $value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'numeric';
    }
  }
  
  function alphabet($name, $value, $mes = null) {
    if (!preg_match("/^[a-zA-Z]+$/", $value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'alphabet';
    }
  }
  
  function alphanumeric($name, $value, $mes = null) {
    if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'alphanumeric';
    }
  }
  
  function regex($name, $value, $regex, $mes = null) {
    if (!preg_match("$regex", $value)) {
      if (!isset($this->errors[$name]))
        $this->errors[$name] = isset($mes) ? $mes : 'regex';
    }
  }

  function add_error($name, $mes, $force = false) {
    if (!isset($this->errors[$name]) || $force)
      $this->errors[$name] = $mes;
  }
}