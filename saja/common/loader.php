<?php
/**
 * 开发模式组件加载器
 * @author : 江陈
 */

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}
if(!defined('ROOT_DIR')){
    define('ROOT_DIR',dirname(dirname(__FILE__)));
}
class loader{
    public static $_enableLoader = true;
    private static $_instance = null;
    private static $_maps = null;
    public static function autoRun(){
        if(self::$_enableLoader){
            if(! self::$_instance instanceof loader){
                self::$_instance = new self();
            }
        }
    }
    private function __construct(){
        spl_autoload_register(array($this,'autoload'));
    }
    private function autoload($class){
        $eject_array=array(
            'gadgetIndexAbstract',
            'gadgetIndexController',
            'gadgetApplicationController',
            'gadgetApplicationAbstract',
        );
        if(!is_array(self::$_maps)){
            self::$_maps = include(ROOT_DIR.DS.'conf'.DS.'maps.php');
        }
        $pattern = '/[A-Z][a-z0-9]+/';
        preg_match_all($pattern,$class,$matches);
        $prefix = null;
        $path = null;
        if(isset($matches[0]) && !empty($matches[0])){

            $prefix = substr($class,0,stripos($class,$matches[0][0]));
            if($prefix == 'read' || $prefix == 'write'){
                return;
            }
            $path = '';
            foreach($matches[0] as $m){
                $path .= strtolower($m) . DS;
            }
            $path = rtrim($path,DS) . '.php';
        }
        else{
            $path = strtolower($class) . '.php';
        }

        if(in_array($class,$eject_array)){
            $path = str_replace('/','',$path);
        }

        if($prefix && isset(self::$_maps[$prefix])){
            $classPath = self::$_maps[$prefix];
        }else{
            $classPath = ROOT_DIR . DS . 'common' . DS;
        }

        if(is_array($classPath)){
            $required = false;
            
            foreach($classPath as $cp){
                if(file_exists($cp.$path)){
                    require $cp.$path;
                    $required = true;
                    break;
                }
            }
            if(!$required){
                log::errorLog($class . ': FileNotFound');
                return false;
            }
        }else{
            if(file_exists($classPath.$path)){
                require $classPath.$path;
            }else{
                log::errorLog($class . ': FileNotFound');
                return false;
            }
        }
    }
}
