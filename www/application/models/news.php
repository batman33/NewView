<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Model 
{

     function __construct(){
         parent::__construct();
     }

    private $table = 'news';

	public function getAll(){
        //$query = $this->db->get($this->table)->join('category', 'category.id = news.category', 'left');
        $this->db->select('news.name AS `news_name` , news.img_small AS `thumbnail`, news.id AS `news_id` , news.date AS `news_date`')
                 ->from($this->table)
                 ->order_by('news.date', 'desc');

        $query = $this->db->get();     
        $result = $query->result();

        // Запрос на сервер для взятия всех категорий данной статьи
        foreach ($result as $key) {
            $this->db->join('category', 'category.id = news_category.category_id', 'left');
            $this->db->where('news_category.news_id', $key->news_id);
            $this->db->from('news_category');
            $query_cat = $query = $this->db->get();
            $category = $query_cat->result_array();
            // Переносим в общий массив
            $key->cat_name = '';
            foreach ($category as $keys) {
                if($keys == end($category)) 
                    $key->cat_name .= $keys['name'];
                else 
                    $key->cat_name .= $keys['name'] . ', ';
            }                
        }

        return $result;
	}

    public function getAllPreview($num, $offset)
    {
        $this->db->select('news.name AS `news_name` , news.id AS `news_id` , news.date AS `news_date` , news.text AS `news_text`,news.img_min AS `thumbnail`')
                 ->join('news_category', 'news_category.news_id = news.id', 'left')
                 ->order_by('news.date', 'desc');

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
        return $this->db->insert_id();
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
                                news.meta_key as `meta_key`');
            $this->db->where('news.id', $id);
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->row();

            // Запрос на сервер для взятия всех категорий данной статьи
            $query_cat = $query = $this->db->get_where('news_category', array('news_id' => $id));
            $category = $query_cat->result_array();
            
            // Переносим в общий массив
            $result->cat_id = array();
            foreach ($category as $key) array_push($result->cat_id, $key['category_id']);

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

    public function updateNewsCategory($aData, $id)
    {
        $this->db->delete('news_category', array('news_id' => $id));

        foreach ($aData as $key)
            $this->db->insert('news_category', array('news_id' => $id, 'category_id' => $key));
    }
}

