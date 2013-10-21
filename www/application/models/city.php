<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends CI_Model {

     function __construct(){
         parent::__construct();
     }

    private $table = 'city';

    public function autocomplete($val)
    {
    	$this->db->select('city_name_ru')
    			 ->like('city_name_ru', $val)
    			 ->limit(10);
		$query = $this->db->get($this->table);

        return $query->result();
    }

    public function cheskExist($name)
    {
        if($name){        
            $this->db->where('city_name_ru', $name);
            $this->db->limit(1);

            $query = $this->db->get($this->table);

            $result = $query->result();
            if ($query->num_rows() == 1) return $result[0];    
            else return "false";
        } else {
            return "false";
        }         
    }
}

/* End of file city.php */
/* Location: ./application/models/city.php */