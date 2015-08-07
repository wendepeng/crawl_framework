<?php
/**
 *入口文件，万物之始！
 */

define('APP_PATH', dirname(__FILE__));
require_once(dirname(__FILE__).'/../system/load.php');
require_once(dirname(__FILE__).'/crawler.class.php');

$crawler = new MyCrawler();
$crawler->enableResumption();
$crawler->setWorkingDirectory(WORk_DIR);
$crawler->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);

if(!is_dir(WORk_DIR)){
      @mkdir(WORk_DIR) or die('不能创建网站目录');
}

$guid_file = WORk_DIR."/guid.tmp";

if (!file_exists($guid_file))
{
  $crawler_id = $crawler->getCrawlerId();
  file_put_contents($guid_file, $crawler_id);
}else
{
  $crawler_id = file_get_contents($guid_file);
  $crawler->resume($crawler_id);
}

$crawler->setURL(WEBSITE_START_URL);

if(WEBSITE_LOGIN_ENABLE){
	$crawler->addPostData($config['website']['login_url_regex'], $config['website']['login_data']);
}

$crawler->addContentTypeReceiveRule("#text/html#");

$crawler->addURLFilterRule("#\.(css|js|ico|jpg|jpeg)$# i");

$crawler->enableCookieHandling(true);

$crawler->go();

unlink($guid_file);
// At the end, after the process is finished, we print a short
// report (see method getProcessReport() for more information)
$report = $crawler->getProcessReport();

if (PHP_SAPI == "cli") $lb = "\n";
else $lb = "<br />";
    
echo "Summary:".$lb;
echo "Links followed: ".$report->links_followed.$lb;
echo "Documents received: ".$report->files_received.$lb;
echo "Bytes received: ".$report->bytes_received." bytes".$lb;
echo "Process runtime: ".$report->process_runtime." sec".$lb; 
echo "Abort reason: ".$report->abort_reason; 

?>
