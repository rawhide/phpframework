<?php
error_reporting( E_ALL );

/** 
* Data_Page
* PerlのData::Page(::Navigation)のPHP版です。
* メソッド名も機能もそのまま移植しました
* ドキュメントもほぼそのまま移植しました:-)
* 
* 履歴
* 2007/06/18 ver 0.01
* 
* @author ittetsu miyazaki<ittetsu.miyazaki@gmail.com>
* @version 0.01
* @package Data
* @access public
*/
class Paginate {
    
    /**
    * 要素全体の数
    * @access private
    * @var int
    */
    var $_total_entries;
    
    /**
    * ページあたりの全要素数
    * @access private
    * @var int
    */
    var $_entries_per_page;
    
    /**
    * 現在のページ番号
    * @access private
    * @var int
    */
    var $_current_page;
    
    /**
    * 現在のページにある最初の要素番号
    * @access private
    * @var int
    */
    var $_first;
    
    /**
    * 現在のページにある最後の要素番号
    * @access private
    * @var int
    */
    var $_last;
    
    /**
    * インフォメーション用の全ページ数
    * @access private
    * @var int
    */
    var $_last_page;
    
    /**
    * ナビゲーションリンク表示数
    * @access private
    * @var int
    */
    var $_pages_per_navigation = 10;
    
    /**
    * コンストラクタ
    * 第一引数は要素全体の数、第二引数はページあたりの要素数。
    * 第三引数は省略可で、現在のページ数（デフォルトは1）。
    * @access public
    * @param int $total_entries 要素全体の数
    * @param int $entries_per_page ページあたりの要素数
    * @param int $current_page 現在のページ数(省略時は1がセットされる)
    * @return object Data_Pageオブジェクト
    */
    function __construct ( $total_entries, $entries_per_page, $current_page = 1 ) {
        //$current_pageが0になっちゃうなら1にすればいいじゃない
        if (!$current_page)
          $current_page = 1;
      
        // 数字じゃないものはエラーですよ
        if( !preg_match("/^\d+$/", $total_entries) )    trigger_error('int only total_entries >= 0'  ,E_USER_ERROR);
        if( !preg_match("/^\d+$/", $entries_per_page) ) trigger_error('int only entries_per_page > 0',E_USER_ERROR);
        if( !preg_match("/^\d+$/", $current_page) )     trigger_error('int only current_page > 0'    ,E_USER_ERROR);
        
        // ページあたりの要素数が0なんてことはありえん
        if( $entries_per_page == 0 ) trigger_error('',E_USER_ERROR);
        // 0ページってのもありえんぞ
        if( $current_page == 0 ) trigger_error('',E_USER_ERROR);
        
        // 最後のページ番号を計算する
        $last_page = ( $total_entries / $entries_per_page );
        if( $last_page != (int)$last_page ) $last_page = (int)$last_page + 1;
        
        // 現在のページが最後のページを超えていたら最後のページにあわせる
        if( $current_page > $last_page ) $current_page = $last_page;
        
        if( $total_entries == 0 ){
            $first = 0;
            $last  = 0;
        }
        else {
            $first = ( $current_page - 1 ) * $entries_per_page + 1;
            $last  = $current_page * $entries_per_page;
            if( $last > $total_entries ) $last = $total_entries;
        }
        
        $this->_total_entries    = $total_entries;
        $this->_entries_per_page = $entries_per_page;
        $this->_current_page     = $current_page;
        $this->_first            = $first;
        $this->_last             = $last;
        $this->_last_page        = $last_page;
    }
    
    /**
    * 全要素数を返す。
    * @access public
    * @return int 全要素数
    */
    function total_entries () {
        return $this->_total_entries;
    }
    
    /**
    * ページあたりの全要素数を返す。
    * @access public
    * @return int ページあたりの全要素数
    */
    function entries_per_page () {
        return $this->_entries_per_page;
    }
    
    /**
    * 現在のページにある要素数を返す。
    * @access public
    * @return int 現在のページにある要素数
    */
    function entries_on_this_page () {
        if( $this->_total_entries == 0 ){
            return 0;
        }else{
            return $this->_last - $this->_first + 1;
        }
    }
    
    /**
    * 現在のページ番号を返す。
    * @access public
    * @return int 現在のページ番号
    */
    function current_page () {
        return $this->_current_page;
    }
    
    /**
    * 最初のページを返す。last_pageと対称をなすために存在し、常に1を返す。
    * @access public
    * @return int 最初のページ(1を返す)
    */
    function first_page () {
        return 1;
    }
    
    /**
    * インフォメーション用の全ページ数を返す。
    * @access public
    * @return int インフォメーション用の全ページ数
    */
    function last_page () {
        return $this->_last_page;
    }
    
    /**
    * 現在のページにある最初の要素番号を返す。
    * @access public
    * @return int 現在のページにある最初の要素番号
    */
    function first () {
        return $this->_first;
    }
    
    /**
    * 現在のページにある最後の要素番号を返す。
    * @access public
    * @return int 現在のページにある最後の要素番号
    */
    function last () {
        return $this->_last;
    }
    
    /**
    * 存在していれば前のページ番号を返す。そうでない場合はnullを返す。
    * @access public
    * @return int 前のページの番号
    */
    function previous_page () {
        if ( $this->_current_page > 1 ) {
            return $this->_current_page - 1;
        }else{
            return null;
        }
    }
    
    /**
    * 存在していれば次のページ番号を返す。そうでない場合はnullを返す。
    * @access public
    * @return int 次のページの番号
    */
    function next_page () {
        if( $this->_current_page < $this->_last_page ){
            return $this->_current_page + 1;
        }else{
            return null;
        }
    }
    
    /**
    * 引数で受け取った配列の中から現在のページにある要素分のデータを取得し、配列で返します。
    * @access public
    * @param array 元となるデータの配列
    * @return array 現在のページにある値の配列
    */
    function splice ( $data ) {
        if( is_array($data) == false ) trigger_error('array only',E_USER_ERROR);
        $count = count($data);
        if( $count > $this->_last ) $count = $this->_last;
        if( $count == 0 ) return array();
        $first = $this->_first - 1;
        return array_slice( $data , $first , $count - $first );
    }
    
    /**
    * SQLのLIMITなどの値として使うための値を返します。
    * 使い方は下記のようなケース
    * <code>
    * sprintf(
    *     'SELECT * FROM table ORDER BY rec_date LIMIT %s, %s',
    *     $page->skipped(),
    *     $page->entries_per_page(),
    * );
    * </code>
    * 実装はfirst()より１引いただけのものです。
    * @access public
    * @return int LIMIT値
    */
    function skipped () {
        $skipped = $this->_first - 1;
        return ($skipped < 0) ? 0 : $skipped;
    }
    
    /**
    * ナビゲーションリンク用の表示件数の設定/取得
    * @access public
    * @param int $pages_per_navigation 表示件数(デフォルトは10)
    * @return int 表示件数
    */
    function pages_per_navigation($pages_per_navigation=null) {
        if( is_null($pages_per_navigation) ) return $this->_pages_per_navigation;
        $this->_pages_per_navigation = $pages_per_navigation;
    }
    
    /**
    * ナビゲーションリンクの範囲を返す。
    * <pre>
    * $total_entries        = 180;
    * $entries_per_page     = 10;
    * $pages_per_navigation = 10;
    * $current_page         = 1;
    * 
    * $pager =& new Data_Page(
    *     $total_entries,
    *     $entries_per_page,
    *     $current_page
    * );
    * $pager->pages_per_navigation($pages_per_navigation);
    * $list = $pager->pages_in_navigation();
    * #$list = array(1,2,3,4,5,6,7,8,9,10);
    * 
    * $current_page = 9;
    * $pager =& new Data_Page(
    *     $total_entries,
    *     $entries_per_page,
    *     $current_page
    * );
    * $list = $pager->pages_in_navigation($pages_per_navigation);
    * #$list = array(5,6,7,8,9,10,11,12,13,14);
    * </pre>
    * @access public
    * @param int $pages_per_navigation 表示件数(省略時は$this->pages_per_navigation()を使用する)
    * @return array ナビゲーションリンク
    */
    function pages_in_navigation($pages_per_navigation=null){

        $last_page = $this->last_page();
        if( is_null($pages_per_navigation) ) $pages_per_navigation = $this->pages_per_navigation();
        
        if( $pages_per_navigation >= $last_page ) return range($this->first_page(),$last_page);

        $prev = $this->current_page() - 1;
        $next = $this->current_page() + 1;
        $ret  = array($this->current_page());
        $i    = 0;
        
        while( count($ret) < $pages_per_navigation ){
            if( $i % 2 ){
                if( $this->first_page() <= $prev ) array_unshift($ret,$prev);
                $prev--;
            }else{
            if( $last_page >= $next ) array_push($ret,$next);
                $next++;
            }
            $i++;
        }
        
        return $ret;
    }
    
    /**
    * ナビゲーションリンクの最初の値
    * @access public
    * @return int 最初の値
    */
    function first_navigation_page() {
        $pages = $this->pages_in_navigation();
        return array_shift($pages);
    }

    /**
    * ナビゲーションリンクの最後の値
    * @access public
    * @return int 最後の値
    */
    function last_navigation_page() {
        $pages = $this->pages_in_navigation();
        return array_pop($pages);
    }
    
    /**
     * SQL用のoffset値を返す。first() - 1
     * @access public
     * @return int offset値
     */
    public function offset() {
      return $this->_first - 1;
    }
    
}

