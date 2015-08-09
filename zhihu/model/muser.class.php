<?php
class MUser extends model{

	public function __construct(){
		parent::__construct();
		$this->table_name = 'zh_user';
	}
}