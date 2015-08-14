<?php
/**
 * access抽像类
 * @category platform
 * @package  common
 * @author   Shaun
 * @version  $Id:accessAbstract.php  2011-08-30 18:00: 00 Shaun$
 * @link
 * @since    2011-09-02
 * @abstract
 */
abstract class accessAbstract{

        /**
         * Connection Object
         * @access protected
         * @var $_conn Object
         */
        protected $_con ;

        /**
         * Config
         * @access protected
         * @var $_config array
         */
        protected $_config ;

        /**
        * __construct
        * @access public
        * @link none
        * @return none
        * @example  new $className(array $config) ;
        */
        public function __construct($config){
		if(!isset($config) && !is_array($config)){
            		log::errorLog('Config is empty');
			return false ;
		}
		$this->_config = $config ;
        }


	public abstract function connect() ;
	public abstract function close() ;
	public abstract function find($table, $fields = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null) ;
	public abstract function insert($table,$data) ;
	public abstract function update($table,$data,$conditions) ;
	public abstract function delete($table,$conditions) ;
	public abstract function truncate($table) ;

        /**
        * 取得資料表裡的一筆資料的內容
        * @access public
        * @link find()
        * @return array
        * @example  $obj->findOne() ;
        */
	public function findOne($table, $field, $conditions = null, $limitStart = 0, $sorts = null){

		if(!$field){
            		log::errorLog('$field is empty');
			return false ;
		}

		$arr = $this->find($table, $field, $conditions, 1, $limitStart, $sorts);

		if($arr===false){
        	log::errorLog('execute $this->find() fail');
			return false ;
		}

		if(defined($arr[0][$field]))
		    $rv=$arr[0][$field];
	    else
	        $rv=null;

		log::accessLog('execute findOne success');
		log::returnLog($rv);
		return $rv;
	}


        /**
        * 取得資料表裡的所有資料
        * @access public
        * @link find()
        * @return array
        * @example  $obj->findAll() ;
        */
	public function findAll($table, $fields = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null){

	    $arr = $this->find($table, $fields, $conditions, $limit, $limitStart, $sorts);
		if($arr===false){
		    log::errorLog('execute $this->find() fail');
			return false ;
		}
		log::accessLog('execute findAll success');
		log::returnLog($arr);
		return $arr ;


	}

        /**
        * 取得資料表裡的一筆資料
        * @access public
        * @link find()
        * @return array
        * @example  $obj->findRow() ;
        */
	public function findRow($table, $fields = '*', $conditions = null, $limitStart = 0, $sorts = null){

	    $arr = $this->find($table, $fields, $conditions, 1, $limitStart, $sorts);
		if($arr===false){
            log::errorLog('execute $this->find() fail');
			return false ;
		}

		log::accessLog('execute findRow success');
		log::returnLog($arr[0]);
		return $arr[0] ;
	}

        /**
        * 取得資料表裡的其中一個欄位的所有內容.
        * @access public
        * @link find()
        * @return array
        * @example  $obj->findCol() ;
        */
	public function findCol($table, $field, $conditions = null, $limit = null, $limitStart = 0, $sorts = null){


		if(!$field){
            log::errorLog('$field is empty');
			return false ;
		}

		$colArr=array();

		$arr = $this->find($table, $field, $conditions, $limit, $limitStart, $sorts);
		if($arr===false){
            log::errorLog('execute $this->find() fail');
			return false ;
		}
		else{
			foreach($arr as $ak => $av){
				$colArr[] = $av[$field] ;
			}
		}

		log::accessLog('execute findCol success');
		log::returnLog($colArr);
		return $colArr ;


	}




}
