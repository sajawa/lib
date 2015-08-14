<?php
/**
 * log类
 * @category platform
 * @package  common
 * @author   阿贤
 * @version  $Id:log.php  2011-08-30 18:00: 00 江陈$
 * @link     
 * @since    2011-08-18
 */
class log {
    
    /**
     * 是否开启日志
     * @access protected
     * @var $_enabledLog Boolean
     */
    protected static $_enabledLog = true;
    /**
     * 是否开启Backtrace
     * @access protected
     * @var $_enabledBacktrace Boolean
     */
    protected static $_enabledBacktrace = true;
    /**
     * 是否开启returnLog
     * @access protected
     * @var $_enabledReturnLog Boolean
     */
    protected static $_enabledReturnLog = true;
    
    /**
     * 是否开启accessLog
     * @access protected
     * @var $_enabledAccessLog Boolean
     */
    protected static $_enabledAccessLog = true;
    /**
     * 打开日志
     * @access public
     * @link endableLog
     * @return null
     * @example log::enableLog();
     */
    public static function enableLog() {
        self::$_enabledLog = true;
    }
    /**
     * 关闭日志
     * @access public
     * @link disableLog
     * @return null
     * @example log::disableLog();
     */
    public static function disableLog() {
        self::$_enabledLog = false;
    }
    
    /**
     * 启用backtrace
     * @access public
     * @link endableBacktrace
     * @return null
     * @example log::enableBacktrace();
     */
    public static function enableBacktrace() {
        self::$_enabledBacktrace = true;
    }
    /**
     * 禁用backtrace
     * @access public
     * @link disableLog
     * @return null
     * @example log::disableBacktrace();
     */
    public static function disableBacktrace() {
        self::$_enabledBacktrace = false;
    }
    /**
     * 启用Return Log
     * @access public
     * @link enableReturnLog
     * @return null
     * @example log::enableReturnLog();
     */
    public static function enableReturnLog() {
        self::$_enabledReturnLog = true;
    }
    /**
     * 禁用Return Log
     * @access public
     * @link disableReturnLog
     * @return null
     * @example log::disableReturnLog();
     */
    public static function disableReturnLog() {
        self::$_enabledReturnLog = false;
    }
    /**
     * 启用Access Log
     * @access public
     * @link enableAccessLog
     * @return null
     * @example log::enableAccessLog();
     */
    public static function enableAccessLog() {
        self::$_enabledAccessLog = true;
    }
    /**
     * 禁用Access Log
     * @access public
     * @link disableAccessLog
     * @return null
     * @example log::disableAccessLog();
     */
    public static function disableAccessLog() {
        self::$_enabledAccessLog = false;
    }
    /**
     * 错误日志
     * @access public
     * @static
     * @link errorLog
     * @param $str String 日志内容
     * @return null
     * @example log::errorLog('foo');
     */
    public static function errorLog($str) {
        if(self::$_enabledLog){
            echo "\n";
            if(self::$_enabledBacktrace){
                debug_print_backtrace();
            }
            echo 'Result : ' . $str . "\n";
        }
    
    }
    /**
     * 访问日志
     * @access public
     * @static
     * @link errorLog
     * @param $str String 日志内容
     * @return null
     * @example log::accessLog('foo');
     */
    public static function accessLog($str = '') {
        if(self::$_enabledLog && self::$_enabledAccessLog){
            echo "\n";
            if(self::$_enabledBacktrace){
                debug_print_backtrace();
            }
            echo 'Result : ' . $str . "\n";
        }
    }
    /**
     * 访问日志
     * @access public
     * @static
     * @link errorLog
     * @param $str String 日志内容
     * @return null
     * @example log::accessLog('foo');
     */
    public static function exeCountLog($str = '') {
    
    }
    /**
     * 返回日志
     * @access public
     * @static
     * @link returnLog
     * @param $str String 日志内容
     * @return null
     * @example log::returnLog('foo');
     */
    public static function returnLog($str) {
        if(self::$_enabledLog && self::$_enabledReturnLog){
            echo "Result : ";
            print_r($str);
            echo "\n";
        }
    }

}
