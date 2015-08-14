<?php
//設定 Session 的生命週期 , 預設 1440 秒
define('SESS_LIFTTIME', 1440);

//設定 memcache 
define('MEMCACHE_HOST', 'localhost');
define('MEMCACHE_PORT', '11211');

if (!defined('memSession'))
{
    define('memSession',    TRUE);

	class memSession
	{

              static  $mSessSavePath;
              static  $mSessName;
              static  $mMemcacheObj;
          
              /**
               *
               * @param string $login_user    登錄使用者
               * @param int $login_type       登錄類型
               * @param string $login_sess    登錄Session值
               * @return Esession
               */
              public function __construct()
              {
                  if (!class_exists('Memcache') || !function_exists('memcache_connect'))
                  {
                      die('Fatal Error:Can not load Memcache extension!');
                  }
          
                  if (!empty(self::$mMemcacheObj) && is_object(self::$mMemcacheObj))
                  {
                      return false;
                  }
          
                  self::$mMemcacheObj = new Memcache;
          
                  if (!self::$mMemcacheObj->connect(MEMCACHE_HOST , MEMCACHE_PORT))
                  {
                      die('Fatal Error: Can not connect to memcache host '. MEMCACHE_HOST .':'. MEMCACHE_PORT);
                  }
          
                  return TRUE;
              }
              // }}}
          
              /** {{{ sessOpen($pSavePath, $name)
               *
               * @param   String  $pSavePath
               * @param   String  $pSessName
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessOpen($pSavePath = '', $pSessName = '')
              {
                  self::$mSessSavePath    = $pSavePath;
                  self::$mSessName        = $pSessName;
          
                  return TRUE;
              }
              // }}}
          
              /** {{{ sessClose()
               *
               * @param   NULL
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessClose()
              {
                  return TRUE;
              }
              // }}}
          
              /** {{{ sessRead($wSessId)
               *
               * @param   String  $wSessId
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessRead($wSessId = '')
              {
                  $wData = self::$mMemcacheObj->get($wSessId);
          
                  // 讀取資料如果沒有就初始化一個
                  if (!empty($wData))
                  {
                      return $wData;
                  }
                  else
                  {
                      //初始化一筆空的 SESSION
                      $ret = self::$mMemcacheObj->set($wSessId, '', 0, SESS_LIFTTIME);
          
                      if (TRUE != $ret)
                      {
                          die("Fatal Error: Session ID $wSessId init failed!");
          
                          return FALSE;
                      }
          
                      return TRUE;
                  }
              }
              // }}}
          
              /** {{{ sessWrite($wSessId, $wData)
               *
               * @param   String  $wSessId
               * @param   String  $wData
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessWrite($wSessId = '', $wData = '')
              {
                  $ret = self::$mMemcacheObj->replace($wSessId, $wData, 0, SESS_LIFTTIME);
          
                  if (TRUE != $ret)
                  {
                      die("Fatal Error: SessionID $wSessId Save data failed!");
          
                      return FALSE;
                  }
          
                  return TRUE;
              }
              // }}}
          
              /** {{{ sessDestroy($wSessId)
               *
               * @param   String  $wSessId
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessDestroy($wSessId = '')
              {
                  self::sessWrite($wSessId);
          
                  return FALSE;
              }
              // }}}
          
              /** {{{ sessGc()
               *
               * @param   NULL
               *
               * @return  Bool    TRUE/FALSE
               */
              public function sessGc()
              {
                  //不需要回收,memcache有自己的過期回收機制
          
                  return TRUE;
              }
              // }}}
          
              /** {{{ initSess()
               *
               * @param   NULL
               *
               * @return  Bool    TRUE/FALSE
               */
              public function initSess($expire = '')
              {
                  $domain = '.wallet.com';
          
                  //不使用 GET/POST 變數放式
                  ini_set('session.use_trans_sid',    0);
                  ini_set('session.gc_maxlifetime',   SESS_LIFTTIME);
          
                  //使用 COOKIE 保存 SESSION ID 的方式
                  ini_set('session.use_cookies',      1);
                  ini_set('session.cookie_path',      '/');
          
                  //多台主機共享 SESSION ID 的 COOKIE
                  ini_set('session.cookie_domain',    $domain);
          
                  //將 session.save_handler 設定為 user，而不是預設的 files
                  session_module_name('user');
          
                  session_set_save_handler(
                          array('memSession', 'sessOpen'),   //對應到靜到 METHOD ,  My_Sess::open()，下同。
                          array('memSession', 'sessClose'),
                          array('memSession', 'sessRead'),
                          array('memSession', 'sessWrite'),
                          array('memSession', 'sessDestroy'),
                          array('memSession', 'sessGc')
                          );
          
                  session_start();
          
                  return TRUE;
              }
              // }}}

	}//end class

}//end define

//$memSess    = new MemacheSession;
//$memSess->initSess();
?>
