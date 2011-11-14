<?php

class Model{
  protected $attributes = array();
  protected $columns = array();

  public $errors = array();
  
  /**
   * コンストラクタ
   * 引数に連想配列を受け取ると、その値をメンバ変数に展開したオブジェクトとなる。
   */
  function __construct($args=null){
    foreach ($this->columns as $key) {
      $this->$key = null;
    }
    
    if($args){
      if(is_array($args)){
        $this->set_attributes($args);
      }
    }
  }


  /**
   *  データベース関係
   */
  static function find($id){
    $table = static::TABLE;
    return static::find_by_sql("select * from $table where id = ?", array($id));
  }

  static function find_by_sql($sql, $statement=array()){
    return new static(self::execute($sql, $statement)->fields);
  }
  
  public static function all($sql, $statement = array()) {
    $res = self::execute($sql, $statement);
    return self::fetchrow_all($res);
  }
  
  public static function count($sql, $statement = array()) {
    $res = self::execute($sql, $statement);
    return $res->RecordCount();
  }
  
  public function save($options = array()){
    $res = static::validate();
    if (!$res)
      return false;

    if($this->id){
      $this->update_timestamp();
      return $this->update($options);
    } else {
      $this->create_timestamp();
      return $this->insert($options);
    }
  }

  /*
  * validationを通さないsave(from ActiveRecord)
  */
  public function save_without_validation() {
    if($this->id){
      $this->update_timestamp();
      return $this->update();
    } else {
      $this->create_timestamp();
      return $this->insert();
    }
  }

  private function insert(){
    $keys = array();
    $values = array();
    
    foreach($this->attributes as $key => $value){
      $keys[] = $key;
      $values[] = $value;
    } 
    $table = static::TABLE;
    $sql = "insert into {$table} (". join(',', $keys) .") values (". implode(',', array_fill(0,count($values),'?')) .")";
    return $this->execute($sql, $values);
  } 
  
  //private function update($options = array()){
  private function update($options = array()){
    $force_update = isset($options['force_update']) && $options['force_update'] ? $options['force_update'] : false;



    $keys = array();
    $values = array();
    
    foreach($this->attributes as $key => $value){
      if (!isset($value) && !$force_update){
        continue;
      }
      $keys[] = "$key = ?";
      $values[] = $value;
    }
    $table = static::TABLE;
    $sql = "update $table set " . implode(',', $keys) . " where id = ?";
    $values[] = $this->id;
    return $this->execute($sql, $values);
  }

  static private function execute($sql,$statement=array()){
    return DB::connection()->execute($sql,$statement);
  }

  static private function fetchrow_all($rs) {
    $ret = array();
    if ($rs) {
      while (!$rs->EOF) {
        array_push($ret, new static($rs->fields));
        $rs->MoveNext();
      }
    }
    
    return $ret;
  }
  
  private function create_timestamp() {
    $date = date('Y-m-d H:i:s');
    $this->set_timestamp('created_at', $date);
    $this->set_timestamp('updated_at', $date);
  }

  private function update_timestamp() {
    $date = date('Y-m-d H:i:s');
    $this->set_timestamp('updated_at', $date);
  }
  
  private function set_timestamp($name, $date = null) {
    if ($this->have_column($name)) {
      $timestamp = date('Y-m-d H:i:s');
      if (!isset($date))
        $date = $timestamp;
      
      $this->$name = $date;
    }
  }
  
  /**
   * アクセッサー
   */
  public function __set($name, $value){
    if($this->have_column($name) || $name === 'id'){
      $this->attributes[$name] = $value;
    }
  }

  public function __get($name){
    if($this->have_column($name)){
      return $this->attributes[$name];
    } elseif($name == "id"){
      return isset($this->attributes["id"]) ? $this->attributes["id"] : null;
    }
  }

  private function have_column($name){
    return in_array($name, $this->columns) || isset($this->columns[$name]);
  }

  /**
   * 引数で受け取った連想配列を、メンバ変数に展開する 
   */
  public function set_attributes($args){
    foreach($args as $key => $value){
      $this->$key = $value;
    }
  }

  /**
   * 新規作成のレコードかどうかを判定する
   */
  public function is_new_record() {
    return $this->id ? false : true;
  }
}
