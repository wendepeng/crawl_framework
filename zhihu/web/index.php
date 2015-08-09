<?php

if(empty($_POST['action'])){
	return json_encode(array('status' => -1, 'msg' => 'params error'));
}

define('APP_PATH', dirname(__FILE__).'/../');
require_once(dirname(__FILE__).'/../../system/load.php');

$action = addslashes($_POST['action']);
if($action === 'education'){
	$user = new MUser();
	$education_arr = $user->select('count(*) as count,education','education is not null and length(education) > 1','','count(education) desc ','education');
	$education_arr = array_slice($education_arr, 0, 10);
	$count_arr = array_column($education_arr,'count');
	$school_arr = array_column($education_arr,'education');

	array_walk($count_arr, 'array_intval');

	echo json_encode(array(
		'name'  =>$school_arr,
		'count' => $count_arr
	));
} else if($action === 'employment'){
	$user = new MUser();
	$employment_arr = $user->select('count(*) as count,employment','employment is not null and length(employment) > 1','','count(employment) desc ','employment');
	$employment_arr = array_slice($employment_arr, 0, 20);
	$count_arr = array_column($employment_arr,'count');
	$school_arr = array_column($employment_arr,'employment');

	array_walk($count_arr, 'array_intval');

	echo json_encode(array(
		'name'  =>$school_arr,
		'count' => $count_arr
	));
}


function array_intval(&$value,$key){
	$tmp = intval($value);
	$value = $tmp;
}
