<?php
class model{
	public $table_name = '';
	public $db = null;

	public function __construct(){
		global $mysql;
		$this->db = $mysql;
	}

	public function select($data, $where = '', $limit = '', $order = '', $group = '', $key = ''){
		return $this->db->select($data, $this->table_name, $where, $limit, $order, $group, $key);
	}
}