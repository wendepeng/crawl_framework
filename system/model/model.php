<?php
class model{
	public $table_name = '';
	public $db = null;

	public function __construct(){
		$this->$db = $sqlite;
	}

	public function get_one(){
		return $this->db->query("select * from ".$this->table_name." limit 1 ;")->fetch();
	}

	public function get($id = 0){
		return $this->db->query("select * from ".$this->table_name." where id = $id ;")->fetch();
	}	
}