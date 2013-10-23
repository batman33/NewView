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
			users.id as u_id')
			     ->order_by('messages.id', 'desc')
			     ->where('to', $id)
			     ->join('users', 'users.id = messages.from', 'left');

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
			users.id as u_id')
		         ->order_by('messages.id', 'desc')
				 ->where('from', $id)
		         ->join('users', 'users.id = messages.to', 'left');

        $query = $this->db->get($this->table);
        return $query->result();		
	}


	public function getMessageById($id, $status)
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

            if($status == 'inbox')
				$this->db->join('users', 'users.id = messages.from', 'left');     
			else
				$this->db->join('users', 'users.id = messages.to', 'left'); 

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
		$this->db->where('to', $id)
		         ->where('status', '1')
		         ->from($this->table); 
		$query = $this->db->get();
		return $query->num_rows();
	}

}

/* End of file messages.php */
/* Location: ./application/models/messages.php */