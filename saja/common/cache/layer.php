<?php
/**
 * Cache访问工厂
 *
 * @category  Platform
 * @package   Common
 * @author   chenbin
 * @version  $Id:layer.php  2011-09-04 13:22 chenbin $
 * @link     referrer
 * @since    2011-09-04 13:22
 * @final
 */
final class cacheLayer {
    /** @var string Cache Object */
    private $_cacheObject;
    /** @static array Cache Config*/
    private static $_config;
    /** @static Object cacheLayer Object */
    private static $_instance;
    /** @static Boolean True if enable cache or false */
    private static $_enabled = true;
    
    /**
     * 创建唯一Cache实例
     * @access public
     * @static
     * @return Object cacheLayerObject
     * @example $cacheLayer = cacheLayer::_getInstance()
     */
    private static function _getInstance() {
        if(! self::$_instance instanceof cacheLayer){
            self::$_instance = new self();
            log::accessLog("Create cacheLayer instance success");
        }
        log::returnLog(self::$_instance);
        return self::$_instance;
    }
    
    /**
     * 设置Cache所用配置
     * @access public
     * @static
     * @param array $config
     * @return Boolean
     * @example
     * <p> 
     * $config = array();
     * $config['enabled'] = true;
     * $config['cacheType'] = 'memcache';
     * $config['params']['host'] = 'localhost';
     * $config['params']['port'] = '11211';
     * accessLayer::setConfig($config)
     * </p>
     */
    public static function setConfig(array $config) {
        if(empty($config)){
            log::errorLog('Config is empty');
            return false;
        }
        if(isset($config['enabled']) && $config['enabled']){
            self::$_enabled = (boolean)$config['enabled'];
        }
        self::$_config = $config;
        log::accessLog("Set cacheLayer config success");
        log::returnLog(true);
        return true;
    }
    /**
     * Enable cache
     * @access public
     * @static
     * @return void
     * @example  cacheLayer::enableCache() 
     */
    public static function enableCache() {
        self::$_enabled = true;
        log::accessLog("Cache was enabled");
    }
    /**
     * Disable cache
     * @access public
     * @static
     * @return void
     * @example  cacheLayer::disableCache() 
     */
    public static function disableCache() {
        self::$_enabled = false;
        log::accessLog("Cache was disabled");
    }
    /**
     * 获取Cache访问对象并连接Cache
     * 
     * @access public
     * @return Object cacheObject extended cacheAbstract
     * @example
     * <p>
     * $cacheObject = cacheLayer::_getInstance()->connect();
     * </p> 
     */
    private function _connect() {
        if(! $this->_cacheObject instanceof cacheAbstract){
            if(empty(self::$_config['cacheType'])){
                log::errorLog('Cache type is empty!');
                return false;
            }
            $className = 'cache' . ucfirst(strtolower(self::$_config['cacheType']));
            $cacheObject = new $className(self::$_config['params']);
            if(! $cacheObject instanceof cacheAbstract){
                log::errorLog($className . ' is not cacheAbstract instance');
                return false;
            }
            $cacheObject->connect();
            $this->_cacheObject = & $cacheObject;
            log::accessLog('Create ' . $className . ' object success');
        }
        log::returnLog($this->_cacheObject);
        return $this->_cacheObject;
    }
    
    /**
     * 获取cache唯一标识Key
     * @access public
     * @static
     * @param String $category 缓存分类
     * @param String $fun 功能块名称
     * @param String $act 动作名称
     * @param String $custom 自定义参数
     * @return String 返回唯一标识Key
     * @example
     * <p>
     * $cacheKey = cacheLayer::getKey ($layer, $category, $fun, $act, $custom);
     * </p>
     */
    public static function getKey($layer, $category, $fun, $act, $custom = null) {
        $cacheKey = sprintf("%s%s%s%s", $layer, $category, $fun, $act);
        if(! empty($custom)){
            $cacheKey .= $custom;
        }
        $cacheKey = strtolower(str_replace('_', '', $cacheKey));
        log::accessLog('Execute getKey() success');
        log::returnLog($cacheKey);
        return $cacheKey;
    }
    
    /**
     * 从缓存中读取数据
     * @access public
     * @static
     * @param String $key Cache Key
     * @return mixed
     */
    public static function read($key) {
        if(! self::$_enabled){
            log::accessLog('Cache disabled');
            log::returnLog(false);
            return false;
        }
        $data = self::_getInstance()->_connect()->read($key);
        log::accessLog('Execute read() success');
        log::returnLog($data);
        return $data;
    }
    
    /**
     * 存储数据到Cache中
     * @access public
     * @static
     * @param String $key Cache Key
     * @param mixed $data
     * @param integer $expire
     * @return Boolean
     */
    public static function write($key, $data, $expire = null) {
        if(! self::$_enabled){
            log::accessLog('Cache disabled');
            log::returnLog(false);
            return false;
        }
        $cacheResult = self::_getInstance()->_connect()->write($key, $data, $expire);
        log::accessLog('Execute write() success');
        log::returnLog($cacheResult);
        return $cacheResult;
    }
    /**
     * 从Cache中删除Key对的应数据
     * @access public
     * @static
     * @param String $key
     * @return boolean
     */
    public static function delete($key) {
        if(! self::$_enabled){
            log::accessLog('Cache disabled');
            log::returnLog(false);
            return false;
        }
        $deleteResult = self::_getInstance()->_connect()->delete($key);
        log::accessLog('Execute delete() success');
        log::returnLog($deleteResult);
        return $deleteResult;
    }
    /**
     * 关闭Cache连接
     * @access public
     * @return void
     * @example
     * <p>
     * cacheLayer::_getInstance()->close();
     * </p>
     */
    private function _close() {
        if($this->_cacheObject instanceof cacheAbstract){
            $this->_cacheObject->close();
            log::accessLog('Cache is closed');
        }else{
            log::accessLog('Cache has closed');
        }
    }
    /**
     * cacheLayer析构函数
     */
    public function __destruct() {
        $this->_close();
        log::accessLog('cacheLayer is destructed');
    }
}

