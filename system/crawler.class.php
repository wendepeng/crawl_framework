<?php
/**
 * 默认的抓取类
 */
require_once(dirname(__FILE__).'/load.php');

class Crawler extends PHPCrawler 
{
  function __construct()
  {
    parent::__construct();
    if(WEBSITE_LOGIN_ENABLE){
      foreach ($login_cookie_array as $key => $value) {
        $this->PageRequest->cookie_array[$key] = $value;
      }
    }
  }

  function handleHeaderInfo(PHPCrawlerResponseHeader $header)
  {
    $url = $header->source_url;

    if ($header->content_type != "text/html"){
      return -1;
    }
  }

  function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
  {
    $url = $DocInfo->url;
    $content = $DocInfo->content;

    //处理contnet内容
    //...
    
    //输出提示
    echo "process the page: $url done!\n";
    flush();
  } 

  function lets_go(){
    $this->enableResumption();
    $this->setWorkingDirectory(WORk_DIR);
    $this->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);

    if(!is_dir(WORk_DIR)){
          @mkdir(WORk_DIR) or die('不能创建网站目录');
    }

    $guid_file = WORk_DIR."/guid.tmp";

    if (!file_exists($guid_file))
    {
      $crawler_id = $this->getCrawlerId();
      file_put_contents($guid_file, $crawler_id);
    }else
    {
      $crawler_id = file_get_contents($guid_file);
      $this->resume($crawler_id);
    }

    $this->setURL(WEBSITE_START_URL);

    if(WEBSITE_LOGIN_ENABLE){
      $this->addPostData($config['website']['login_url_regex'], $config['website']['login_data']);
    }

    $this->setParams();

    $this->enableCookieHandling(true);

    $this->go();

    unlink($guid_file);
    // At the end, after the process is finished, we print a short
    // report (see method getProcessReport() for more information)
    $report = $this->getProcessReport();

    if (PHP_SAPI == "cli") $lb = "\n";
    else $lb = "<br />";
        
    echo "Summary:".$lb;
    echo "Links followed: ".$report->links_followed.$lb;
    echo "Documents received: ".$report->files_received.$lb;
    echo "Bytes received: ".$report->bytes_received." bytes".$lb;
    echo "Process runtime: ".$report->process_runtime." sec".$lb; 
    echo "Abort reason: ".$report->abort_reason; 
  }

  function setParams(){
    $this->addContentTypeReceiveRule("#text/html#");
    $this->addURLFilterRule("#\.(css|js|ico|jpg|jpeg)$# i");
  }
}
