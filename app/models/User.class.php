<?php

error_reporting(E_ALL);

class User extends Model {
  const TABLE = "users";
  const ADMIN = "admin";
  const USER  = "user";
  const MANAGER = 1;
  const APPLICANT = 2;
  
  protected $columns = array(
                             'login',
                             'password',
                             'authority_group_id',
                             'mail',
                             'name',
                             'typecode',
                             'created_at',
                             'updated_at',
                             'deleted_at'
                             );

  public $base_password;
  public $password_confirm;
  public $have_functions = array();
  public $skip = array();
  
  /**
   * バリデーション
   * 
   * @access public
   * @return boolean
   **/
  public function validate() {
    $v = new Validate();

    if (!in_array('login', $this->skip)) {
      $v->not_null('login', $this->login, 'ログインIDを入力してください');
      $v->alphanumeric('login', $this->login, 'ログインIDは半角英数字で入力してください');
      $v->range('login', $this->login, 4, 10, 'ログインIDは4文字から10文字で入力してください');
      
      $user = User::find_by_sql('select * from users where login = ? and deleted_at is NULL', array($this->login));
      if ($user->id)
        $v->add_error('login', '入力されたログインIDはすでに登録されています');
    }
    
    if (!in_array('password', $this->skip)) {
      $v->not_null('password', $this->base_password, 'パスワードを入力してください');
      $v->alphanumeric('password', $this->base_password, 'パスワードは半角英数字で入力してください');
      $v->range('password', $this->base_password, 6, 255, 'パスワードは6文字以上で入力してください');
    }
    
    if (!in_array('password_confirm', $this->skip)) {
      $v->not_null('password_confirm', $this->password_confirm, 'パスワード確認を入力してください');
      $v->alphanumeric('password_confirm', $this->password_confirm, 'パスワード確認は半角英数字で入力してください');
      $v->range('password_confirm', $this->password_confirm, 6, 255, 'パスワード確認は6文字以上で入力してください');
      $v->not_equal('password_confirm', $this->password_confirm, $this->base_password, 'パスワードとパスワード確認の内容が違います');
    }
    
    if (!in_array('name', $this->skip)) {
      $v->not_null('name', $this->name, '名前を入力してください');
      $v->range('name', $this->name, 0, 255, '名前は255文字以内で入力してください');
    }
   
    if (!in_array('email', $this->skip)) { 
      $v->not_null('mail', $this->mail, 'メールアドレスを入力してください');
      $v->mail_lite('mail', $this->mail, 'メールアドレスの形式が不正です');
    }

  
    if (!in_array('typecode', $this->skip)) { 
      $v->contain('typecode', $this->typecode, array(User::ADMIN, User::USER), '不正な区分です');
    }

    $this->errors = $v->errors;
    return empty($this->errors) ? true : false;
  }
  
  /**
   * 管理者チェック
   * 
   * @access public
   * @return boolean
   **/
  public function is_admin() {
    return strtolower($this->typecode) === $this::ADMIN;
  }

  /**
   * 申請者チェック
   * 
   * @access public
   * @return boolean
   **/
  public function is_user() {
    return strtolower($this->typecode) === $this::USER;
  }

  /**
   * saveメソッド(overwrite)
   * 
   * @access public
   * @return boolean
   **/
  public function save() {
    //$this->base_password = $this->password;
    //$this->password = User::encrypt_password($this->password);
    
    if($this->base_password) $this->password = User::encrypt_password($this->base_password);
    if($this->typecode) $this->authority_group_id = $this->authority_for_typecode();
    
    return parent::save();
  }
  
  /**
   * updateメソッド(overwrite)
   * 
   * @access public
   * @return boolean
   */

  
  public function is_permitted($function) {
    if (empty($this->have_functions)) {
      $res = AuthorityGroupFunction::all("select function_name from authority_groups inner join authority_groups_functions on name = authority_group_name where id = ?", array($this->authority_group_id));
      foreach ($res as $row) {
        array_push($this->have_functions, $row->function_name);
      }
    }
    return in_array($function, $this->have_functions);
  }
  
  /*
   * static method
   */
  
  /**
   * loginとencrypt_passwordを元に検索
   * 
   * @access public
   * @params string login
   * @params string password
   * @return object User
   **/
  public function find_login($login, $encrypt_password) {
    return parent::find_by_sql('select * from users where login = ? and password = ? and deleted_at is NULL', array($login, $encrypt_password));
  }
  
  /**
   * ログイン用
   * 
   * @access public
   * @params string login
   * @params string encrypt_password
   * @return object User
   **/
  public function login($login, $password) {
    $password = User::encrypt_password($password);
    return User::find_login($login, $password);
  }
  
  /**
   * パスワード暗号化
   * 
   * @access public
   * @params string password
   * @return string hash value
   **/
  public static function encrypt_password($password) {
    return sha1($password);
  }
  
  public function authority_for_typecode() {
    if (!$this->typecode)
      return null;
    
    if ($this->typecode == User::USER)
      return User::APPLICANT;
    if ($this->typecode == User::ADMIN)
      return User::MANAGER;
    return null;
  }

  public static function find_all() {
    return User::all("select * from users where deleted_at is NULL");
    //TODO:deleted_at対応 return User::all("select * from users where deleted_at is null");
  }

  public static function active_find($id){
    return self::find_by_sql("select * from users where deleted_at is NULL and id = ? ", $id);
  }

  public function display_created_at(){
    return date('Y-m-d H:i', strtotime($this->created_at));
  }

  public function display_updated_at(){
    return date('Y-m-d H:i', strtotime($this->updated_at));
  }

  public function display_typecode(){
    if($this->typecode == User::USER) {
      return "申請者";
    }
     
    if($this->typecode == User::ADMIN) {
      return "管理者";
   }
  }

  public function is_deleted() {
    if($this->deleted_at) {
      return true;
    }else{
      return false;
    }
  }

}
