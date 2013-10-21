<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Model 
{

     function __construct(){
         parent::__construct();
     }

    private $table = 'news';

	public function getAll(){
        //$query = $this->db->get($this->table)->join('category', 'category.id = news.category', 'left');
        $this->db->select('news.name AS `news_name` , news.img_small AS `thumbnail`, news.id AS `news_id` , news.date AS `news_date` , category.name AS `cat_name` , category.id AS `cat_id`');
        $this->db->from($this->table);
        $this->db->join('category', 'category.id = news.category', 'left');

        $query = $this->db->get();     

        return $query->result();
	}

    public function getAllPreview($num, $offset)
    {
        $this->db->select('news.name AS `news_name` , news.id AS `news_id` , news.date AS `news_date` , news.text AS `news_text`,news.img_min AS `thumbnail`,category.name AS `cat_name` , category.id AS `cat_id`');
        $this->db->join('category', 'category.id = news.category', 'left');
        $this->db->order_by('news_id','desc');

        $query = $this->db->get($this->table,$num, $offset);     

        return $query->result();
    }

    public function getAllPreviewCategory($id, $num, $offset){
        //$query = $this->db->get($this->table)->join('category', 'category.id = news.category', 'left');
        $this->db->select('news.name AS `news_name` , news.id AS `news_id` , news.date AS `news_date` , news.text AS `news_text`,news.img_min AS `thumbnail`,category.name AS `cat_name` , category.id AS `cat_id`');
        $this->db->join('category', 'category.id = news.category', 'left');
        $this->db->where('category.id', $id);
        $this->db->order_by('news_id','desc');        

        $query = $this->db->get($this->table, $num, $offset);     

        return $query->result();
    }
   
    public function newNews($data){
        $this->db->insert($this->table, $data);
    }

    public function getByID($id)
    {
        if($id){
            $this->db->select(' news.id as `news_id`,
                                news.name as `news_name`,    
                                news.text as `news_text`,   
                                news.date as `news_date`,    
                                news.img as `news_img`,     
                                news.meta_desc as `meta_desc`,  
                                news.meta_key as `meta_key`,          
                                category.name as `cat_name`, 
                                category.id as `cat_id`');
            $this->db->join('category', 'category.id = news.category', 'left');
            $this->db->where('news.id', $id);
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->result();
            return $result[0];
        } else {
            return false;
        }
    }

    public function getByIDForEdit($id)
    {
        if($id){
            $this->db->select(' news.id as `news_id`,
                                news.name as `name`,    
                                news.text as `text`,   
                                news.date as `date`,    
                                news.img_min as `img_min`,     
                                news.meta_desc as `meta_desc`,  
                                news.meta_key as `meta_key`,          
                                category.name as `category`, 
                                category.id as `cat_id`');
            $this->db->join('category', 'category.id = news.category', 'left');
            $this->db->where('news.id', $id);
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function updateNews($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);        
    }

    public function getCount($id = null)
    {
        if ($id != null) {
            $this->db->where('category', $id);
        }
        return $this->db->count_all_results($this->table);
    }
}

