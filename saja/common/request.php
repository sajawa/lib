<?php
/**
 * 请求数据处理
 *
 * @category  Platform
 * @package   Common
 * @author   chenbin
 * @version  $Id:request.php  2011-09-23 16:35 chenbin $
 * @link     referrer
 * @since    2011-09-23 16:35
 */
class request {
    /** @const int */
    const NOTRIM = 1;
    /** @const int */
    const ALLOWRAW = 2;
    /** @const int */
    const ALLOWHTML = 4;
    
    /**
     * 从Request中获取变量值
     * @access public
     * @static
     * @param string $name var name
     * @param mixed $default default value
     * @param hash $hash hash type
     * @param string $type var type
     * @param int $mask mask value
     * @return mixed
     */
    public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0) {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
        if($hash === 'METHOD'){
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $type = strtoupper($type);
        $sig = $hash . $type . $mask;
        
        // Get the input hash
        switch($hash){
            case 'GET':
                $input = &$_GET;
                break;
            case 'POST':
                $input = &$_POST;
                break;
            case 'FILES':
                $input = &$_FILES;
                break;
            case 'COOKIE':
                $input = &$_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                $hash = 'REQUEST';
                break;
        }
        if(isset($GLOBALS['_SREQUEST'][$name]['SET.' . $hash]) && ($GLOBALS['_SREQUEST'][$name]['SET.' . $hash] === true)){
            // Get the variable from the input hash
            $var = (isset($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
            $var = self::_cleanVar($var, $mask, $type);
        }elseif(! isset($GLOBALS['_SREQUEST'][$name][$sig])){
            if(isset($input[$name]) && $input[$name] !== null){
                // Get the variable from the input hash and clean it
                $var = self::_cleanVar($input[$name], $mask, $type);
                // Handle magic quotes compatability
                if(get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')){
                    $var = self::_stripSlashesRecursive($var);
                }
                $GLOBALS['_SREQUEST'][$name][$sig] = $var;
            }elseif($default !== null){
                // Clean the default value
                $var = self::_cleanVar($default, $mask, $type);
            }else{
                $var = $default;
            }
        }else{
            $var = $GLOBALS['_SREQUEST'][$name][$sig];
        }
        
        return $var;
    }
    /**
     * 获取整型值
     * @access public
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash hash type
     * @return mixed;
     */
    public static function getInt($name, $default = 0, $hash = 'default') {
        return self::getVar($name, $default, $hash, 'int');
    }
    /**
     * 获取浮点型值
     * @access public
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash hash type
     * @return mixed;
     */
    public static function getFloat($name, $default = 0.0, $hash = 'default') {
        return self::getVar($name, $default, $hash, 'float');
    }
    /**
     * 获取Boolean值
     * @access public
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash hash type
     * @return mixed;
     */
    public static function getBool($name, $default = false, $hash = 'default') {
        return self::getVar($name, $default, $hash, 'bool');
    }
    /**
     * 获取字串值
     * @access public
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash hash type
     * @return mixed
     */
    public static function getString($name, $default = '', $hash = 'default', $mask = 0) {
        // Cast to string, in case ALLOWRAW was specified for mask
        return (string)self::getVar($name, $default, $hash, 'string', $mask);
    }
    /**
     * 修改指定值
     * @access public
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash hash type
     * @param boolean 
     * @return mixed
     */
    public static function setVar($name, $value = null, $hash = 'method', $overwrite = true) {
        //If overwrite is true, makes sure the variable hasn't been set yet
        if(! $overwrite && array_key_exists($name, $_REQUEST)){
            return $_REQUEST[$name];
        }
        
        // Clean global request var
        $GLOBALS['_SREQUEST'][$name] = array();
        
        // Get the request hash value
        $hash = strtoupper($hash);
        if($hash === 'METHOD'){
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        
        $previous = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;
        
        switch($hash){
            case 'GET':
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST':
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE':
                $_COOKIE[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES':
                $_FILES[$name] = $value;
                break;
            case 'ENV':
                $_ENV['name'] = $value;
                break;
            case 'SERVER':
                $_SERVER['name'] = $value;
                break;
        }
        
        // Mark this variable as 'SET'
        $GLOBALS['_SREQUEST'][$name]['SET.' . $hash] = true;
        $GLOBALS['_SREQUEST'][$name]['SET.REQUEST'] = true;
        
        return $previous;
    }
    /**
     * 获取指定请求的所有值
     * @access public
     * @static     
     * @param string $hash
     * @param int $mask
     * @return mixed
     */
    public static function get($hash = 'default', $mask = 0) {
        $hash = strtoupper($hash);
        
        if($hash === 'METHOD'){
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        
        switch($hash){
            case 'GET':
                $input = $_GET;
                break;
            
            case 'POST':
                $input = $_POST;
                break;
            
            case 'FILES':
                $input = $_FILES;
                break;
            
            case 'COOKIE':
                $input = $_COOKIE;
                break;
            
            case 'ENV':
                $input = &$_ENV;
                break;
            
            case 'SERVER':
                $input = &$_SERVER;
                break;
            
            default:
                $input = $_REQUEST;
                break;
        }
        
        $result = self::_cleanVar($input, $mask);
        
        // Handle magic quotes compatability
        if(get_magic_quotes_gpc() && ($hash != 'FILES')){
            $result = self::_stripSlashesRecursive($result);
        }
        
        return $result;
    }
    /**
     * Sets a request variable.
     *
     * @param	array	An associative array of key-value pairs.
     * @param	string	The request variable to set (POST, GET, FILES, METHOD).
     * @param	boolean	If true and an existing key is found, the value is overwritten, otherwise it is ignored.
     */
    public static function set($array, $hash = 'default', $overwrite = true) {
        foreach($array as $key => $value){
            self::setVar($key, $value, $hash, $overwrite);
        }
    }
    /**
     * Cleans the request from script injection.
     *
     * @return	void
     */
    public static function clean() {
        self::_cleanArray($_FILES);
        self::_cleanArray($_ENV);
        self::_cleanArray($_GET);
        self::_cleanArray($_POST);
        self::_cleanArray($_COOKIE);
        self::_cleanArray($_SERVER);
        
        if(isset($_SESSION)){
            self::_cleanArray($_SESSION);
        }
        
        $REQUEST = $_REQUEST;
        $GET = $_GET;
        $POST = $_POST;
        $COOKIE = $_COOKIE;
        $FILES = $_FILES;
        $ENV = $_ENV;
        $SERVER = $_SERVER;
        
        if(isset($_SESSION)){
            $SESSION = $_SESSION;
        }
        
        foreach($GLOBALS as $key => $value){
            if($key != 'GLOBALS'){
                unset($GLOBALS[$key]);
            }
        }
        $_REQUEST = $REQUEST;
        $_GET = $GET;
        $_POST = $POST;
        $_COOKIE = $COOKIE;
        $_FILES = $FILES;
        $_ENV = $ENV;
        $_SERVER = $SERVER;
        
        if(isset($SESSION)){
            $_SESSION = $SESSION;
        }
        
        // Make sure the request hash is clean on file inclusion
        $GLOBALS['_SREQUEST'] = array();
    }
    /**
     * Adds an array to the GLOBALS array and checks that the GLOBALS variable is not being attacked.
     *
     * @param	array	$array	Array to clean.
     * @param	boolean	True if the array is to be added to the GLOBALS.
     */
    static function _cleanArray(&$array, $globalise = false) {
        static $banned = array('_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals');
        
        foreach($array as $key => $value){
            // PHP GLOBALS injection bug
            $failed = in_array(strtolower($key), $banned);
            // PHP Zend_Hash_Del_Key_Or_Index bug
            $failed |= is_numeric($key);
            if($failed){
                exit('Illegal variable <b>' . implode('</b> or <b>', $banned) . '</b> passed to script.');
            }
            if($globalise){
                $GLOBALS[$key] = $value;
            }
        }
    }
    /**
     * Clean up an input variable.
     *
     * @param mixed The input variable.
     * @param int Filter bit mask. 1=no trim: If this flag is cleared and the
     * input is a string, the string will have leading and trailing whitespace
     * trimmed. 2=allow_raw: If set, no more filtering is performed, higher bits
     * are ignored. 4=allow_html: HTML is allowed
     * @param string The variable type
     */
    static function _cleanVar($var, $mask = 0, $type = null) {
        // If the no trim flag is not set, trim the variable
        if(! ($mask & 1) && is_string($var)){
            $var = trim($var);
        }
        if($mask & 2){
            // If the allow raw flag is set, do not modify the variable
            $var = $var;
        }else{
            if(! $mask & 4){
                $var = strip_tags($var);
            }
            switch(strtoupper($type)){
                case 'INT':
                    preg_match('/-?[0-9]+/', (string)$var, $matches);
                    $result = @ (int)$matches[0];
                    break;
                
                case 'FLOAT':
                    preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$var, $matches);
                    $result = @ (float)$matches[0];
                    break;
                
                case 'BOOL':
                case 'BOOLEAN':
                    $result = (bool)$var;
                    break;
                
                case 'ALNUM':
                    $result = (string)preg_replace('/[^A-Z0-9]/i', '', $var);
                    break;
                case 'STRING':
                    $result = (string)$var;
                    break;
            }
        }
        return $var;
    }
    /**
     * Strips slashes recursively on an array.
     *
     * @param	array	$array		Array of (nested arrays of) strings.
     * @return	array	The input array with stripshlashes applied to it.
     */
    protected static function _stripSlashesRecursive($value) {
        $value = is_array($value) ? array_map(array('request', '_stripSlashesRecursive'), $value) : stripslashes($value);
        return $value;
    }
}