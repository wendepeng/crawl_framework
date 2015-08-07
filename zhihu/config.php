<?php
/**
 *配置文件
 */
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
	    'login_url' => 'http://www.zhihu.com/login/email',
	    'login_url_regex' => '#http:\/\/www\.zhihu\.com\/login\/email#',
	    'login_cookie_array' => array(
	    	'cap_id' => '"MGQ2MjY0NGM3YTFiNGJkYmIxMjY3NDk0YzE4MTQxMjA=|1438917134|d8bbf81400673800d4deed328a4600a00d16122a"'
	    ),
	    'login_data' => array(
	    	"email" => "970211002@qq.com",
		    "password" => "5iportal",
		    "remember_me" => "true",
		    "_xsrf" => "3b8cca8ee8dbf02766603569e199ffe4"
	    )
    ),
);
