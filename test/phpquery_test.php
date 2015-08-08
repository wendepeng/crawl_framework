<?php 
	include dirname(__FILE__).'/../system/libs/phpQuery.php'; 
	// phpQuery::newDocumentFile('http://www.zhihu.com/people/da-rou-rou-rou'); 
	phpQuery::newDocumentFile('/tmp/hahah.html'); 
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
	// var_dump($nums_arr);die;
	
	// if(!isset($nums_arr[1])){
	// 	file_put_contents('/tmp/hah', $content);
	// 	exit;
	// }

	$ask         = $nums_arr[1];
	$answer      = $nums_arr[2];
	$special     = $nums_arr[3];
	$favorite    = $nums_arr[4];
	$public_edit = $nums_arr[5];

	$query_result = pq(".zm-profile-side-following a");
	// file_put_contents('/tmp/xx', var_export($query_result, TRUE));die;
	$nums_arr = array();
	foreach ($query_result as $value) {
	  $nums_arr[] = pq($value)->find('strong')->html();
	}
	$focus     = $nums_arr[0];
	$followers = $nums_arr[1];

	$title_arr = array();
	$query_result = pq(".zm-profile-side-topics a");
	foreach ($query_result as $value) {
		$title_arr[] = pq($value)->find('img')->attr('title');
	}

?>