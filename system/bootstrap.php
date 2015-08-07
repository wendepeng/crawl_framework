<?php
/**
 * 加载system路径
 */
set_time_limit(0);

//加载phpcrawl引擎
include(dirname(__FILE__)."/PHPCrawl/libs/PHPCrawler.class.php");

//加载redis数据库实例 $redis
include(dirname(__FILE__)."/libs/redis.class.php");

//加载sqlite数据库实例 $sqlite
include(dirname(__FILE__)."/libs/sqlite.class.php");

//加载model
include(dirname(__FILE__)."/model/model.php");
?>
