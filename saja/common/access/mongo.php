<?php
/**
 * Access MongoDB Object
 * @category platform
 * @package  common
 * @author   Hoffmann
 * @version  $Id$
 * @link
 * @since    2011-09-05
 * @abstract
 */
class accessMongo extends accessAbstract{

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 27017;

    const DESC=-1;
    const ASC=1;

    /**
     * Database
     * @access protected
     * @var $_result Object
     */
    protected $_db;

    /**
     * connect MongoDB
     * @access public
     * @return boolean
     */
    public function connect(){

        if(empty($this->_config)){
            log::errorLog('MongoDB config is empty!');
        }

        $config=$this->_config;

        $host=(!empty($config['host']))? $config['host']:self::DEFAULT_HOST ;
        $port=(!empty($config['port']))? $config['port']:self::DEFAULT_PORT ;

        try {
            $this->_con=new Mongo(
            		"mongodb://$host:$port",
                    array(
                    	'user'=>$config['username'],
                    	'password'=>$config['password'],
                    	'db'=>$config['dbname'],
                       )
                    );

            //calling selectDB() is still necessary
            $this->_db=$this->_con->selectDB($config['dbname']);

            log::accessLog('Init accessMongo Success');
            log::returnLog(true);
            return true ;

        } catch (MongoConnectionException $e) {
            log::errorLog('MongoDB connect error:' . $e->getMessage());
            return false;
        }
    }

    /**
    * Close MongoDB connection
    * @access public
    * @return boolean
    */
    public function close(){
        if(!$this->_con->close()){
            log::errorLog('MongoDB close() failed!');
            return false ;
        }

        log::accessLog('MongoDB close() succeeded!');
        log::returnLog(true);
        return true  ;
    }


    /**
    * Insert for MongoDB
    * @access public
    * @return boolean
    */
    public function insert($table,$data){

        if(!$table){
            log::errorLog('$table is empty!');
            return false ;
        }

        if(!is_array($data)){
            log::errorLog('MongoDB insert() $data format incorrect!');
            return false ;
        }
        try {
            $collection=$this->_db->selectCollection($table);
            $collection->insert($data);
            log::accessLog('MongoDB insert() succeeded');
            log::returnLog(true);
            return true  ;
        } catch (Exception $e) {
            log::errorLog('MongoDB insert() error: '.$e->getMessage());
            return false ;
        }
    }

    /**
     * find a entry
     * @access public
     * @return array
     */
    public function find($table, $fields = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null){

        if(empty($limit)) $limit=0;
        if(empty($limitStart)) $limitStart=0;
        if(empty($sorts)) $sorts=array();
        if(empty($conditions)) $conditions=array();
        if(empty($fields)) $fields='*';

        if(!$table){
            log::errorLog('$table is empty!');
            return false ;
        }

        if(!is_array($fields)|| $fields==null) {
            $fields = '*';
        }

        if(!is_numeric($limit)) {
            log::errorLog('$limit incorrect');
            return false ;
        }

        if(!is_numeric($limitStart)) {
            log::errorLog('$limitStart incorrect');
            return false ;
        }

        if(!is_array($conditions) && !is_string($conditions)){
            log::errorLog('$conditions format incorrect');
            return false ;
        }

        if(!is_array($sorts) && $conditions!=null){
            log::errorLog('$sorts format incorrect');
            return false ;
        }

        $collection=$this->_db->selectCollection($table);

        $whereMapped=$this->whereMapped($conditions);
        $fieldsMapped=$this->fieldMapped($fields);
        $sortsMapped=$this->sortMapped($sorts);
        $arr=$collection->find($whereMapped,$fieldsMapped)->sort($sortsMapped)->limit($limit)->skip($limitStart);

        $arr=iterator_to_array($arr);

        log::accessLog('MongoDB find() success');
        log::returnLog($arr);

        return $arr;

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

        if(!is_array($conditions)){
            log::errorLog('$conditions format incorrect');
            return false ;
        }

        if(!is_array($data)){
            log::errorLog('$data format incorrect!');
            return false ;
        }

        $collection=$this->_db->selectCollection($table);
        $whereMapped=$this->whereMapped($conditions);

        try {
            foreach($data as $field => $newData) {
                $newObj=array('$set'=>array($field=>$newData));
                $collection->update($whereMapped,$newObj,array('multiple'=>true));
            }
            log::accessLog('execute update() success');
            log::returnLog(true);
            return true  ;
        } catch (Exception $e) {
            log::errorLog('MongoDB update() error!');
            return false ;
        }
    }

    /**
     * delete
     * @access public
     * @return boolean
     */
    public function delete($table,$conditions){

        if(!$table){
            log::errorLog('$table is empty!');
            return false ;
        }

        if(!is_array($conditions)){
            log::errorLog('$conditions format incorrect');
            return false ;
        }

        $collection=$this->_db->selectCollection($table);

        $whereMapped=$this->whereMapped($conditions);

        try {
            $collection->remove($whereMapped);
            log::accessLog('execute delete() success');
            log::returnLog(true);
            return true  ;
        } catch (Exception $e){
            log::errorLog('this->query fail!');
            return false ;
        }
    }

    /**
     * truncate
     * @access public
     * @return boolean
     */
    public function truncate($table){

        if(!$table){
            log::errorLog('$table is empty!');
            return false ;
        }

        $collection=$this->_db->selectCollection($table);
        $rv=$collection->drop();
        if($rv['ok']!=1){
            log::errorLog('mongoDB truncate() fail!');
            return false ;
        }

        log::accessLog('MongoDB execute truncate() success');
        log::returnLog(true);
        return true  ;

    }

    /**
    * convert 'where' conditional clause to Mongo parameter array
    *
    * 由於 MongoDB 和傳統 SQl database server 不同,所以只能在此轉換簡單的 query; 複雜的 query
    * 須用 mongodb 的 MongoCode 來做 (在 MongoDB 中, 只要用到 or 的都算複雜查詢。)
    *
    * 目前能轉換簡單的 全部是AND 或 OR的query  ,如 a=1 and b=2 and c=3 , 或是 a=1 or b=2 or c=3
    *
    * 其他複雜 query : 1.請修改程式邏輯，簡化 query 2.採用 MongoCollection 的 find 自行撰寫 query。
    *
    * 如:
    * 1. a=1 or (b=2 and c=3)
    * 手動撰寫成 :
    * $collection->find('a'=>1,array('$or'=> array('b'=>2, 'c'=>3))));
    *
    * 2. (a=1 or b=2) and c=3
    * 須用  javascript function 來處理:
    * $collection->find(array('$where'=>new MongoCode('function(){return((this.a==1 || this.b==2)&& this.c==3);}')));
    *
    * 3. a=1 or b=2 or a=3 and a in (1,2,3) (be warned : syntax ambiguity in this case)
    * 須用  javascript function 來處理:
    * $collection->find(array('$where'=>new MongoCode('function(){return((this.a==1 || this.b==2 || $this.a==3)&& (this.c>=1 && this.c<=3));}')));
    *
    *
    * @access 	public
    * @link		http://www.mongodb.org/display/DOCS/Advanced+Queries
    * @link 	http://www.mongodb.org/display/DOCS/OR+operations+in+query+expressions
    * @return 	array result array
	*
    */
    public function whereMapped($conditions){

        //no condition
        if(!$conditions) return array();

        //if this is a $where clause ?

        if(is_string($conditions)) {

            try {
                $whereArray=array('$where'=>new MongoCode($conditions));

                log::accessLog('access whereMapped() succeess!');
                log::returnLog(var_export($whereArray,true));
                return $whereArray;
            } catch (Exception $e) {
                log::errorLog('accessMongo $where error : '.$e->getMessage());
                return array('_id'=>false) ; //guaranteed nothing found.
            }
        }

        $tmpArray=array();
        $whereArray=array();


        //find logic operator
        if(sizeof($conditions)>1) {
             //there are more than 1 condition
            $logicalOperator=strtolower($conditions[1][0]);
        } else {
            $logicalOperator=null;
        }

        //process single conditional clause
        foreach($conditions as $cidx=>$condition) {

            //find logic operator
            if((strtolower($condition[0])!=$logicalOperator) && ($cidx>0)) {
                //there are more than 1 condition
                log::errorLog('accessMongo: Inconsistent logocal operators. whereMapped() cannot convert mixed ANDs and ORs automatically');
                return array('_id'=>false) ; //guaranteed nothing found.
            }

            $key=$condition[1];

            if($condition[2]==null) :
                //specail case for 'in'
                $key=$condition[1][1][0];
                $value=array('$in'=>$condition[1][1][1]);
            else:
                switch(strtolower($condition[2])) {
                    case '=':
                        $value=$condition[3];
                        break;
                    case '>':
                        $value=array('$gt'=> $condition[3]);
                        break;
                    case '>=':
                        $value=array('$gte' => $condition[3]);
                        break;
                    case '<':
                        $value=array('$lt' => $condition[3]);
                        break;
                    case '<=':
                        $value=array('$lte' => $condition[3]);
                        break;
                    case '<>':
                    case '!=':
                        $value=array('$ne' => $condition[3]);
                        break;
                    case 'like':
                        $str=trim($condition[3],'%');
                        if(strpos($condition[3],'%')>0)
                            $str="^$str";
                        $value=new MongoRegex('/'.$str.'/');
                        break;
                    default:
                        log::errorLog('unsupported conditional operator in accessMongo');
                        break;
                }//end of switch
            endif;

            //process logical operator
            if ($logicalOperator=='and' || $logicalOperator==null)
                $tmpArray[$key]=$value;
            elseif($logicalOperator=='or')
                $tmpArray[]=array($key=>$value);

        }//end of foreach

        //build final array
        if($logicalOperator=='and' || $logicalOperator==null)
            $whereArray=$tmpArray;
        elseif($logicalOperator=='or')
            $whereArray=array('$or'=> $tmpArray);

        log::accessLog('access whereMapped() succeess!');
        log::returnLog(var_export($whereArray,true));
        return $whereArray;
    }

    /**
    * convert sort condition to Mongo parameter array
    * @access 	public
    * @param 	mixed $sorts
    * @return 	array sorts clause array
    */
    public function sortMapped($sorts){

        if(empty($sorts)) return array();

        $sortsArray=array();
        foreach($sorts as $sort => $orderBy) {
            $sortsArray[$sort]=(strtolower($orderBy)=='asc')?
                    self::ASC : self::DESC;
        }

        log::accessLog('access sortMapped() succeess!');
        log::returnLog(var_export($sortsArray,true));
        return $sortsArray;
    }

    /**
    * convert fields to Mongo parameter array
    * @access 	public
    * @param	array $fields
    * @return 	array result array
    */
    public function fieldMapped($fields){
        if(!$fields){
            log::errorLog('$fields is empty!');
            return false ;
        }

        if(!is_array($fields)){
            if(trim($fields)=='*') return array(); //empty array means all
            log::errorLog('$fields format is incorrect');
            return false ;
        }

        //make array
        $fieldsArray=array();

        foreach($fields as $field) {

            if(is_array($field)) {
                $fieldsArray[$field['asname']]=1;
            } else {
                //empty field skipped
                $field=trim($field);
                if(empty($field))
                    continue;
                else
                    $fieldsArray[$field]=1;
            }
        }

        log::accessLog('access fieldMapped() succeess!');
        log::returnLog(var_export($fieldsArray,true));
        return $fieldsArray;

    }

    public function __destruct(){
        if(!empty($this->_con))
                $this->close();
        log::accessLog('access __destruct() success!');
    }

    public function getConn(){
        return $this->_con;
    }

    public function getDb(){
        return $this->_db;
    }

}
