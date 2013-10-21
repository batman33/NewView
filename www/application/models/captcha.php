<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Model {

	private $table = 'captcha';

	function __construct()
	{
		parent::__construct();
	}

	function add($data){
		$query = $this->db->insert_string($this->table, $data);
		$this->db->query($query);
	}

	function check($cap){
		// Сначало, удаление старыз каптч
		$expiration = time()-7200; // лимит 2 часа
		$this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);

		// Проверка на существования капчи:
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($cap, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();

		if ($row->count == 0) return 'false'; else return 'true';
	}

}

/* End of file captcha.php */
/* Location: ./application/models/captcha.php */