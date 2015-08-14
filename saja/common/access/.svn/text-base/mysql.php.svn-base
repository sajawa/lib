<?php
/**
 * Access Mysql Object
 * @category platform
 * @package  common
 * @author   Shaun
 * @version  $Id:accessMysql.php  2011-09-02 18:00: 00 Shaun$
 * @link
 * @since    2011-09-02
 * @abstract
 */
class accessMysql extends accessAbstract{

    /**
     * Query Result
     * @access protected
     * @var $_result Object
     */
    protected $_result ;

	/**
	* connect mysql
	* @access public
	* @link mysqli
	* @return boolean
	* @example accessMysql::setConfig();
	*/
	public function connect(){

		if(empty($this->_config)){
		        log::errorLog('mysql config is empty!');
		}


		$this->_con= new mysqli($this->_config['host'], $this->_config['username'], $this->_config['password'], $this->_config['dbname']);

		if ($this->_con->connect_error) {
			log::errorLog('mysql connect error:' . $this->_con->connect_error);
			return false ;
		}

		/*
		if($this->query("SET wait_timeout = 1;")){
			log::errorLog('Set Wait_timeout fail' . $this->_con->connect_error);
			return false ;
		}

		if($this->query("SET interactive_timeout = 1;")){
			log::errorLog('set interactive time fail' . $this->_con->connect_error);
			return false ;
		}
		*/

		if(!$this->_con->set_charset($this->_config['charset'])){
			log::errorLog('mysql connect error:' . $this->_con->connect_error);
			return false ;
		}

		log::accessLog('Init accessMysql Success');
		log::returnLog(true);
		return true ;
	}

	/**
	* Query
	* @access public
	* @link self()
	* @return boolean
	* @example $this->query();
	*/
	public function query($query){

		if(!$query){
			log::errorLog('$query is empty!');
			return false ;
		}

		if(!$this->_result = $this->_con->query($query)){
			log::errorLog('$this->_con->query fail! : '.$this->_con->error.": $query");
			return false ;
		}

		log::accessLog('execute query() success');
		log::returnLog(true);
		return true  ;

	}

	/**
	* Query
	* @access public
	* @link self() , fiendMapped , whereMapped , sortMapped
	* @return array
	* @example $this->find($table, $fields = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null);
	* 參數的說明請參考 fields : fieldMapped($fields) , conditions : whereMapped($conditions) , sorts : sortMapped($sorts)
	* $table : 必填欄位 , 資料表名稱.
	* $limit : 回傳的 record 數.
	* $limitStart : 從第幾個 record  開始取資料 , 預設從 0 這個位置開始取.
	*/
	public function find($table, $fields = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null){

		$arr = "";

		if(!$table){
			log::errorLog('$table is empty!');
			return false ;
		}

		if(is_array($fields)){
			$fieldStr = $this->fieldMapped($fields);
		}
		elseif($fields == '*'){
			$fieldStr = '*';
		}
		else{
			$fieldStr = $fields ;
		}
		$limitStr = '';
		if($limit != null || $limitStart != 0){
			$limitStr = ' limit '.intval($limitStart).' , '. intval($limit);
		}

		$whereStr = '';
		if(is_array($conditions)){
			$whereStr = ' Where '.$this->whereMapped($conditions);
		}
		else{
			if($conditions != null){
				log::errorLog('$conditions format incorrect');
				return false ;
			}
		}
		$sortStr = '' ;
		if(is_array($sorts)){
			$sortStr = " Order by  ".$this->sortMapped($sorts);

		}
		else{
			if($sorts != null){
				log::errorLog('$sorts format incorrect');
				return false ;
			}
		}

		// Mysql 和 Mongo 的 欄位名稱不一致 , 所以 在 欄位進來時全部都要個別再去處理.
		if($table == 'application'){
			// conditions
			if(is_Array($conditions)){
				foreach($conditions as $ck => $cv){
					if($cv["lang"]){

					}
				}
			}
		}


		$query = "SELECT ".$fieldStr." FROM `".$table."` ";
		$query .= $whereStr;
		$query .= $sortStr;
		$query .= $limitStr;

		if($this->query($query)===false){
			log::errorLog('this->query fail!');
			return false ;
		}

		$arr=array();
		while($raw = $this->_result->fetch_assoc()){
			$arr[] = $raw ;
		}

		log::accessLog('execute find() success');
		log::returnLog($arr);

		return $arr  ;

	}

	/**
	* Insert
	* @access public
	* @link self()
	* @return "msyql insert id"
	* @example $this->insert($table, $data);
	*/
	public function insert($table,$data){

		if(!$table){
			log::errorLog('$table is empty!');
			return false ;
		}

		if(is_array($data)){
			$no = 0 ;
			$fieldStr = '';
			$valueStr = '';
			foreach($data as $dk => $dv){
				if($no != 0){
					$fieldStr .= " ,";
					$valueStr .= " ,";
				}
				else{
					$fieldStr .= " ( ";
					$valueStr .= " VALUES ( ";
				}
				$fieldStr .= "`".$dk."`";
				$valueStr .= "'".$dv."'";

				if($no == (count($data)-1)){
					$fieldStr .= " ) ";
					$valueStr .= " ) ";
				}
				$no ++ ;
			}
		}
		else{
			log::errorLog('$data format incorrect!');
			return false ;
		}


		$query = "INSERT INTO `".$table."` ".$fieldStr . " ".$valueStr ;
		if(!$this->query($query)){
			log::errorLog('accessMysql insert() fail!');
			return false ;
		}

		log::accessLog('execute insert() success');
		log::returnLog($this->_con->insert_id);

		return $this->_con->insert_id  ;

	}

	/**
	* Update
	* @access public
	* @link self()
	* @return boolean
	* @example $this->update($table, $data , $conditions);
	*/
	public function update($table,$data,$conditions){

		if(!$table){
			log::errorLog('$table is empty!');
			return false ;
		}


		if(is_array($conditions)){
			$whereStr = ' Where '.$this->whereMapped($conditions);
		}
		else{
			log::errorLog('$conditions format incorrect');
			return false ;
		}

		if(is_array($data)){
			$no = 0 ;
			$setStr = '';
			foreach($data as $dk => $dv){
				if($no != 0){
					$setStr .= " ,";
				}
				else{
					$setStr .= " SET ";
				}
				$setStr .= "`".$dk."` = '".$dv."' ";
				$no ++ ;
			}
		}
		else{
			log::errorLog('$data format incorrect!');
			return false ;
		}


		$query = "UPDATE `".$table."` ".$setStr . " ".$whereStr ;
		if(!$this->query($query)){
			log::errorLog('this->query fail!');
			return false ;
		}

		log::accessLog('execute update() success');
		log::returnLog(true);

		return true  ;
	}

	/**
	* delete
	* @access public
	* @link self()
	* @return boolean
	* @example $this->delete($table, $conditions);
	*/
	public function delete($table,$conditions){

		if(!$table){
			log::errorLog('$table is empty!');
			return false ;
		}


		if(is_array($conditions)){
			$whereStr = ' Where '.$this->whereMapped($conditions);
		}
		else{
			log::errorLog('$conditions format incorrect');
			return false ;
		}


		$query = "DELETE FROM `".$table."` ".$whereStr ;
		if(!$this->query($query)){
			log::errorLog('this->query fail!');
			return false ;
		}

		log::accessLog('execute delete() success');
		log::returnLog(true);

		return true  ;

	}

	/**
	* truncate
	* @access public
	* @link self()
	* @return boolean
	* @example $this->truncat($table);
	*/
	public function truncate($table){

		if(!$table){
			log::errorLog('$table is empty!');
			return false ;
		}


		$query = "TRUNCATE `".$table."` " ;
		if(!$this->query($query)){
			log::errorLog('this->query fail!');
			return false ;
		}

		log::accessLog('execute truncate() success');
		log::returnLog(true);

		return true  ;

	}

	/**
	* Close connect mysql
	* @access private
	* @link close()
	* @return boolean
	* @example accessMysql::close();
	*/
	public function close(){
		if(!$this->_con->close()){
			log::errorLog('close fail!');
			return false ;
		}

		log::accessLog('access close() success!');
		log::returnLog(true);
		return true  ;
	}

	/**
	* field mapped
	* @access private
	* @link fieldMapped
	* @return boolean
	* @example
	* $fields =
	* array(
	* 	'application_id' 	,  // 欄位名稱
	* 	array(
	* 		'id' => 'application_name' , // id 為欄位名稱
	* 		'asname' => 'name' , // as name 要將結果的欄位名稱修改成 name
	* 	) 	 		, // 其它欄位名稱
	* 	'apikey'		,
	* 	'secret'		,
	* 	'user_id'		,
	* 	'application_title'	,
	* 	'callback_url'		,
	* 	'language'		,
	*
	*
	* ) ;
	* accessMysql::fieldMapped($fields);
	*/
	public function fieldMapped($fields){

		if(!$fields){
			log::errorLog('$fields is empty!');
			return false ;
		}

		if(!is_array($fields)){
			log::errorLog('$fields format is incorrect');
			return false ;
		}
		else{
			$no = 0 ;
			$fieldStr = '';
			foreach($fields as $fk=>$fv){
				if($no != 0){
					$fieldStr .= ",";
				}
				if(!is_array($fv)){
					$fieldStr .= "`".$fv."`";
				}
				else{
					$fieldStr .= "`".$fv['id']."` as `".$fv["asname"]."`" ;
				}
				$no ++ ;
			}
		}
		log::accessLog('access fieldMapped() success!');
		log::returnLog($fieldStr);
		return $fieldStr;

	}

	/**
	* where mapped , 接受 , or , and  , in
	* @access public
	* @link whereMapped
	* @return boolean
	* @example
	* $conditions =
        * array(
	*
	* 	array(
	* 		''		,  // 第一個陣列不用寫 邏輯條件 , 寫了也不看它
	* 		'application_id' ,
	* 		'='		 ,
	* 		'1'
	* 	),
        * 	array(
	* 		'or' ,   // 第二個 陣列寫邏輯條件.
	* 		'application_name' , // 欄位名稱
	* 		'like' , // 判斷條件 接受 > = < >= <= 等等....
	* 		'%Plant%' // 判斷內容.
	* 	),
	* 	array(
	* 		'or',
        * 		'application_id' ,
	* 		'=',
	* 		'2'
	* 	)
	* 	,
	* 	array(
	* 		'and' , // 第二個 陣列寫邏輯條件.
        * 		array(
	*			'in' , // 第3個陣列可以填寫 IN
	* 			array(
	* 				'application_id',  // 在第4維陣列裡的第一個值填寫 欄位名稱
	* 				array( // 在第4維陣的第二個陣列填寫 值 , 為一 ARRAY
	* 				'1','2','3'
	* 				)
	* 			)
	* 		)
        * 	)
	* )
	*;
	* accessMysql::whereMapped($fields);
	*/
	public function whereMapped($conditions){

		if(!$conditions){
			log::errorLog('$conditions is empty!');
			return false ;
		}

		if(!is_array($conditions)){
			log::errorLog('$conditions format is incorrect');
			return false ;
		}
		else{
			$no = 0 ;
			$whereStr = '';

			if(is_array($conditions)){
				foreach($conditions as $fk=>$fv){
						if(!$no == 0){
							if(strtoupper($fv[1][0]) == 'IN'){
								$whereStr .= "".$fv[0]." ".$fv[1][1][0]." ".$fv[1][0]." ";
							}
							else{
								$whereStr .= " ".$fv[0]." ";
							}
						}
						else{
							if(strtoupper($fv[1][0]) == 'IN'){
								$whereStr .= "".$fv[0]." ".$fv[1][1][0]." ".$fv[1][0]." ";
							}

						}
						if(strtoupper($fv[1][0]) == 'IN'){
	                        			if(!is_array($fv[1])){
								log::errorLog("In format incorrect");
								return false;
							}
							$ino = 0 ;
							foreach($fv[1][1][1] as $fvk => $fvv){
								if($ino == 0 && (count($fv[1][1][1])-1) != 0){
									$whereStr .= "( '".$fvv."'";
								}
								elseif($ino == (count($fv[1][1][1])-1) && $ino == 0){
									$whereStr .= "('".$fvv."')";
								}
								elseif($ino == (count($fv[1][1][1])-1) ){
									$whereStr .= ", '".$fvv."')";
								}
								else{
									$whereStr .= ", '".$fvv."'";
								}
								$ino ++ ;
							}
						}
						else{
							$whereStr .= " `".$fv[1]."` ".$fv[2]." '".$fv[3]."' ";
						}

						$no ++ ;

				}
			}
		}

		log::accessLog('access whereMapped() success!');
		log::returnLog($whereStr);
		return $whereStr;

	}

	/**
	* sort mapped
	* @access public
	* @link sortMapped
	* @return boolean
	* @example
	* $sorts =
	*  array(
        *  	'application_id' => 'desc' ,  // KEY 為 欄位名稱 , VALUE 為排序方式 , 支援多個 SORT 條件.
        *  	'application_name' => 'asc' ,
	* );
	* accessMysql::sortMapped($sorts);
	*/
	public function sortMapped($sorts){

		if(!$sorts){
			log::errorLog('$sorts is empty!');
			return false ;
		}

		if(!is_array($sorts)){
			log::errorLog('$sorts format is incorrect');
			return false ;
		}
		else{
			$no = 0 ;
			$sortStr = '';
			if(is_array($sorts)){
				//print_r($sorts); exit ;
				foreach($sorts as $sk=>$sv){
					if($no == 0){
						$sortStr .= " `".$sk."` ".$sv." ";
					}
					else{
						$sortStr .= " , `".$sk."` ".$sv." ";
					}
                                	$no++ ;
				}
			}
			else{
				log::errorLog('$sorts format incorrect');
				return false ;
			}
		}

		log::accessLog('access sortMapped() success!');
		log::returnLog($sortStr);
		return $sortStr;

	}

	public function __destruct(){
		if(!$this->close()){
			log::errorLog('__destruct fail');
		}
		log::accessLog('access close() success!');
		//log::returnLog(true);
		//return true ;
	}

	public function getConn(){
	    return $this->_con;
	}

}

