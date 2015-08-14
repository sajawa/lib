<?php
/**
 * Access访问工厂
 *
 * @category  Platform
 * @package   Common
 * @author   chenbin
 * @version  $Id:layer.php  2011-09-03 12:01 chenbin $
 * @link     referrer
 * @since    2011-0-03 12:01
 * @final
 */

final class accessLayer {
    /** @const String */
    const MONGO_NS = 'default';
    /** @const String */
    const MYSQL_NS = 'minor';
    /** @var string Access Object */
    private $_accessObject;
    /** @static array Access Config*/
    private static $_config;
    /** @static Object accessLayer Object */
    private static $_instances;
    /** @static array table namespace map */
    private static $_tableMapped = array(self::MYSQL_NS => array('sg_members', 'sg_subscribe', 'sg_friends'));
    /** @var string config NameSpace */
    private $_ns;
    
    /**
     * Access构造函数，定义私有构造函数的
     * 目的在于只能通过getInstance进行创建，
     * 不能在外部通过new来创建。
     * @access private     * 
     */
    private function __construct($ns) {
        $this->_ns = $ns;
    }
    
    /**
     * 创建唯一Access实例
     * @access public
     * @static
     * @param String $ns config NameSpace
     * @return Object accessLayerObject
     * @example $accessLayer = accessLayer::_getInstance()
     */
    private static function _getInstance($ns = self::MONGO_NS) {
        $ns = (string)$ns;
        if(empty($ns)){
            log::errorLog('Access config namespace is empty');
            return false;
        }
        if(! isset(self::$_instances[$ns]) || ! self::$_instances[$ns] instanceof accessLayer){
            self::$_instances[$ns] = new self($ns);
            log::accessLog("Create accessLayer($ns) instance success");
        }
        log::returnLog(self::$_instances[$ns]);
        return self::$_instances[$ns];
    }
    /**
     * 设置TableMapped
     * @access public
     * @static
     * @param array $tableMapped
     * @return void
     * @example
     * <p>
     * contentLayer::setTableMapped(array(accessLayer::MYSQL_NS=>array('sg_members', 'sg_subscribe', 'sg_friends'));
     * </p>
     */
    public static function setTableMapped(array $tableMapped) {
        self::$_tableMapped = $tableMapped;
        log::accessLog('Execute setTableMapped() success');
    }
    /**
     * 设置Access所用配置
     * @access public
     * @static
     * @param array $config
     * @return Boolean
     * @example
     * <p> 
     * $config = array();
     * $config['default']['accessType'] = 'mongo';
     * $config['default']['params']['host'] = 'localhost';
     * $config['default']['params']['port'] = '27017';
     * $config['default']['params']['username'] = 'user';
     * $config['default']['params']['password'] = 'password';
     * $config['default']['params']['dbname'] = 'dbname';
     * $config['default']['params']['charset'] = 'utf8';
     * $config['minor']['accessType'] = 'mysql';
     * $config['minor']['params']['host'] = 'localhost';
     * $config['minor']['params']['port'] = '3306';
     * $config['minor']['params']['username'] = 'user';
     * $config['minor']['params']['password'] = 'password';
     * $config['minor']['params']['dbname'] = 'dbname';
     * $config['minor']['params']['charset'] = 'utf8';
     * accessLayer::setConfig($config)
     * </p>
     */
    public static function setConfig(array $config) {
        if(empty($config)){
            log::errorLog('Config is empty');
            return false;
        }
        if(! isset($config[self::MONGO_NS])){
            log::errorLog('Mongo config is empty');
            return false;
        }
        if(! isset($config[self::MYSQL_NS])){
            log::errorLog('MySQL config is empty');
            return false;
        }
        self::$_config = $config;
        log::accessLog('Set accessLayer config success');
        log::returnLog(true);
        return true;
    }
    
    /**
     * 获取数据库访问对象并连接数据库
     * 
     * @access public
     * @return Object accessObject extended accessAbstract
     * @example
     * <p>
     * $accessObject = accessLayer::_getInstance()->_connect();
     * </p> 
     */
    private function _connect() {
        if(! $this->_accessObject instanceof accessAbstract){
            if(! isset(self::$_config[$this->_ns])){
                log::errorLog('Config namespace[' . $this->_ns . '] is empty');
                return false;
            }
            $config = self::$_config[$this->_ns];
            if(empty($config['accessType'])){
                log::errorLog('Access type is empty!');
                return false;
            }
            $className = 'access' . ucfirst(strtolower($config['accessType']));
            $accessObject = new $className($config['params']);
            if(! $accessObject instanceof accessAbstract){
                log::errorLog($className . ' is not accessAbstract instance');
                return false;
            }
            $accessObject->connect();
            log::accessLog('Create ' . $className . ' object success');
            $this->_accessObject = & $accessObject;
        }
        log::returnLog($this->_accessObject);
        return $this->_accessObject;
    }
    
    /**
     * 通过Access获取数据
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $field 栏位定义
     * @param null or Array $conditions 限制条件
     * @param null or Integer $limit 获取条数
     * @param Integer 获取条件超始值
     * @param Array $sorts 排序条件
     * @return Array Return query data
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $sorts = array();
     * $sorts['d'] = 'desc';
     * $sorts['e'] = 'asc';
     * accessLayer::find('testtable', array('a','b','c'), $conditions, 10, 0, $sorts);
     * </p>
     */
    public static function find($table, $field = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null) {
        $queryRows = self::_getInstance(self::_getTableNameSpace($table))->_connect()->find($table, $field, $conditions, $limit, $limitStart, $sorts);
        log::accessLog('Execute find() success');
        log::returnLog($queryRows);
        return $queryRows;
    }
    
    /**
     * 
     * 更新指定表的数据
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $data Update data
     * @param null or Array $conditions 更新条件
     * @return Integer Update rows number
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $data = array();
     * $data['c'] = 'd';
     * $upateNums = accessLayer::update('testtable', $data, $conditions);
     */
    public static function update($table, $data, $conditions = null) {
        $updateNums = self::_getInstance(self::_getTableNameSpace($table))->_connect()->update($table, $data, $conditions);
        log::accessLog('Execute update() success.');
        log::returnLog($updateNums);
        return $updateNums;
    }
    
    /**
     * 
     * 插入数据到指定的表
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $data Insert data
     * @return Boolean or insertId
     * @example
     * <p>
     * $data = array();
     * $data['c'] = 'd';
     * $insertResult = accessLayer::insert('testtable', $data);
     */
    public static function insert($table, $data) {
        $insertResult = self::_getInstance(self::_getTableNameSpace($table))->_connect()->insert($table, $data);
        log::accessLog('Execute insert() success.');
        log::returnLog($insertResult);
        return $insertResult;
    }
    /**
     * 
     * 从指定的表中删除数据
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $conditions 删除条件定义
     * @return Boolean or Integer 删除失败返回false,否则返回删除数量
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $deleteNums = accessLayer::delete('testtable', $conditions);
     * </p>
     */
    public static function delete($table, $conditions) {
        $deleteNums = self::_getInstance(self::_getTableNameSpace($table))->_connect()->delete($table, $conditions);
        log::accessLog('Execute delete() success');
        log::returnLog($deleteNums);
        return $deleteNums;
    }
    
    /**
     * 清空指定表的所有数据
     * @access public
     * @static
     * @param String $table 表名
     * @return Boolean True 成功,False 失败
     * @example
     * <p>
     * $truncatResult = accessLayer::truncat('testtable');
     * </p>
     */
    public static function truncate($table) {
        $truncatResult = self::_getInstance(self::_getTableNameSpace($table))->_connect()->truncate($table);
        log::accessLog('Execute truncat() success');
        log::returnLog($truncatResult);
        return $truncatResult;
    }
    
    /**
     * 获取第一行第一列数据
     * @access public
     * @static
     * @param String $table 表名
     * @param String $field 栏位定义
     * @param null or Array $conditions 限制条件
     * @param Integer 获取条件超始值
     * @param Array $sorts 排序条件
     * @return String Return query data
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $sorts = array();
     * $sorts['d'] = 'desc';
     * $sorts['e'] = 'asc';
     * accessLayer::findOne('testtable', array('a','b','c'), $conditions, 0, $sorts);
     * </p>
     */
    public static function findOne($table, $field = '*', $conditions = null, $limitStart = 0, $sorts = null) {
        $queryOne = self::_getInstance(self::_getTableNameSpace($table))->_connect()->findOne($table, $field, $conditions, $limitStart, $sorts);
        log::accessLog('Execute fetchOne success');
        log::returnLog($queryOne);
        return $queryOne;
    }
    
    /**
     * 获取第一行数据
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $fields 栏位定义
     * @param null or Array $conditions 限制条件
     * @param Integer 获取条件超始值
     * @param Array $sorts 排序条件
     * @return Array Return query data
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $sorts = array();
     * $sorts['d'] = 'desc';
     * $sorts['e'] = 'asc';
     * accessLayer::findRow('testtable', array('a','b','c'), $conditions, 0, $sorts);
     * </p>
     */
    public static function findRow($table, $fields = '*', $conditions = null, $limitStart = 0, $sorts = null) {
        $queryRow = self::_getInstance(self::_getTableNameSpace($table))->_connect()->findRow($table, $fields, $conditions, $limitStart, $sorts);
        log::accessLog('Execute fetchRow() success');
        log::returnLog($queryRow);
        return $queryRow;
    }
    
    /**
     * 获取符合指定条件的所有数据
     * @access public
     * @static
     * @param String $table 表名
     * @param Array $fields 栏位定义
     * @param null or Array $conditions 限制条件
     * @param Array $sorts 排序条件
     * @return Array Return query data
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $sorts = array();
     * $sorts['d'] = 'desc';
     * $sorts['e'] = 'asc';
     * accessLayer::findAll('testtable', array('a','b','c'), $conditions, 10, 0, $sorts);
     * </p>
     */
    public static function findAll($table, $fields = '*', $conditions = null, $limit = null, $limitStart = null, $sorts = null) {
        $queryAll = self::_getInstance(self::_getTableNameSpace($table))->_connect()->findAll($table, $fields, $conditions, $limit, $limitStart, $sorts);
        log::accessLog('Execute findAll() success');
        log::returnLog($queryAll);
        return $queryAll;
    }
    
    /**
     * 获取第一列数据
     * @access public
     * @static
     * @param String $table 表名
     * @param String $field 栏位定义
     * @param null or Array $conditions 限制条件
     * @param null or Integer $limit 获取条数
     * @param Integer 获取条件超始值
     * @param Array $sorts 排序条件
     * @return Array Return query data
     * @example
     * <p>
     * $conditions  = array(
     * array{'','a', '=', 'b'),
     * array('or', 'c', '>', 'd'),
     * array('or', 'e', '<=', 'f')     
     * )
     * }
     * $sorts = array();
     * $sorts['d'] = 'desc';
     * $sorts['e'] = 'asc';
     * accessLayer::findCol('testtable', array('a','b','c'), $conditions, 10, 0, $sorts);
     * </p>
     */
    public static function findCol($table, $field = '*', $conditions = null, $limit = null, $limitStart = 0, $sorts = null) {
        $queryCol = self::_getInstance(self::_getTableNameSpace($table))->_connect()->findCol($table, $field, $conditions, $limit, $limitStart, $sorts);
        log::accessLog('Access findCol() success');
        log::returnLog($queryCol);
        return $queryCol;
    }
    /**
     * 直接放置查询
     * @access public
     * @static
     * @param String $query
     * @return mixed
     * @example
     * <p>
     * accessLayer::query("SELECT * FROM sg_application");
     * </p>
     */
    public static function query($query, $accessType = self::MONGO_NS) {
        $result = self::_getInstance($accessType)->_connect()->query($query);
        log::accessLog('Access query() success');
        log::returnLog($result);
        return $result;
    }
    /**
     * 
     * 获取表所在的NameSpace
     * @access public
     * @static
     * @param String $table 表名
     * @return String
     * @example
     * <p>
     * $ns = self::_getTableNameSpace($table);
     * </p>
     */
    private static function _getTableNameSpace($table) {
        $table = trim($table);
        $ns = self::MONGO_NS;
        if(in_array($table, self::$_tableMapped[self::MYSQL_NS])){
            $ns = self::MYSQL_NS;
        }
        log::returnLog($ns);
        return $ns;
    }
    /**
     * 直接返回DBO
     * @access public
     * @static
     * @param String $accessType
     * @return Object
     * @example
     * <p>
     * $accessType = accessLayer::MONGO_NS;
     * $ns = accessLayer::getDbo($accessType);
     * </p>
     */
    public static function getDbo($accessType = self::MONGO_NS) {
        if($accessType === self::MONGO_NS){
            $dbo = self::_getInstance($accessType)->_connect()->getDb();
        }else{
            $dbo = self::_getInstance($accessType)->_connect()->getConn();
        }
        return $dbo;
    }
}
?>
