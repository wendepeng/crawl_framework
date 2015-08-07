<?php
/**
 * 默认的抓取类
 */
class Crawler extends PHPCrawler 
{
  //在头部进行过滤
  function handleHeaderInfo(PHPCrawlerResponseHeader $header)
  {
    $url = $header->source_url;

    if ($header->content_type != "text/html"){
      return -1;
    }

    //可以对url进行过滤
    // if(!preg_match('/^http\:\/\/www\.zhihu\.com\/people\/([a-zA-Z0-9-_]+)$/', $url)){
    //   echo "drop un_user_center url: $url ...\n";  
    //   return ;
    // }
  }

  //已经加载了content
  function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
  {
    $url = $DocInfo->url;
    $content = $DocInfo->content;

    //处理contnet内容

    //输出提示
    echo "process the page: $url done!\n";
    flush();
  } 
}
