<?php
error_reporting( E_ALL );

/** 
* Data_Page
* Perl��Data::Page(::Navigation)��PHP�łł��B
* ���\�b�h�����@�\�����̂܂܈ڐA���܂���
* �h�L�������g���قڂ��̂܂܈ڐA���܂���:-)
* 
* ����
* 2007/06/18 ver 0.01
* 
* @author ittetsu miyazaki<ittetsu.miyazaki@gmail.com>
* @version 0.01
* @package Data
* @access public
*/
class Paginate {
    
    /**
    * �v�f�S�̂̐�
    * @access private
    * @var int
    */
    var $_total_entries;
    
    /**
    * �y�[�W������̑S�v�f��
    * @access private
    * @var int
    */
    var $_entries_per_page;
    
    /**
    * ���݂̃y�[�W�ԍ�
    * @access private
    * @var int
    */
    var $_current_page;
    
    /**
    * ���݂̃y�[�W�ɂ���ŏ��̗v�f�ԍ�
    * @access private
    * @var int
    */
    var $_first;
    
    /**
    * ���݂̃y�[�W�ɂ���Ō�̗v�f�ԍ�
    * @access private
    * @var int
    */
    var $_last;
    
    /**
    * �C���t�H���[�V�����p�̑S�y�[�W��
    * @access private
    * @var int
    */
    var $_last_page;
    
    /**
    * �i�r�Q�[�V���������N�\����
    * @access private
    * @var int
    */
    var $_pages_per_navigation = 10;
    
    /**
    * �R���X�g���N�^
    * �������͗v�f�S�̂̐��A�������̓y�[�W������̗v�f���B
    * ��O�����͏ȗ��ŁA���݂̃y�[�W���i�f�t�H���g��1�j�B
    * @access public
    * @param int $total_entries �v�f�S�̂̐�
    * @param int $entries_per_page �y�[�W������̗v�f��
    * @param int $current_page ���݂̃y�[�W��(�ȗ�����1���Z�b�g�����)
    * @return object Data_Page�I�u�W�F�N�g
    */
    function __construct ( $total_entries, $entries_per_page, $current_page = 1 ) {
        //$current_page��0�ɂȂ����Ⴄ�Ȃ�1�ɂ���΂�������Ȃ�
        if (!$current_page)
          $current_page = 1;
      
        // ��������Ȃ����̂̓G���[�ł���
        if( !preg_match("/^\d+$/", $total_entries) )    trigger_error('int only total_entries >= 0'  ,E_USER_ERROR);
        if( !preg_match("/^\d+$/", $entries_per_page) ) trigger_error('int only entries_per_page > 0',E_USER_ERROR);
        if( !preg_match("/^\d+$/", $current_page) )     trigger_error('int only current_page > 0'    ,E_USER_ERROR);
        
        // �y�[�W������̗v�f����0�Ȃ�Ă��Ƃ͂��肦��
        if( $entries_per_page == 0 ) trigger_error('',E_USER_ERROR);
        // 0�y�[�W���Ă̂����肦��
        if( $current_page == 0 ) trigger_error('',E_USER_ERROR);
        
        // �Ō�̃y�[�W�ԍ����v�Z����
        $last_page = ( $total_entries / $entries_per_page );
        if( $last_page != (int)$last_page ) $last_page = (int)$last_page + 1;
        
        // ���݂̃y�[�W���Ō�̃y�[�W�𒴂��Ă�����Ō�̃y�[�W�ɂ��킹��
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
    * �S�v�f����Ԃ��B
    * @access public
    * @return int �S�v�f��
    */
    function total_entries () {
        return $this->_total_entries;
    }
    
    /**
    * �y�[�W������̑S�v�f����Ԃ��B
    * @access public
    * @return int �y�[�W������̑S�v�f��
    */
    function entries_per_page () {
        return $this->_entries_per_page;
    }
    
    /**
    * ���݂̃y�[�W�ɂ���v�f����Ԃ��B
    * @access public
    * @return int ���݂̃y�[�W�ɂ���v�f��
    */
    function entries_on_this_page () {
        if( $this->_total_entries == 0 ){
            return 0;
        }else{
            return $this->_last - $this->_first + 1;
        }
    }
    
    /**
    * ���݂̃y�[�W�ԍ���Ԃ��B
    * @access public
    * @return int ���݂̃y�[�W�ԍ�
    */
    function current_page () {
        return $this->_current_page;
    }
    
    /**
    * �ŏ��̃y�[�W��Ԃ��Blast_page�ƑΏ̂��Ȃ����߂ɑ��݂��A���1��Ԃ��B
    * @access public
    * @return int �ŏ��̃y�[�W(1��Ԃ�)
    */
    function first_page () {
        return 1;
    }
    
    /**
    * �C���t�H���[�V�����p�̑S�y�[�W����Ԃ��B
    * @access public
    * @return int �C���t�H���[�V�����p�̑S�y�[�W��
    */
    function last_page () {
        return $this->_last_page;
    }
    
    /**
    * ���݂̃y�[�W�ɂ���ŏ��̗v�f�ԍ���Ԃ��B
    * @access public
    * @return int ���݂̃y�[�W�ɂ���ŏ��̗v�f�ԍ�
    */
    function first () {
        return $this->_first;
    }
    
    /**
    * ���݂̃y�[�W�ɂ���Ō�̗v�f�ԍ���Ԃ��B
    * @access public
    * @return int ���݂̃y�[�W�ɂ���Ō�̗v�f�ԍ�
    */
    function last () {
        return $this->_last;
    }
    
    /**
    * ���݂��Ă���ΑO�̃y�[�W�ԍ���Ԃ��B�����łȂ��ꍇ��null��Ԃ��B
    * @access public
    * @return int �O�̃y�[�W�̔ԍ�
    */
    function previous_page () {
        if ( $this->_current_page > 1 ) {
            return $this->_current_page - 1;
        }else{
            return null;
        }
    }
    
    /**
    * ���݂��Ă���Ύ��̃y�[�W�ԍ���Ԃ��B�����łȂ��ꍇ��null��Ԃ��B
    * @access public
    * @return int ���̃y�[�W�̔ԍ�
    */
    function next_page () {
        if( $this->_current_page < $this->_last_page ){
            return $this->_current_page + 1;
        }else{
            return null;
        }
    }
    
    /**
    * �����Ŏ󂯎�����z��̒����猻�݂̃y�[�W�ɂ���v�f���̃f�[�^���擾���A�z��ŕԂ��܂��B
    * @access public
    * @param array ���ƂȂ�f�[�^�̔z��
    * @return array ���݂̃y�[�W�ɂ���l�̔z��
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
    * SQL��LIMIT�Ȃǂ̒l�Ƃ��Ďg�����߂̒l��Ԃ��܂��B
    * �g�����͉��L�̂悤�ȃP�[�X
    * <code>
    * sprintf(
    *     'SELECT * FROM table ORDER BY rec_date LIMIT %s, %s',
    *     $page->skipped(),
    *     $page->entries_per_page(),
    * );
    * </code>
    * ������first()���P�����������̂��̂ł��B
    * @access public
    * @return int LIMIT�l
    */
    function skipped () {
        $skipped = $this->_first - 1;
        return ($skipped < 0) ? 0 : $skipped;
    }
    
    /**
    * �i�r�Q�[�V���������N�p�̕\�������̐ݒ�/�擾
    * @access public
    * @param int $pages_per_navigation �\������(�f�t�H���g��10)
    * @return int �\������
    */
    function pages_per_navigation($pages_per_navigation=null) {
        if( is_null($pages_per_navigation) ) return $this->_pages_per_navigation;
        $this->_pages_per_navigation = $pages_per_navigation;
    }
    
    /**
    * �i�r�Q�[�V���������N�͈̔͂�Ԃ��B
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
    * @param int $pages_per_navigation �\������(�ȗ�����$this->pages_per_navigation()���g�p����)
    * @return array �i�r�Q�[�V���������N
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
    * �i�r�Q�[�V���������N�̍ŏ��̒l
    * @access public
    * @return int �ŏ��̒l
    */
    function first_navigation_page() {
        $pages = $this->pages_in_navigation();
        return array_shift($pages);
    }

    /**
    * �i�r�Q�[�V���������N�̍Ō�̒l
    * @access public
    * @return int �Ō�̒l
    */
    function last_navigation_page() {
        $pages = $this->pages_in_navigation();
        return array_pop($pages);
    }
    
    /**
     * SQL�p��offset�l��Ԃ��Bfirst() - 1
     * @access public
     * @return int offset�l
     */
    public function offset() {
      return $this->_first - 1;
    }
    
}

