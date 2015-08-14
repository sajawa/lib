<?PHP
$debug_mod = "N";
$host_name="localhost";
$protocol = "http";
$domain_name = "admin.gamagic.com";

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
$config["default_template"]		= "admin"	; 		// 
$config["default_topn"] 		= 10	; 			// default top n
$config["debug_mod"]			= $debug_mod ; 			// enable all the debug console , N : disable , Y : enable 
$config["max_upload_file_size"]		= 800000000 ; 			//unit is k
$config["expire"]			= "60" ; 			// memcace expire time . sec
$config["domain_name"]			= $domain_name;
$config["protocol"]			= $protocol;
$config["admin_email"]			= array('shaun.huang@snsplus.com') ; 
$config["currency_email"]		= array('shaun.huang@snsplus.com') ; 
$config["transaction_time_limit"]	= 5; //The minimized time between two transaction
############################################################################
# FaceBook
############################################################################
$config['fb']['appid' ]  = "139511249415941";
//$config['fb']['api'   ]  = "";
$config['fb']['secret']  = "afc4dfd7e932210fd55402879764e0f5";


############################################################################
# path
############################################################################
$config["path_admin"]            = "/var/www/admin.gamagic.com/phpmodule";
$config["path_class"] 		= $config["path_admin"]."/class" ; 
$config["path_function"] 	= $config["path_admin"]."/function" ; 
$config["path_include"] 	= $config["path_admin"]."/include" ; 
$config["path_bin"] 		= $config["path_admin"]."/bin" ; 
$config["path_data"] 		= $config["path_admin"]."/data/" ; 
$config["path_cache"] 		= $config["path_admin"]."/data/cache" ; 
$config["path_sources"] 	= $config["path_admin"]."/sources/".$config["module_type"] ; 
$config["path_style"] 		= $config["path_admin"]."/template/".$config["tpl_type"] ; 
$config["path_language"]	= $config["path_admin"]."/language" ; 
$config["path_images"] 		= dirname($config["path_admin"])."/images" ; 
$config["path_products_images"] = dirname(dirname($config["path_admin"]))."/images/products" ; 
$config["path_css"] 		= $config["path_admin"]."/css/" ; 
$config["path_javascript"] 	= $config["path_admin"]."/javascript/" ; 
$config["path_pdf_template"] 	= $config["path_class"]."/tcpdf/template/" ; 
$config["path_eamil_api"] 	= "/usr/bin/" ;  // email api path 

############################################################################
# sql Shaun 
############################################################################
$config["db"][0]["charset"] 	= "utf8" ;
$config["db"][0]["host"] 	= $host_name ;
$config["db"][0]["type"] 	= "mysql";
$config["db"][0]["username"] 	= "root"; 
$config["db"][0]["password"] 	= "2358249" ;
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
$config["db"][9]["dbname"]	= "sns_bulletin" ;//公告區



############################################################################
# memcache 
############################################################################
// session : 為 SESSION Memcache Server , 
$config['memcache']['server_list']['session']['ip']	= '10.67.237.10';
$config['memcache']['server_list']['session']['port']	= 11211;
// ap1 : 為 Application Memcache Server 1 , 
$config['memcache']['server_list']['ap1']['ip']	= '10.67.237.9';
$config['memcache']['server_list']['ap1']['port']	= 11211;
// ap2 : 為 Application Memcache Server 2 , 
$config['memcache']['server_list']['ap2']['ip']	= '10.67.237.9';
$config['memcache']['server_list']['ap2']['port']	= 11211;





############################################################################
# snsPayment
############################################################################
$config['payment']['deposit_page']		= "http://test-pay.snsplus.com/api/getpaymenturl";
$config['payment']['game_coding']		= "OOSPAR";
$config['payment']['platform']			= "snsplus";
$config['payment']['entrance']		= "connect"; // connect : 非 FB 的模式 , noconnect : APP2APP , 都不設 是 FBC



?>
