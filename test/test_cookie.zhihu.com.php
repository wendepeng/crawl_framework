<?php

set_time_limit(0);
include("PHPCrawl/libs/PHPCrawler.class.php");
include("redis.class.php");

define('WEBSITE', 'kkkk');
define('IMG_DIR', '/tmp/');

class MyCrawler extends PHPCrawler 
{
  function __construct()
  {
    parent::__construct();
    $this->login();
    
    //在此处添加想要的cookies Note:Use "anysite.net" here, NOT "www.anysite.net"
    // $this->PageRequest->cookie_array["_xsrf"] = "3b8cca8ee8dbf02766603569e199ffe4";
    // $this->PageRequest->cookie_array["tc"] = "AQAAAJ/7AQ7APQwAAhFuJMHNVX13Mfan";
    // $this->PageRequest->cookie_array["_za"] = "e5d15e48-5035-48f2-bb97-6151ec084acf";
   // $this->PageRequest->cookie_array["cap_id"] = '"MGQ2MjY0NGM3YTFiNGJkYmIxMjY3NDk0YzE4MTQxMjA=|1438917134|d8bbf81400673800d4deed328a4600a00d16122a"';
//     $this->PageRequest->cookie_array["unlock_ticket"] = '"QUFBQWtVUXFBQUFYQUFBQVlRSlZUUlp5dzFXMGhIZ3FBYTMtN2kyWXJ2NlljaWJxVGRJNFBRPT0=|1438870286|b2fd7596fc9
// 8ab8d42c5edc4a34e8336b89c46e5"';
//     $this->PageRequest->cookie_array["z_c0"] = '"QUFBQWtVUXFBQUFYQUFBQVlRSlZUUTM0NmxXcVVaQVkwd1daT3p4MlNPbENzdXFxOERPNVBRPT0=|1438870286|518756f92b8
// 19cac79048db8a36ecd3ebe7b0604"';
//     $this->PageRequest->cookie_array["q_c1"] = 'e5fe60e2300e46f4a3d90bb17a810892|1438858942000|1438858942000';
  }

  function login(){
    echo "Crawling the login url : http://www.zhihu.com/login/email begin\n";
    $this->PageRequest->cookie_array["cap_id"] = '"MGQ2MjY0NGM3YTFiNGJkYmIxMjY3NDk0YzE4MTQxMjA=|1438917134|d8bbf81400673800d4deed328a4600a00d16122a"';
    $this->initCrawlerProcess();
    $this->processUrl(new PHPCrawlerURLDescriptor('http://www.zhihu.com/login/email', '', '', '', 'http://www.zhihu.com/', '1'));
    echo "Crawling the login url : http://www.zhihu.com/login/email end\n";
  }

  function handleHeaderInfo(PHPCrawlerResponseHeader $header)
  {
    if ($header->content_type != "text/html"){
      return -1;
    }

    // if($header->source_url == 'http://www.zhihu.com/login/email'){
    //   $this->login();
    // }
  }

  function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
  {
    $url = $DocInfo->url;
     var_dump($DocInfo);die;
    if(strpos($url, 'sizhuren/followees') !== FALSE){
      
      file_put_contents('/tmp/jj', $DocInfo->content);
      exit;
    }

    // 过滤非用户中心的页面
    if(!preg_match('/^http\:\/\/www\.zhihu\.com\/people\/([a-zA-Z0-9-_]+)$/', $url)){
      echo "drop un_user_center url: $url ...\n";  
      return ;
    }

    //保存到redis
    // save_page($DocInfo);

    //输出提示
    echo "done with: $url ...\n";
    flush();
  } 
}

// Now, create a instance of your class, define the behaviour
// of the crawler (see class-reference for more options and details)
// and start the crawling-process.

$crawler = new MyCrawler();
$crawler->enableResumption();
$crawler->setFollowRedirects(TRUE);
$crawler->enableCookieHandling(TRUE);
$crawler->setWorkingDirectory(IMG_DIR.WEBSITE.'/');
$crawler->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);

if(!is_dir(IMG_DIR.WEBSITE)){
      @mkdir(IMG_DIR.WEBSITE) or die('不能创建网站目录');
}

$guid_file = IMG_DIR.WEBSITE."/guid.tmp";
if (!file_exists($guid_file))
{
  $crawler_id = $crawler->getCrawlerId();
  file_put_contents($guid_file, $crawler_id);
}else
{
  $crawler_id = file_get_contents($guid_file);
  $crawler->resume($crawler_id);
}

$crawler->setURL("http://www.zhihu.com");
$post_data = array("email" => "970211002@qq.com", "password" => "5iportal", "remember_me" => "true", "_xsrf" => "3b8cca8ee8dbf02766603569e199ffe4");
$crawler->addPostData("#http:\/\/www\.zhihu\.com\/login\/email#", $post_data);

$crawler->addContentTypeReceiveRule("#text/html#");

$crawler->addURLFilterRule("#\.(css|js|ico|jpg|jpeg)$# i");

$crawler->setUserAgentString("Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:38.0) Gecko/20100101 Firefox/38.0");



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
    $url = $DocInfo->url;
    $content = $DocInfo->content;

    $redis = new RedisClient();
    $redis = $redis->lpush('crawl_zhihu',json_encode(array('url' => $url, 'content' => $content ))); 
}
?>
