<?php 
include("phpQuery.php");
include("redis.class.php");
include("mysql.class.php");

$redis = new redis();
$redis->connect('127.0.0.1','6379');
$mysql = new mysql();

while (true) {
	//从redis取得数据
	$item_str = $redis->lpop('crawl_zhihu');
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
		echo "the prase_data return empty array\n";
		continue;
	}
	if(!$mysql->insert($result,'zh_user')){
		echo "Error: ".$mysql->error()." \n";
	}
}

function prase_data($content) {
	//检测content的html格式是否正确
	if(!preg_match('/<\!DOCTYPE html>.*<\/html>/s', $content)){
		echo "the content formatting is not right,stop prasing...\n";
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

	$profiles = $query_result->find('.profile-navbar a');
	$nums_arr = array();
	foreach ($profiles as $value) {
	  $nums_arr[] = pq($value)->find('span.num')->html();
	}
	
	if(!isset($nums_arr[1])){
		file_put_contents('/tmp/hah', $content);
		exit;
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
		'followers'		=> addslashes($followers)
	);
}
