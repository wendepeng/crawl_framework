<?php
return array (
	'mysql' => array (
		'hostname' => '127.0.0.1',
		'database' => 'crawl_zhihu',
		'username' => 'root',
		'password' => '',
		'tablepre' => 'zh_',
		'charset' => 'utf8',
		'type' => 'mysql',
		'debug' => true,
		'pconnect' => 0,
		'autoconnect' => 0
    ),
    'redis' => array (
    	'hostname' => '127.0.0.1',
    	'port' => 6379
    ),
    'website' => array(
    	'name' => "zhihu",
	    'start_url' => 'http://www.zhihu.com/',
	    'login_enable' => FALSE,
	    'login_url' => 'http://www.zhihu.com/login/email'
    ),
);
