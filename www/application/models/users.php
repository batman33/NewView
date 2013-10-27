<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model 
{

	function __construct()
	{
	 parent::__construct();
	}

	// Таблица данной модели
	private $table = 'users';

	// Метод входа пользователя
	// @login - логин введенный пользователем
	// @password - пароль введеный пользователем
	// @role - роль пользователя, для проверки прав на вход
	// return boolen
	public function enterUsers($login, $password, $role)
    {
		$this->db->where('login', $login);
		$this->db->where('password', $password);
		if($role != 'any') 
			$this->db->where('role', $role);
		$this->db->limit('1');
        $query = $this->db->get($this->table); //Выбираем все записи из таблицы user
		if ($query->num_rows() == 1){
			return $query->row();
		} else return "false";
    }

    // Метод получения всех пользователей
    // return - Object -> array
	public function getAll()
	{
		$this->db->select('users.id, users.login, users.password, users.role, users.name, users.email, users.skype, users.birthday, users.avatar_full, users.avatar_small, users.avatar_thums, city.id_region, city.id_country, city.oid, city.city_name_ru, city.city_name_en');
		$this->db->join('city', 'city.id = users.city', 'left');
        $query = $this->db->get($this->table);
        return $query->result();
	}

    // Метод получения одного пользователя
	// @id - id пользователя из сессии 
    // return - Object
	public function getByID($id)
	{
        if($id){
            $this->db->where('id', $id);
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->result();
            return $result[0];
        } else {
            return false;
        }
	}

	// Метод получения пользователя и его полное места нахождения
	// @id - id пользователя из сессии 
    // return - Object
	public function getUserCountryRegion($id)
	{
        if($id){		
			$this->db->select(' users.name 				AS 	`name`, 
								users.login 			AS 	`login`, 
								users.id 				AS 	`id`, 
								users.role 				AS 	`role`, 
								users.email 			AS 	`email`, 
								users.skype 			AS 	`skype`, 
								users.birthday 			AS 	`birthday`, 
								users.avatar_small 		AS  `avatar_small`,
								users.avatar_thums 		AS  `avatar_thums`,
								city.city_name_ru 		AS 	`city_name`, 
								city.id 				AS 	`city_id`, 
								region.region_name_ru 	AS 	`region_name`, 
								region.id 				AS 	`region_id`, 
								country.country_name_ru AS 	`country_name`, 
								country.id 				AS 	`country_id`'
								);
			$this->db->join('city', 'city.id = users.city', 'left');
			$this->db->join('region', 'region.id = city.id_region', 'left');
			$this->db->join('country', 'country.id = city.id_country', 'left');
	        $this->db->where('users.id', $id);
            $this->db->from($this->table);       
            $query = $this->db->get();
            $result = $query->result();
            return $result[0];
        } else return false;
	}

	// Метод проверки существования данных в табл
	// @data - данные для проврки в бд
	// @how - поле которое проверяется
	// return boolen
	public function checkExistence($data, $how)
	{

		$this->db->where($how, $data);
		$this->db->limit(1);
		$query = $this->db->get($this->table);

		return $query->num_rows() == 1 ? 'false' : 'true'; //$query->result();
	}

	// Метод создания нового пользователя
	// @data - Данные пользователя
	// return boolen
	public function newUser($data)
	{
        return $this->db->insert($this->table, $data);		
	}

	// Метод создания нового пользователя
	// @data - Данные пользователя для обнавления
	// return boolen	
	public function updateUser($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);	
	}

	// Метод создания нового пользователя
	// @data - Данные пользовательского аватара
	// return boolen
	public function updateAvatar($data, $id)
	{	
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	// Метод получение всех имен по SQL запросу с like
	// @val - Введенные символы на UI пользователем
	// return Object
    public function autocomplete($val)
    {
    	$this->db->select('login, id, avatar_thums, name')
    			 ->like('login', $val)
    			 ->or_like('name', $val)
    			 ->limit(10);
		$query = $this->db->get($this->table);

        return $query->result();
    }
}