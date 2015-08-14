<?php
/**
 * Config访问接口
 *
 * @category  Platform
 * @package   Common
 * @author   chenbin
 * @version  $Id:config.php  2011-09-20 15:31 chenbin $
 * @link     referrer
 * @since    2011-09-20 15:31
 * @final
 */

final class config {
    /** @staticvar array save config var*/
    private static $_config;
    /**
     * load config from array or file
     * @access public
     * @static
     * @param array or string $config string if config is file or array
     * @return boolean
     * @example
     * <p>
     * $config = '/var/www/platform/config.php';
     * config::load($config);
     * or
     * $config = array();
     * $config['cache']['params'] = array('host'=>'localhost', 'port'=>'11211');
     * config::load($config);
     * </p>
     */
    public static function load($config) {
        if(! is_array(self::$_config)){
            if(is_array($config)){ //如果配置直接以一个数组传递进来，就直接读取
                if(count($config)){
                    self::$_config = &$config;
                }else{
                    log::errorLog('application config error!');
                    return false;
                }
            }else if(is_file($config)){
                $config = include ($config);
                if(is_array($config) && count($config)){
                    self::$_config = &$config;
                }else{
                    log::errorLog('application config error!');
                    return false;
                }
            }else{
                log::errorLog('application config error!');
                return false;
            }
        }
        log::accessLog('config load successful');
        log::returnLog(true);
        return true;
    }
    /**
     * load config from db
     * @access public
     * @static
     * @return boolean
     * @example
     * <p>
     * config::loadDbConfig();
     * </p>
     */
    public static function loadDbConfig() {
        $cacheConfig = self::get('cache');
        if($cacheConfig){
            //从cache中读取dbConfig,如中没有命中，则继续从DB中读取
            cacheLayer::setConfig($cacheConfig);
            $dbConfigCacheKey = cacheLayer::getKey('co', 'platform', 'config', 'loadDbConfig');
            $dbConfig = cacheLayer::read($dbConfigCacheKey);
            if($dbConfig){
                self::$_config = array_merge_recursive(self::$_config, $dbConfig);
                log::accessLog('Execute loadDbConfig() successful(from cache)');
                log::returnLog(true);
                return true;
            }
        }
        $mongoConfig = self::get('database.default.params');
        if(is_array($mongoConfig) && count($mongoConfig)){
            //从mongo Db中读取config
            $accessMongo = new accessMongo($mongoConfig);
            $accessMongo->connect();
            $dbo = $accessMongo->getDb();
            $connection = $dbo->selectCollection('sg_configuration');
            $configures = iterator_to_array($connection->find(array('published' => 1), array('name', 'value', 'default_value')));
            $config = array();
            foreach($configures as $configure){
                if(is_numeric($configure['default_value'])){
                    if(! is_numeric($configure['value'])){
                        $config[$configure['name']] = $configure['default_value'];
                    }else{
                        $config[$configure['name']] = $configure['value'];
                    }
                }else{
                    if(empty($configure['value'])){
                        $config[$configure['name']] = $configure['default_value'];
                    }else{
                        $config[$configure['name']] = $configure['value'];
                    }
                }
            }
            if(isset($dbConfigCacheKey)){
                cacheLayer::write($dbConfigCacheKey, $config);
            }
            self::$_config = array_merge_recursive(self::$_config, $config);
            log::accessLog('Execute loadDbConfig() successful(from mongo db)');
            log::returnLog(true);
            return true;
        }else{
            log::errorLog('mongo config error');
            return false;
        }
    }
    /**
     * get config
     * @access public
     * @static
     * @param string $name
     * @return mixed
     * @example
     * <p>
     * $name = 'database.params';
     * $params = config::get($name);
     * </p>
     */
    public static function get($name = null) {
        if(empty($name)){
            return self::$_config;
        }
        $segments = explode('.', $name);
        if(1 < count($segments)){
            $config = self::$_config;
            foreach($segments as $segment){
                if(isset($config[$segment])){
                    $config = $config[$segment];
                }else{
                    log::errorLog('config item(' . $name . ') not found');
                    return false;
                }
            }
            return $config;
        }else{
            if(isset(self::$_config[$name])){
                return self::$_config[$name];
            }else{
                log::errorLog('config item(' . $name . ') not found');
                return false;
            }
        }
    }
    /**
     * set config
     * @access public
     * @static
     * @param string $name
     * @param mixed $value
     * @example
     * <p>
     * $name = 'database.params';
     * $value = array('host'=>'localhost', 'password'=>'xxx');
     * config::set($name, $value);
     * </p>
     */
    public static function set($name, $value) {
        $segments = explode('.', $name);
        if(1 < count($segments)){
            $config = & self::$_config;
            for($i = 0, $n = count($segments); $i < $n; $i ++){
                $segment = & $segments[$i];
                if($i === $n - 1){
                    $config[$segment] = $value;
                }else{
                    $config[$segment] = array();
                }
                $config = & $config[$segment];
            }
        }else{
            self::$_config[$name] = $value;
        }
        log::accessLog('config set[' . $name . '] success');
    }
}