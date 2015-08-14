<?php
/**
 * cache抽像層
 * @category	platform
 * @package		common
 * @author		hoffmann
 * @version		$Id$
 * @link
 * @since		2011-09-02
 * @abstract
 */

abstract class cacheAbstract{
    /**
    * @access protected
    * @var integer default expiration time
    */
    const DEFAULT_EXPIRATION_DURATION=1800;

	/**
	* @access protected
	* @var array access configuration parameters
	*/
	protected $_config=null;

    /**
    * constructor
    * @access 	public
    * @return	boolean
    * @since	2011-09-02
    */
    abstract public function __construct($config);

   /**
    * 連接 cache server
    * @access 	public
    * @return	boolean
    * @since	2011-09-02
    */
    abstract public function connect();

    /**
    * 關閉 cache 的連接
    * @access	public
    * @return	boolean
    * @since	2011-09-02
    */
    abstract public function close() ;

    /**
    * 讀出 cache 值
    * @access
    * @return
    * @since	2011-09-02
    */
    abstract public function read($key) ;

    /**
    * 寫入 cache 值
    * @access
    * @return
    * @since	2011-09-02
    */
    abstract public function write($key,$data,$expire=self::DEFAULT_EXPIRATION_DURATION) ;

    /**
    * 刪除 cache 值
    * @access
    * @return
    * @since	2011-09-08
    */
    abstract public function delete($key) ;


}
