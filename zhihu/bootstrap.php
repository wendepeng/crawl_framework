<?php
/**
 *入口文件
 */
define('APP_PATH', dirname(__FILE__));
require_once(dirname(__FILE__).'/../system/crawler.class.php');

class MyCrawler extends Crawler{

	function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
	{
		$url = $DocInfo->url;
		$content = $DocInfo->content;

		if(empty($content)) return ;
		
		//处理contnet内容
		global $redis;
		$redis->lpush('zhihu_pages',json_encode(array(
			'url' => $url,
			'content' => $content
		)));

		//输出提示
		echo "process the page: $url done!\n";
		flush();
	} 
}

$myCrawler = new MyCrawler();
$myCrawler->lets_go();