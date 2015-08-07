<?php
class MyCrawler extends PHPCrawler 
{
  function __construct()
  {
    parent::__construct();
    if(!$is_login){
      $this->PageRequest->cookie_array["cap_id"] = '"MGQ2MjY0NGM3YTFiNGJkYmIxMjY3NDk0YzE4MTQxMjA=|1438917134|d8bbf81400673800d4deed328a4600a00d16122a"';
    }
  }

  function handleHeaderInfo(PHPCrawlerResponseHeader $header)
  {
    if ($header->content_type != "text/html"){
      return -1;
    }
  }

  function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
  {
    $url = $DocInfo->url;

    if($url == LOGIN_URL && !$is_login){
        $
    }

    //过滤非用户中心的页面
    if(!preg_match('/^http\:\/\/www\.zhihu\.com\/people\/([a-zA-Z0-9-_]+)$/', $url)){
      echo "drop un_user_center url: $url ...\n";  
      return ;
    }

    //保存到redis
    save_page($DocInfo);

    //输出提示
    echo "done with: $url ...\n";
    flush();
  } 
}
