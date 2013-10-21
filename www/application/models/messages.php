<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	// Таблица данной модели
	private $table = 'messages';

	public function newMessage($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function getAllMessage($id)
	{
		$this->db->select('
			messages.id as mess_id,
			messages.title as mess_title,
			messages.status as mess_status,
			messages.created as mess_created,
			users.name as u_name,
			users.id as u_id');
		
		$this->db->where('to', $id);
		$this->db->join('users', 'users.id = messages.from', 'left');

        $query = $this->db->get($this->table);
        return $query->result();		
	}

}

/* End of file messages.php */
/* Location: ./application/models/messages.php */