<?php
/**
 * 默认的抓取类
 */
require_once(dirname(__FILE__).'/load.php');

class Crawler extends PHPCrawler 
{
  public $config = array();
  function __construct()
  {
    global $config;
    $this->config = $config;

    parent::__construct();
    if(WEBSITE_LOGIN_ENABLE){
      foreach ($this->config['website']['login_cookie_array'] as $key => $value) {
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

    $FLAG = FALSE;
    foreach ($this->config['website']['crawl_url_regex'] as $url_regex) {
      if(preg_match($url_regex, $url)){
        $FLAG = TRUE ;
      }
    }

    if(!$FLAG){
      echo "Passed url: $url Passed\n";
      return -1;
    } 
  }

  function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
  {
    $url = $DocInfo->url;
    $content = $DocInfo->content;

    if(empty($content)) return ;
    
    //处理contnet内容
    //...
    
    //输出提示
    echo "process the page: $url done!\n";
    flush();
  } 

  function lets_go(){
    $this->enableResumption();

    if(!is_dir(WORk_DIR)){
          @mkdir(WORk_DIR) or die('不能创建网站目录');
    }
    $this->setWorkingDirectory(WORk_DIR.'/');
    $guid_file = WORk_DIR."/guid.tmp";
    $this->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);

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
      $this->addPostData($this->config['website']['login_url_regex'], $this->config['website']['login_data']);
    }

    $this->setParams();

    $this->enableCookieHandling(true);

    //需要安装几个php扩展，参考:http://phpcrawl.cuab.de/requirements.html
    $this->goMultiProcessed(9); 

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
