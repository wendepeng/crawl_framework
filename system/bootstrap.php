<?php
/**
 * 加载system路径
 */
set_time_limit(0);
define('SYSTEM_PATH',dirname(__FILE__));

//加载phpcrawl引擎
include(SYSTEM_PATH."/PHPCrawl/libs/PHPCrawler.class.php");

//加载redis数据库实例 $redis
include(SYSTEM_PATH."/libs/redis.class.php");

//加载sqlite数据库实例 $sqlite
include(SYSTEM_PATH."/libs/sqlite.class.php");

//加载model
include(SYSTEM_PATH."/model/model.php");
?>
