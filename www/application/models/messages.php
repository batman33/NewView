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

		$this->db->order_by('messages.id', 'desc');
		
		$this->db->where('to', $id);
		$this->db->join('users', 'users.id = messages.from', 'left');

        $query = $this->db->get($this->table);
        return $query->result();		
	}

	public function getAllSentMessage($id)
	{
		$this->db->select('
			messages.id as mess_id,
			messages.title as mess_title,
			messages.status as mess_status,
			messages.created as mess_created,
			users.name as u_name,
			users.id as u_id');
		
		$this->db->where('from', $id);
		$this->db->join('users', 'users.id = messages.to', 'left');

        $query = $this->db->get($this->table);
        return $query->result();		
	}


	public function getMessageById($id)
	{
        if($id){

			$this->db->select('
				messages.id as mess_id,
				messages.title as mess_title,
				messages.message as messages,
				messages.status as mess_status,
				messages.created as mess_created,
				users.name as u_name,
				users.id as u_id');

            $this->db->where('messages.id', $id);
			$this->db->join('users', 'users.id = messages.from', 'left');            
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->result();
            return $result[0];
        } else {
            return false;
        }        	
	}

	public function deActive($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
	}

	public function countByUser($id)
	{
		$this->db->where('to', $id);
		$this->db->where('status', '1');
		$this->db->from($this->table); 
		$query = $this->db->get();
		return $query->num_rows();
	}

}

/* End of file messages.php */
/* Location: ./application/models/messages.php */