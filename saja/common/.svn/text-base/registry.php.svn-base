<?php
/**
 * 全局变量注册类
 * @category platform
 * @package  common
 * @author   江陈
 * @version  $Id:registry.php  2011-08-30 18:00: 00 江陈$
 * @link     
 * @since    2011-08-18
 */
class registry{
    /**
     * 变量存放容器
     * @access private
     * @static
     * @var $_var Array 
     */
    private static $_var = array();
    /**
     * 是否开启日志
     * @access private
     * @static
     * @var $_enableLog Boolean
     */
    private static $_enableLog = true;
    /**
    * 设置全局变量
    * @access public
    * @static
    * @link set
    * @param $key String 变量名
    * @param $value mixed 变量值
    * @return null
    * @example registry::set('foo','bar');
    */
    public static function set($key,$value){
        self::$_var[$key] = $value;
        if(self::$_enableLog){
            log::accessLog('access object of registry');
            log::returnLog('set global variable: ' . $key .' to assignd value successed!');
        }
    }
    /**
    * 获取全局变量
    * @access public
    * @static
    * @link get
    * @param $key String 变量名
    * @return mixed 变量值
    * @example registry::get('foo');
    */
    public static function get($key){
        if(!isset(self::$_var[$key])){
            if(self::$_enableLog){
                log::errorLog('there has no this('.$key.') global variable');
            }
            return false;
        }else{
            if(self::$_enableLog){
                log::accessLog('access object of registry');
                log::returnLog('return variable('.$key.')');
            }
            return self::$_var[$key];
        } 
    }
    /**
    * 检查变量是否存在
    * @access public
    * @static
    * @link _isset
    * @param $key String 变量名
    * @return Boolean
    * @example registry::_isset('foo');
    */

    public static function _isset($key){
        $flag = false;
        if(isset(self::$_var[$key])){
            $flag = true;
        }
        if(self::$_enableLog){
            if($flag){
                log::accessLog('access object of registry');
                log::returnLog('isset('.$key.') return true');
            }else{
                log::returnLog('isset('.$key.') return false');
            }    
        }
        return $flag;
    }
    /**
    * 开启日志
    * @access public
    * @static
    * @link enableLog
    * @return null
    * @example registry::enableLog();
    */

    public static function enableLog(){
        self::$_enableLog = true ;
    }
    /**
    * 关闭日志
    * @access public
    * @static
    * @link disableLog
    * @return null
    * @example registry::disableLog();
    */

    public static function disableLog(){
        self::$_enableLog = false ;
    }
}
