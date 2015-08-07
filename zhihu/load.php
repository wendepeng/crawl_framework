<?php
/**
 *加载配置文件和使用魔术方法加载model类和Crawler类
 */
define('BASE_PATH', dirname(__FILE__));
define('SYSTEM_PATH', dirname(__FILE__).'/../system');
define('DATA_PATH', dirname(__FILE__).'/../data');

$config = require_once(BASE_PATH.'/config.php');

//定义站点名和工作目录
define('WEBSITE_NAME', $config['website']['name']);
define('WEBSITE_LOGIN_ENABLE', $config['website']['login_enable']);
define('WEBSITE_LOGIN_URL', $config['website']['login_url']);
define('WORk_DIR', DATA_PATH.'/'.WEBSITE_NAME);
define('WEBSITE_START_URL', $config['website']['start_url']);

require_once(SYSTEM_PATH."/bootstrap.php");

function __crawl_autoload($strClassName) {
	$strClassName = ucwords($strClassName);
	
	if($strClassName === 'Crawler'){
		if(file_exists(SYSTEM_PATH.'/crawler.php')){
			require_once(SYSTEM_PATH.'/crawler.php');
			return ;
		}

		if(file_exists(BASE_PATH.'/crawler.php')){
			require_once(SYSTEM_PATH.'/crawler.php');
			return ;	
		}

		die('can not find crawler class!');
	}

	if($strClassName[0] === 'M'){
		if(file_exists(SYSTEM_PATH.'/model/'.$strClassName.'.php')){
			require_once(SYSTEM_PATH.'/model/'.$strClassName.'.php');
			return ;
		}

		if(file_exists(BASE_PATH.'/model/'.$strClassName.'.php')){
			require_once(SYSTEM_PATH.'/model/'.$strClassName.'.php');
			return ;	
		}

		die('can not find '.$strClassName.' class!');
	}
}

spl_autoload_register('__crawl_autoload');

