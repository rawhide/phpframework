<?php

class Template {
  private $attributes = array();

  public function render($template, $options = array()){
    extract($this->attributes);
    require_once("ViewHelper.php");

    if (isset($options['layout'])) {
      $layout = VIEW_PATH . $template . ".tpl";
      include(VIEW_PATH . "layouts/" . $options['layout'] . ".tpl");
    }
    else {
      include(VIEW_PATH . $template . ".tpl");
    }
  }

  public function assign($key, $value){
    $this->attributes[$key] = $value;
  }
}

