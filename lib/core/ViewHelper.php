<?php
function h($str){
  return htmlspecialchars($str, ENT_QUOTES);
}

function br($str) {
  $str = preg_replace("/\r\n/", "\n", $str);
  $str = preg_replace("/\r/", "\n", $str);
  return preg_replace("/\n/", "<br />", $str);
}

function hbr($str) {
  $str = h($str);
  return br($str);
}

function text_field($name, $value, $options = array()) {
  $attr = "";
  foreach ($options as $k => $v) {
    $attr .= "$k=\"$v\" ";
  }

  $value = h($value);
  return <<<TEXTFIELD
<input type="text" name="$name" value="$value" $attr/>
TEXTFIELD;
}

function password_field($name, $value, $options = array()) {
  $attr = "";
  foreach ($options as $k => $v) {
    $attr .= "$k=\"$v\" ";
  }

  $value = h($value);
  return <<<PASSFIELD
<input type="password" name="$name" value="$value" $attr/>
PASSFIELD;
}

function select_box($name, $values = array(), $options = array()) {
  $body = '';
  
  if (isset($options['blank']))
    $body .= "<option value=\"\">{$options['blank']}</option>\n";
  
  foreach ($values as $row) {
    $selected = isset($options['selected']) && "{$options['selected']}" == "$row[0]" ? ' selected="selected"' : '';
    $body .= <<<OPTION
<option value="$row[0]" $selected>$row[1]</option>\n
OPTION;
  }
  $js = isset($options['js']) ? $options['js'] : '';
  return <<<SELECTBOX
<select name="$name" $js>
$body
</select>
SELECTBOX;
}

function param_to_hidden_tag($name, $back = false) {
  if(!@$_REQUEST[$name]) return "";

  $data = $_REQUEST[$name];

  $hidden = '';
  foreach ($data as $key => $val) {
    $val = h($val);
    $hidden .= <<<HIDDEN
<input type="hidden" name="{$name}[$key]" value="$val" />\n
HIDDEN;
  }
  
  return $hidden;
}

function truncate($string, $maxlen=9, $suffix="..."){
  if(mb_strlen($string, 'UTF-8') > $maxlen){
    return mb_substr($string, 0, $maxlen,'UTF-8') . "...";
  } else {
    return $string;
  }
}

function null_to_nbsp($string) {
  return is_null($string) ? "&nbsp;" : $string;
}

function word_wrap($string, $wraplen=40) {
  $len = mb_strlen($string, 'UTF-8');

  if($len < $wraplen) return $string;

  $start_pos = 0;
  $end_pos = 0;
  $return_string = "";

  while($end_pos < $len) {
    $cut_string = mb_substr($string, $start_pos, $wraplen, 'UTF-8');
    $return_string .= $cut_string . "<br />";
    $start_pos += $wraplen;
    $end_pos += $wraplen;
  }
  return $return_string;
}
