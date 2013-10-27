<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Model 
{

     function __construct(){
         parent::__construct();
     }

    private $table = 'category';

	public function getAll(){
        $query = $this->db->get($this->table);
        return $query->result();
	}

    public function getDropDownList(){
        $query = $this->db->get($this->table);
        
        foreach($query->result() as $drop){
            $drop_down_lsit[$drop->id] = $drop->name;
        }

        return $drop_down_lsit;
    }
    
    public function newCategory($data){
        $this->db->insert($this->table, $data);
    }

    public function update($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);        
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);        
    }

    public function getNameByID($id)
    {
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        $res = $query->row();
        return $res->name;
    }
}