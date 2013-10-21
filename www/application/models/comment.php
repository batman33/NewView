<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Model {

     function __construct(){
         parent::__construct();
     }

    private $table = 'comment';
/*
SELECT users.name AS user_name, users.id AS user_id, news.id AS news_id, comment.date AS date, comment.text AS text
FROM COMMENT 
LEFT JOIN news ON news.id = comment.news_id
LEFT JOIN users ON users.id = comment.user_id
WHERE comment.news_id =24
LIMIT 0 , 30
*/
    public function getAll($news_id)
    {
        $this->db->select('users.name AS user_name, users.avatar_thums as users_ava, users.id AS user_id, news.id AS news_id, comment.date AS date, comment.text AS text');
        $this->db->join('news', 'news.id = comment.news_id', 'left');
        $this->db->join('users', 'users.id = comment.user_id', 'left');    
        $this->db->where('comment.news_id', $news_id);    
        $query = $this->db->get($this->table);     

        return $query->result();
    }

    public function newComment($data){
        return $this->db->insert($this->table, $data);
    }
}

/* End of file Comment.php */
/* Location: ./application/models/Comment.php */