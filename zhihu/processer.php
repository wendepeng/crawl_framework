<?php 
/**
 *数据处理进程
 */
define('APP_PATH', dirname(__FILE__));
require_once(dirname(__FILE__).'/../system/crawler.class.php');


$redis = new redis();
$redis->connect('127.0.0.1','6379');

while (true) {
	//从redis取得数据
	$item_str = $redis->lpop('zhihu_pages');
	if(empty($item_str)){
		echo "get redis with nothing .... \n";
		sleep(1);
		continue;
	}

	if(!is_array($item_str)){
		$item = json_decode($item_str,TRUE);
		// empty($item) ? file_put_contents('/tmp/item_str', $item_str) : file_put_contents('/tmp/item_str', var_export($item,TRUE)) ;
	}

	if(empty($item)){
		echo "the item json_decode to be empty \n";
		continue;
	}

	//提示信息
	echo 'proccess data of url : '.$item['url']."\n";

	//解析数据 & 存入数据库
	$result = prase_data($item['content']);	

	if(empty($result)){
		continue;
	}

	$result['user_identify'] = $item['url'];

	if(!$mysql->insert($result,'zh_user')){
		echo "Error: ".$mysql->error()." \n";
	}
}

function prase_data($content) {
	//检测content的html格式是否正确
	if(!preg_match('/<\!DOCTYPE html>.*<\/html>/s', $content)){
		echo "the html tag formatting is wrong,skip the page\n";
		return array();
	}

	phpQuery::newDocumentLocalFile($content);
	$query_result = pq(".zm-profile-header");
	$name = $query_result->find('span.name')->html();
	$intro = $query_result->find('span.bio')->html();
	$sex = $query_result->find('.zm-profile-header-op-btns button.zm-rich-follow-btn')->html();
	if ($sex === '关注他'){
	  $sex = 1;
	}else if($sex === '关注她'){
	  $sex = 0;
	}else{
	  $sex = 2;
	}

	$zan = $query_result->find('.zm-profile-header-user-agree strong')->html();
	$thank = $query_result->find('.zm-profile-header-user-thanks strong')->html();

	$location = $query_result->find('.location a')->html();
	$business = $query_result->find('.business a')->html();
	$employment = $query_result->find('.employment a')->html();
	$position = $query_result->find('.position a')->html();
	$education = $query_result->find('span.education')->attr('title');
	$education_extra = $query_result->find('span.education-extra')->attr('title');

	$profiles = $query_result->find('.profile-navbar a');
	$nums_arr = array();
	foreach ($profiles as $value) {
	  $nums_arr[] = pq($value)->find('span.num')->html();
	}

	$ask         = $nums_arr[1];
	$answer      = $nums_arr[2];
	$special     = $nums_arr[3];
	$favorite    = $nums_arr[4];
	$public_edit = $nums_arr[5];

	$query_result = pq(".zm-profile-side-following a");
	$nums_arr = array();
	foreach ($query_result as $value) {
	  $nums_arr[] = pq($value)->find('strong')->html();
	}
	$focus = $nums_arr[0];
	$followers = $nums_arr[1];

	$topics = '';
	$query_result = pq(".zm-profile-side-topics a");
	foreach ($query_result as $value) {
		$topics .= pq($value)->find('img')->attr('title').',';
	}

	return array(
		'name' 			=> addslashes($name),
		'intro'			=> addslashes($intro),
		'sex'			=> addslashes($sex),
		'zan'			=> addslashes($zan),
		'thank'			=> addslashes($thank),
		'ask'			=> addslashes($ask),
		'answer'		=> addslashes($answer),
		'special'		=> addslashes($special),
		'favorite'		=> addslashes($favorite),
		'public_edit'	=> addslashes($public_edit),
		'focus'			=> addslashes($focus),
		'followers'		=> addslashes($followers),
		'business'		=> addslashes($business),
		'location'		=> addslashes($location),
		'employment'	=> addslashes($employment),
		'position'		=> addslashes($position),
		'topics'		=> addslashes($topics),
		'education'		=> addslashes($education),
		'education_extra' 		=> addslashes($education_extra)
	);
}
