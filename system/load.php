<?php
/**
 * 加载system路径
 */
set_time_limit(0);

define('SYSTEM_PATH', dirname(__FILE__).'/../system');
define('DATA_PATH', dirname(__FILE__).'/../data');

$config = require_once(APP_PATH.'/config.php');

//定义站点名和工作目录
define('WEBSITE_NAME', $config['website']['name']);
define('WEBSITE_LOGIN_ENABLE', $config['website']['login_enable']);
define('WEBSITE_LOGIN_URL', $config['website']['login_url']);
define('WORk_DIR', DATA_PATH.'/'.WEBSITE_NAME);
define('WEBSITE_START_URL', $config['website']['start_url']);

//加载phpcrawl引擎
require_once(dirname(__FILE__)."/PHPCrawl/libs/PHPCrawler.class.php");

//加载redis数据库实例 $redis
require_once(dirname(__FILE__)."/libs/redis.class.php");

//加载sqlite数据库实例 $sqlite
require_once(dirname(__FILE__)."/libs/sqlite.class.php");

//加载model
require_once(dirname(__FILE__)."/model/model.class.php");

//加载phpQuery
require_once(dirname(__FILE__)."/libs/phpQuery.php");

//加载mysql数据库实例 $mysql
require_once(dirname(__FILE__)."/libs/mysql.class.php");

function __crawl_autoload($strClassName) {
	$strClassName = ucwords($strClassName);
	
	if($strClassName === 'Crawler'){
		require_once(SYSTEM_PATH.'/crawler.class.php');
	}

	if($strClassName[0] === 'M'){
		if(file_exists(SYSTEM_PATH.'/model/'.strtolower($strClassName).'.class.php')){
			require_once(SYSTEM_PATH.'/model/'.strtolower($strClassName).'.class.php');
			return ;
		}

		if(file_exists(APP_PATH.'/model/'.strtolower($strClassName).'.class.php')){
			require_once(APP_PATH.'/model/'.strtolower($strClassName).'.class.php');
			return ;	
		}

		die('can not find '.$strClassName.' class!');
	}
}

spl_autoload_register('__crawl_autoload');
?>
