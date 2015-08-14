<?PHP
$debug_mod = "Y";
$host_name="localhost";
$protocol = "http";
$domain_name = "test-cs.com";

############################################################################
# defient
############################################################################
$config["project_id"] 			= "admin"; 
$config["default_main"] 		= "/"; 
$config["default_charset"]		= "UTF-8";
$config["sql_charset"]			= "utf8";
$config["default_prefix"] 		= "sns_" ;  			// prefix
$config["default_prefix_id"] 		= "sns" ;		// prefixid
$config["cookie_path"] 			= "/";  
$config["cookie_domain"] 		= "";  
$config["tpl_type"] 			= "php"; 
$config["module_type"] 			= "php";	  		// or xml
$config["fix_time_zone"]                = -8 ;                          // modify time zone 
$config["default_time_zone"]            = 8 ;                           // default time zone 
$config["max_page"] 			= 10 ;
$config["max_range"] 			= 10 ;
$config["encode_type"]			= "crypt" ; 			// crypt or md5
$config["encode_key"]			= "%^$#@%S_d_+!" ; 			// crypt encode key
$config["session_time"] 		= 15	; 			// Session time out
$config["default_lang"] 		= "tc"	; 			// en , tc
$config["default_template"]		= "cs"	; 		// 
$config["default_topn"] 		= 10	; 			// default top n
$config["debug_mod"]			= $debug_mod ; 			// enable all the debug console , N : disable , Y : enable 
$config["max_upload_file_size"]		= 800000000 ; 			//unit is k
//$config["expire"]			= "60" ; 			// memcace expire time . sec
$config["domain_name"]			= $domain_name;
$config["protocol"]			= $protocol;
$config["admin_email"]			= array('shaun.huang@snsplus.com') ; 
$config["currency_email"]		= array('shaun.huang@snsplus.com') ; 
$config["transaction_time_limit"]	= 5; //The minimized time between two transaction
############################################################################
# FaceBook
############################################################################
$config['fb']['appid' ]  = "205246299551641";
$config['fb']['secret']  = "404a0c7e6a5e45d390ee0a85fd1663de";
$config['fb']['req_perms' ]  = "email,publish_stream,user_birthday,user_location";

$config['fb']['ddtank']['appid' ]  = "289035024453160";
$config['fb']['ddtank']['secret']  = "114ecfbe2ec004c44842e50a001e3b79";
$config['fb']['wonderjourney']['appid' ]  = "163877867044262";
$config['fb']['wonderjourney']['secret']  = "7a5220e83a93af132a6385b3e9f88310";


############################################################################
# path
############################################################################
$config["path_wallet"]            = "/var/www/test-cs.com/phpmodule";
$config["path_class"] 		= $config["path_wallet"]."/class" ; 
$config["path_function"] 	= $config["path_wallet"]."/function" ; 
$config["path_include"] 	= $config["path_wallet"]."/include" ; 
$config["path_bin"] 		= $config["path_wallet"]."/bin" ; 
$config["path_data"] 		= $config["path_wallet"]."/data/" ; 
$config["path_cache"] 		= $config["path_wallet"]."/data/cache" ; 
$config["path_sources"] 	= $config["path_wallet"]."/sources/".$config["module_type"] ; 
$config["path_style"] 		= $config["path_wallet"]."/template/".$config["tpl_type"] ; 
$config["path_language"]	= $config["path_wallet"]."/language" ; 
$config["path_images"] 		= dirname($config["path_wallet"])."/images" ; 
$config["path_products_images"] = dirname(dirname($config["path_wallet"]))."/images/products" ; 
$config["path_css"] 		= $config["path_wallet"]."/css/" ; 
$config["path_javascript"] 	= $config["path_wallet"]."/javascript/" ; 
$config["path_pdf_template"] 	= $config["path_class"]."/tcpdf/template/" ; 
$config["path_eamil_api"] 	= "/usr/bin/" ;  // email api path 
$config["path_cdn"] 		= "/" ;  
$config['path_page_cache']      = '/tmp/ramdisk/gamagic';
$config['path_user_cache']      = '/tmp/gamagic';


############################################################################
# sql Shaun 
############################################################################
$config["db"][0]["charset"] 	= "utf8" ;
$config["db"][0]["host"] 	= '10.67.119.91';
$config["db"][0]["type"] 	= "mysql";
$config["db"][0]["username"]    = "test";
$config["db"][0]["password"]    = "Vijin11" ;

$config["db"][1]["charset"] 	= "utf8" ;
$config["db"][1]["host"] 	= '10.67.119.92';
$config["db"][1]["type"] 	= "mysql";
$config["db"][0]["username"]    = "test";
$config["db"][0]["password"]    = "Vijin11" ;

#$config["db_port"][0]		= "/var/lib/mysql/mysql.sock";

$config["db"][0]["dbname"]	= "sns_site" ;
$config["db"][1]["dbname"]	= "sns_language" ;
$config["db"][2]["dbname"]	= "sns_deposit" ;
$config["db"][3]["dbname"]	= "sns_wallet" ;
$config["db"][4]["dbname"]	= "sns_event" ;
$config["db"][5]["dbname"]	= "sns_language" ;
$config["db"][6]["dbname"]	= "sns_paypal" ;
$config["db"][7]["dbname"]	= "sns_bank_vault" ;
$config["db"][8]["dbname"]	= "sns_notice" ;
$config["db"][9]["dbname"]	= "sns_bulletin" ;
$config["db"][10]["dbname"]	= "sns_game" ;
$config["db"][11]["dbname"]	= "sns_product" ;
$config["db"][12]["dbname"]	= "sns_exchange" ;
$config["db"][13]["dbname"]     = "sns_service" ;
$config["db"][14]["dbname"]     = "sns_service_admin" ;
############################################################################
# memcache 
############################################################################
// session : 為 SESSION Memcache Server , 
$config['memcache']['server_list']['session']['ip']	= '10.67.119.81';
$config['memcache']['server_list']['session']['port']	= 11211;
// ap1 : 為 Application Memcache Server 1 , 
$config['memcache']['server_list']['ap1']['ip']	= '10.67.119.82';
$config['memcache']['server_list']['ap1']['port']	= 11211;
// ap2 : 為 Application Memcache Server 2 , 
$config['memcache']['server_list']['ap2']['ip']	= '10.67.119.82';
$config['memcache']['server_list']['ap2']['port']	= 11211;



$config['memcache']['MEM_PERSISTENT']		= true;
$config['memcache']['MEM_TIMEOUT']			= 1;
$config['memcache']['MEM_RETRY_INTERVAL']	= 1;
$config['memcache']['MEM_STATUS']			= 1;
$config['memcache']['MEM_WEIGHT']			= 1000;

############################################################################
# snsPayment
############################################################################
$config['payment']['deposit_page']		= "http://test-pay.snsplus.com/api/getpaymenturl";
$config['payment']['game_coding']		= "OOSPAR";
$config['payment']['platform']			= "snsplus";
$config['payment']['entrance']		    = "connect"; // connect : 非 FB 的模式 , noconnect : APP2APP , 都不設 是 FBC
$config['payment']['apisecret']         = "edeab473ef53620dd2627185af67353c";
$config['payment']['prefix']			= "mj_sig";

############################################################################
# Expire 
############################################################################
$config['expire']["auth_token"] = 300;
$config['expire']["call_id"] 	= 600;


?>
