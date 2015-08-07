<?php
/**
 *入口文件
 */
define('APP_PATH', dirname(__FILE__));
require_once(dirname(__FILE__).'/../system/crawler.class.php');

class MyCrawler extends Crawler{
	public function __construct(){
		parent::__construct();
		#do nothing ...
	}
}

$myCrawler = new MyCrawler();
$myCrawler->lets_go();