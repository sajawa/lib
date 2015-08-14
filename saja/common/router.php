<?php
/**
 * 系统路由管理工具
 *
 * @category  Platform
 * @package   Common
 * @author   chenbin
 * @version  $Id:router.php  2011-09-23 09:57 chenbin $
 * @link     referrer
 * @since    2011-09-23 09:57
 */

class router {
    /** @var string base url */
    private $_baseUrl;
    /** @var string request url */
    private $_requestUri;
    /** @var string path info */
    private $_pathInfo;
    /** @var array route array */
    private $_routes;
    /** @staticvar Object router Object */
    private static $_instance;
    
    /**
     * router construct
     * @access private
     */
    private function __construct() {
        $this->addRoutes();
        log::accessLog('Router object create successful!');
    }
    /**
     * 创建唯一router实例
     * @access public
     * @static
     * @return Object routerObject
     * @example $router = router::getInstance()
     */
    public static function getInstance() {
        if(! self::$_instance instanceof router){
            self::$_instance = new self();
            log::accessLog("Create router instance success");
        }
        log::returnLog(self::$_instance);
        return self::$_instance;
    }
    /**
     * 添加routes
     * @access public
     * @param array $routes
     * @return void
     */
    public function addRoutes(array $routes = array()) {
        if(count($routes)){
            if(is_array($this->_routes)){
                $this->_routes = array_merge_recursive($this->route(), $routes);
            }else{
                $this->_routes = $routes;
            }
        }else{
            $routes = config::get('routes');
            if($routes){
                $this->_routes = & $routes;
            }
        }
        log::accessLog('Execute addRoutes sucessful!');
    }
    /**
     * 获取routes
     * @access private
     * @return array routes array
     */
    private function _getRoutes() {
        if(null === $this->_baseUrl){
            $this->addRoutes();
        }
        return $this->_routes;
    }
    /**
     * 设置base Url
     * @access public 
     * @param string $baseUrl
     * @return void
     */
    public function setBaseUrl($baseUrl = null) {
        if((null !== $baseUrl) && ! is_string($baseUrl)){
            log::errorLog('Error baseUrl value!');
            return false;
        }
        if($baseUrl === null){
            $filename = (isset($_SERVER['SCRIPT_FILENAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';
            if(isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename){
                $baseUrl = $_SERVER['SCRIPT_NAME'];
            }elseif(isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename){
                $baseUrl = $_SERVER['PHP_SELF'];
            }elseif(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename){
                $baseUrl = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
            }else{
                // Backtrack up the script_filename to find the portion matching
                // php_self
                $path = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
                $file = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
                $segs = explode('/', trim($file, '/'));
                $segs = array_reverse($segs);
                $index = 0;
                $last = count($segs);
                $baseUrl = '';
                do{
                    $seg = $segs[$index];
                    $baseUrl = '/' . $seg . $baseUrl;
                    ++$index;
                }while(($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
            }
            
            // Does the baseUrl have anything in common with the request_uri?
            $requestUri = $this->getRequestUri();
            if(0 === strpos($requestUri, $baseUrl)){
                // full $baseUrl matches
                $this->_baseUrl = $baseUrl;
            }else if(0 === strpos($requestUri, dirname($baseUrl))){
                // directory portion of $baseUrl matches
                $this->_baseUrl = rtrim(dirname($baseUrl), '/');
            }else{
                $truncatedRequestUri = $requestUri;
                if(($pos = strpos($requestUri, '?')) !== false){
                    $truncatedRequestUri = substr($requestUri, 0, $pos);
                }
                
                $basename = basename($baseUrl);
                if(empty($basename) || ! strpos($truncatedRequestUri, $basename)){
                    // no match whatsoever; set it blank
                    $this->_baseUrl = '';
                }else if((strlen($requestUri) >= strlen($baseUrl)) && ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0))){
                    // If using mod_rewrite or ISAPI_Rewrite strip the script filename
                    // out of baseUrl. $pos !== 0 makes sure it is not matching a value
                    // from PATH_INFO or QUERY_STRING
                    $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
                }
            }
        }
        $this->_baseUrl = rtrim($baseUrl, '/');
        log::accessLog('Execute setBaseUrl successful!');
    }
    /**
     * 获取baseUrl
     * @access public
     * @return string
     */
    public function getBaseUrl() {
        if(null === $this->_baseUrl){
            $this->setBaseUrl();
        }
        log::accessLog('Execute getBaseUrl() sucessful!');
        log::returnLog($this->_baseUrl);
        return $this->_baseUrl;
    }
    /**
     * 获取Request uri
     * @access public
     * @return string
     */
    public function getRequestUri() {
        if(empty($this->_requestUri)){
            if(isset($_SERVER['HTTP_X_REWRITE_URL'])){ // check this first so IIS will catch
                $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            }elseif(isset($_SERVER['IIS_WasUrlRewritten']) && $_SERVER['IIS_WasUrlRewritten'] == '1' && isset($_SERVER['UNENCODED_URL']) && $_SERVER['UNENCODED_URL'] != ''){
                // IIS7 with URL Rewrite: make sure we get the unencoded url (double slash problem)
                $requestUri = $_SERVER['UNENCODED_URL'];
            }elseif(isset($_SERVER['REQUEST_URI'])){
                $requestUri = $_SERVER['REQUEST_URI'];
                // Http proxy reqs setup request uri with scheme and host [and port] + the url path, only use url path
                $schemeAndHttpHost = getSiteUrl();
                if(strpos($requestUri, $schemeAndHttpHost) === 0){
                    $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
                }
            }elseif(isset($_SERVER['ORIG_PATH_INFO'])){ // IIS 5.0, PHP as CGI
                $requestUri = $_SERVER['ORIG_PATH_INFO'];
                if(! empty($_SERVER['QUERY_STRING'])){
                    $requestUri .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
            $this->_requestUri = $requestUri;
        }
        log::accessLog('Execute getRequestUri() successful!');
        log::returnLog($this->_requestUri);
        return $this->_requestUri;
    }
    /**
     * 获取path info
     * @access public
     * @return string
     */
    public function getPathInfo() {
        if($this->_pathInfo === null){
            $baseUrl = $this->getBaseUrl();
            
            if(null === ($requestUri = $this->getRequestUri())){
                return false;
            }
            
            // Remove the query string from REQUEST_URI
            if($pos = strpos($requestUri, '?')){
                $requestUri = substr($requestUri, 0, $pos);
            }
            
            if(null !== $baseUrl && ((! empty($baseUrl) && 0 === strpos($requestUri, $baseUrl)) || empty($baseUrl)) && false === ($pathInfo = substr($requestUri, strlen($baseUrl)))){
                // If substr() returns false then PATH_INFO is set to an empty string 
                $pathInfo = '';
            }elseif(null === $baseUrl || (! empty($baseUrl) && false === strpos($requestUri, $baseUrl))){
                $pathInfo = $requestUri;
            }
            $this->_pathInfo = (string)$pathInfo;
        }
        log::accessLog('Execute getPathInfo() successful!');
        log::returnLog($this->_pathInfo);
        return $this->_pathInfo;
    }
    /**
     * 路由转换处理
     * @access public
     */
    public function route() {
        $path = trim(urldecode($this->getPathInfo()), '/');
        $routeMatched = false;
        $routes = $this->_getRoutes();
        if(is_array($routes) && count($routes)){
            foreach($routes as $module => $rules){
                if(is_array($rules) && count($rules)){
                    foreach($rules as $rule){
                        if(isset($rule['rule']) && is_string($rule['rule'])){
                            $pattern = '#^' . $rule['rule'] . '$#i';
                            $routeMatched = preg_match($pattern, $path, $matches);
                            if($routeMatched){
                                array_shift($matches);
                                if(isset($rule['matches']['controller'])){
                                    $classFile = sprintf('%s/app/%s/%scontroller.php', PROJECT_ROOT, $module, $rule['matches']['controller']);
                                    if(! is_file($classFile)){
                                        throw new Exception('Error controller file!(' . $rule['matches']['controller'] . ')');
                                        return false;
                                    }
                                    require_once $classFile;
                                    $className = sprintf('%s%sController', strtolower($module), ucfirst($rule['matches']['controller']));
                                    if(! class_exists($className, false)){
                                        throw new Exception('Error controller name!(' . $className . ')');
                                        return false;
                                    }
                                    $action = 'index';
                                    if(isset($rule['matches']['action'])){
                                        $action = $rule['matches']['action'];
                                    }
                                    $action = strtolower($action) . 'Action';
                                    $controllerObject = new $className();
                                    if(method_exists($controllerObject, 'init')){
                                        $controllerObject->init();
                                    }
                                    if(! method_exists($controllerObject, $action)){
                                        throw new Exception('Error action name(' . $className . '->' . $action . ')');
                                        return false;
                                    }
                                    call_user_func_array(array($controllerObject, $action), $matches);
                                    $routeMatched = true;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        }        
        log::accessLog('Execute route() sucessful');
        log::returnLog($routeMatched);
        return $routeMatched;
    }
}