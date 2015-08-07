<?php
include(dirname(__FILE__).'/load.php');

$crawler = new Crawler();
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

// $crawler->addPostData("#http:\/\/www\.zhihu\.com\/login\/email#", array("email" => "970211002@qq.com", "password" => "5iportal", "remember_me" => "true", "_xsrf" => "3b8cca8ee8dbf02766603569e199ffe4"));

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

if ($report->abort_reason == 1){
  echo "Abort reason: ABORTREASON_PASSEDTHROUGH"; 
}else{
  echo "Abort reason: ".$report->abort_reason; 
}



//解析并保存网页信息
function save_page($DocInfo){
    global $redis;
    $url = $DocInfo->url;
    $content = $DocInfo->content;

    
    $redis = $redis->lpush('crawl_zhihu',json_encode(array('url' => $url, 'content' => $content ))); 
}


?>
