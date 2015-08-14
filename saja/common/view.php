<?php
// $Id: view.php 2011-09-13 14:16:02Z 江陈 $


/**
 * 定义 view 类
 *
 * @link 
 * @version $Id: view.php 2011-09-13 14:16:02Z 江陈 $
 * @package common
 * @category Platform 
 */

class view {
    /**
     * 视图分析类名
     *
     * @var string
     */
    protected $_parser_name = 'viewParser';
    
    /**
     * 视图文件所在目录
     *
     * @var string
     */
    public $view_dir;
    
    /**
     * 要输出的头信息
     *
     * @var array
     */
    public $headers;
    
    /**
     * 视图文件的扩展名
     *
     * @var string
     */
    public $file_extname = 'php';
    
    /**
     * 模板变量
     *
     * @var array
     */
    protected $_vars;
    
    /**
     * 视图
     *
     * @var string
     */
    protected $_viewname = 'index';
    
    /**
     * 要使用的布局视图
     *
     * @var string
     */
    protected $_view_layouts;
    
    /**
     * 当前使用的分析器
     *
     * @var viewrPaser
     */
    protected $_parser;
    /**
     * 开关view
     *
     * @var viewParser
     */
    protected $_enableView = true;
    /**
     * view最终数据
     * @var _viewData
     */
    protected $_viewData = null;
    /**
     * viewdir 静态
     * @var _viewDir
     */
    protected static $_viewDir = null;
    /**
     * layout 开关
     * @var _enableLayout
     */
    protected $_enableLayout = true;
    /**
     * 默认layout
     * @var _defaultlayout
     */
    protected $_defaultLayout = '';
    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct(array $config = null) {
        if(is_array($config)){
            foreach($config as $key => $value){
                $this->{$key} = $value;
            }
        }
        
        $this->cleanVars();
        log::accessLog('view construct success');
    }
    /**
     * 关闭view
     * return void
     */
    public function disableView() {
        $this->_enableView = false;
        log::accessLog('disable view success');
    }
    /**
     * 关闭layout
     * return void
     */
    public function disableLayout() {
        $this->_enableLayout = false;
        log::accessLog('disable layout success');
    }
    /**
     * 设置视图名称
     *
     * @param string $viewname
     *
     * @return view
     */
    public function setViewName($viewname) {
        $this->_viewname = $viewname;
        log::accessLog('set view name success');
        log::returnLog($this);
        return $this;
    }
    /**
     * 设置视图目录
     *
     * @param string $viewdir
     *
     * @return void
     */
    public function setViewDir($viewdir) {
        $this->view_dir = $viewdir;
        log::accessLog('set viewdir success');
    }
    /**
     * 静态方式设置视图目录,最高优先级
     *
     * @param string $viewname
     *
     * @return void
     */
    public static function setViewDirStatic($viewdir) {
        self::$_viewDir = $viewdir;
        log::accessLog('set viewdirstatic success');
    }
    /**
     * 判断view是否存在
     * 
     * @param string $viewname
     *
     * @return boolean
     */
    public function isView($viewName) {
        $result = file_exists($this->view_dir . DIRECTORY_SEPARATOR . $viewName . '.php');
        log::accessLog('isView ok');
        log::returnLog($result);        
        return $result;
    }
    /**
     * 设置默认layout
     * 
     * @param string $layoutName
     *
     * @return void
     */
    public function setDefaultLayout($layoutName){
        log::accessLog('setdefaultlayout ok');
        $this->_defaultLayout = $layoutName;
    }
    /**
     * 指定模板变量
     *
     * @param string|array $key
     * @param mixed $data
     *
     * @return view
     */
    public function assign($key, $data = null) {
        if(is_array($key)){
            $this->_vars = array_merge($this->_vars, $key);
        }else{
            $this->_vars[$key] = $data;
        }
        log::accessLog('assign key value success');
        log::returnLog($this);
        return $this;
    }
    /**
     * 指定模板变量
     *
     * @param string|array $key
     * @param mixed $data
     *
     * @return view
     */
    public function __set($key, $data = null) {
        if(is_array($key)){
            $this->_vars = array_merge($this->_vars, $key);
        }else{
            $this->_vars[$key] = $data;
        }
        log::accessLog('assign key value success');
        log::returnLog($this);
        return $this;
    }
    
    /**
     * 获取指定模板变量
     *
     * @param string
     *
     * @return mixed
     */
    public function getVar($key, $default = null) {
        $data = isset($this->_vars[$key]) ? $this->_vars[$key] : $default;
        log::accessLog('get value success');
        log::returnLog($data);
        return $data;
    }
    /**
     * 获取指定模板变量
     *
     * @param string
     *
     * @return mixed
     */
    public function __get($key) {
        $default = null;
        $data = isset($this->_vars[$key]) ? $this->_vars[$key] : $default;
        log::accessLog('get value success');
        log::returnLog($data);
        return $data;
    }
    
    /**
     * 获取所有模板变量
     *
     *
     * @return mixed
     */
    public function getVars() {
        $data = $this->_vars;
        log::accessLog('get vars success');
        log::returnLog($data);
        return $data;
    }
    
    /**
     * 清除所有模板变量
     *
     * @return view
     */
    public function cleanVars() {
        $this->_vars = array();
        log::accessLog('clean vars success');
        log::returnLog($this);
        return $this;
    }
    
    /**
     * 渲染视图
     *
     * @param string $viewname
     * @param array $vars
     * @param array $config
     */
    public function display($viewname = null, array $vars = null, array $config = null) {
        if(empty($viewname)){
            $viewname = $this->_viewname;
        }
        
        echo $this->fetch($viewname, $vars, $config);
        log::accessLog('display view success');
    }
    
    /**
     * 执行
     */
    public function execute() {
        $this->display($this->_viewname);
        log::accessLog('execute view success');
    }
    
    /**
     * 渲染视图并返回渲染结果
     *
     * @param string $viewname
     * @param array $vars
     * @param array $config
     *
     * @return string
     */
    public function fetch($viewname = null, array $vars = null, array $config = null) {
        if(! $this->_enableView){
            die();
        }
        if(empty($viewname)){
            $viewname = $this->_viewname;
        }
        
        $this->_before_render();
        $view_dir = isset($config['view_dir']) ? $config['view_dir'] : $this->view_dir;
        $view_dir = ! empty(self::$_viewDir) ? self::$_viewDir : $view_dir;
        $extname = isset($config['file_extname']) ? $config['file_extname'] : $this->file_extname;
        $filename = "{$view_dir}/{$viewname}.{$extname}";
        if(file_exists($filename)){
            if(! is_array($vars)){
                $vars = $this->_vars;
            }
            if(is_null($this->_parser)){
                $parser_name = $this->_parser_name;
                $this->_parser = new $parser_name($view_dir);
            }
            if($this->_enableLayout){
                $output = $this->_parser->assign($vars)->parse($filename, null, null, array('id' => mt_rand(), 'contents' => '', 'extends' => '_layout/default_layout', 'blocks_stacks' => array('content'), 'blocks' => array('content'), 'blocks_config' => array('content' => null), 'nested_blocks' => array()));
            }else{
                $output = $this->_parser->assign($vars)->parse($filename, null, null, array('id' => mt_rand(), 'contents' => '', 'extends' => '_layout/default_layout', 'blocks_stacks' => array('content'), 'blocks' => array('content'), 'blocks_config' => array('content' => null), 'nested_blocks' => array()), false);
            }
        
        }else{
            log::errorLog('Error view file:' . $filename);
            return false;
        }
        
        $this->_after_render($output);
        $this->_viewData = $output;
        log::accessLog('fetch view success');
        log::returnLog($output);
        return $output;
    }
    
    /**
     * 渲染之前调用
     *
     * 继承类可以覆盖此方法。
     */
    protected function _before_render() {
    }
    
    /**
     * 渲染之后调用
     *
     * 继承类可以覆盖此方法。
     *
     * @param string $output
     */
    protected function _after_render(& $output) {
    }

}

/**
 * viewParser 类实现了视图的分析
 *
 * @author 江陈
 * @version $Id: viewparser.php 2011-09-13 14:16:02Z 江陈 $
 * @package common
 * @category platform
 */
class viewParser {
    /**
     * 视图文件扩展名
     * 
     * @var string
     */
    protected $_extname;
    
    /**
     * 视图堆栈
     *
     * @var array
     */
    private $_stacks = array();
    
    /**
     * 当前处理的视图
     *
     * @var int
     */
    private $_current;
    
    /**
     * 视图变量
     *
     * @var array
     */
    protected $_vars;
    
    /**
     * 视图文件所在目录
     *
     * @var string
     */
    private $_view_dir;
    
    /**
     * 构造函数
     */
    public function __construct($view_dir) {
        $this->_view_dir = $view_dir;
        log::accessLog('viewparser construct success');
    }
    
    /**
     * 设置分析器已经指定的变量
     *
     * @param array $vars
     *
     * @return viewParser
     */
    public function assign(array $vars) {
        $this->_vars = $vars;
        log::accessLog('assign vars success');
        log::returnLog($this);
        return $this;
    }
    
    /**
     * 返回分析器使用的视图文件的扩展名
     *
     * @return string
     */
    public function extname() {
        log::returnLog($this->_extname);
        return $this->_extname;
    }
    
    /**
     * 分析一个视图文件并返回结果
     *
     * @param string $filename
     * @param string $view_id
     * @param array $inherited_stack
     *
     * @return string
     */
    public function parse($filename, $view_id = null, array $inherited_stack = null, $default = null, $enableLayout = true) {
        if(! $view_id)
            $view_id = mt_rand();
        
        $stack = array('id' => $view_id, 'contents' => '', 'extends' => '', 'blocks_stacks' => array(), 'blocks' => array(), 'blocks_config' => array(), 'nested_blocks' => array());
        array_push($this->_stacks, $stack);
        $this->_current = count($this->_stacks) - 1;
        unset($stack);
        
        ob_start();
        $this->_include($filename);
        $stack = $this->_stacks[$this->_current];
        if($enableLayout){
            if(empty($stack['blocks'])){
                $stack = $default;
                $stack['blocks']['content'] = ob_get_clean();
                $inherited_stack = $stack;
            }else{
                $stack['extends'] = $default['extends'];
                $stack['contents'] = ob_get_clean();
            }
        }else{
            $stack['contents'] = ob_get_clean();
        }
        
        // 如果有继承视图，则用继承视图中定义的块内容替换当前视图的块内容
        if(is_array($inherited_stack)){
            foreach($inherited_stack['blocks'] as $block_name => $contents){
                if(isset($stack['blocks_config'][$block_name])){
                    switch(strtolower($stack['blocks_config'][$block_name])){
                        case 'append':
                            $stack['blocks'][$block_name] .= $contents;
                            break;
                        case 'replace':
                        
                        default:
                            $stack['blocks'][$block_name] .= $contents;
                    }
                }else{
                    $stack['blocks'][$block_name] = $contents;
                }
            }
        }
        // 如果有嵌套 block，则替换内容
        while(list($child, $parent) = array_pop($stack['nested_blocks'])){
            $stack['blocks'][$parent] = str_replace("%block_contents_placeholder_{$child}_{$view_id}%", $stack['blocks'][$child], $stack['blocks'][$parent]);
            unset($stack['blocks'][$child]);
        }
        // 保存对当前视图堆栈的修改
        $this->_stacks[$this->_current] = $stack;
        if(! $enableLayout){
            $stack['extends'] = '';
        }
        if($stack['extends']){
            // 如果有当前视图是从某个视图继承的，则载入继承视图
            $filename = "{$this->_view_dir}/{$stack['extends']}.{$this->_extname}";
            
            $data = $this->parse($filename, $view_id, $this->_stacks[$this->_current]);
            log::returnLog($data);
            return $data;
        }else{
            // 最后一个视图一定是没有 extends 的
            $last = array_pop($this->_stacks);
            foreach($last['blocks'] as $block_name => $contents){
                $last['contents'] = str_replace("%block_contents_placeholder_{$block_name}_{$last['id']}%", $contents, $last['contents']);
            }
            $this->_stacks = array();
            
            $data = $last['contents'];
            log::returnLog($data);
            return $data;
        }
    }
    
    /**
     * 视图的继承
     *
     * @param string $tplname
     *
     * @access public
     */
     protected function _extends($tplname) {
        $layout = !empty($this->_defaultLayout)?$this->_defaultLayout:'_layout/default_layout';
        $tplname = empty($tplname) ? $layout : $tplname;
        $this->_stacks[$this->_current]['extends'] = $tplname;
        log::accessLog('extends template success');
    }
    
    /**
     * 开始定义一个区块
     *
     * @param string $block_name
     * @param mixed $config
     *
     * @access public
     */
    protected function _block($block_name, $config = null) {
        empty($block_name) ? $block_name = 'content' : $block_name;
        $stack = & $this->_stacks[$this->_current];
        if(! empty($stack['blocks_stacks'])){
            // 如果存在嵌套的 block，则需要记录下嵌套的关系
            $last = $stack['blocks_stacks'][count($stack['blocks_stacks']) - 1];
            $stack['nested_blocks'][] = array($block_name, $last);
        }
        $this->_stacks[$this->_current]['blocks_config'][$block_name] = $config;
        array_push($stack['blocks_stacks'], $block_name);
        ob_start();
        log::accessLog('define block success');
    }
    
    /**
     * 结束一个区块
     *
     * @access public
     */
    protected function _endblock() {
        $block_name = array_pop($this->_stacks[$this->_current]['blocks_stacks']);
        $this->_stacks[$this->_current]['blocks'][$block_name] = ob_get_clean();
        echo "%block_contents_placeholder_{$block_name}_{$this->_stacks[$this->_current]['id']}%";
        log::accessLog('endblock success');
    }
    
    /**
     * 载入一个视图片段
     *
     * @param string $element_name
     * @param array $vars
     *
     * @access public
     */
    protected function _element($element_name, array $vars = null) {
        //$filename = "{$this->_view_dir}/_elements/{$element_name}_element.{$this->_extname}";
        $filename = "{$this->_view_dir}/{$element_name}.{$this->_extname}";
        $this->_include($filename, $vars);
        log::accessLog('load an element success');
    }
    
    /**
     * 载入视图文件
     */
    protected function _include($___filename, array $___vars = null) {
        $this->_extname = pathinfo($___filename, PATHINFO_EXTENSION);
        extract($this->_vars);
        if(is_array($___vars))
            extract($___vars);
        include $___filename;
        log::accessLog('include a file success');
    }
}

