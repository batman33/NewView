<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function Home()
    {
        parent::__construct();
        //Проверка на авторизованность
        //if(!$this->session->userdata('username'))
        //    echo 'No User';
        //else echo 'Hello ' . $this->session->userdata('username') . '!';
    }

	public function index()
	{
		$this->load->model('news');

		// Пагинация
		$config['base_url'] = '/pagination/page/';
		$config['total_rows'] = $this->news->getCount();
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// Вывод сообщение на главной странице
		$message_type = $this->uri->segment(2);
		if($message_type == 'successReg'){
			$data['message'] = '<div class="success">Регистрация успешна!</div>';
		} elseif($message_type == 'no-permision'){
			$data['message'] = '<div class="error">Зарегестрируйтесь для просмотра!</div>';
		}


		$header['title'] = 'Главная';
		$data['list_news'] = $this->news->getAllPreview($config['per_page'], $this->uri->segment(3));

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('index_action',$data);
		$this->load->view('block/bottom');
	}

	public function singleNews()
	{
		$id = $this->uri->segment(2);
		$this->load->model('news');
		$this->load->model('comment');

		$data['comment'] = $this->comment->getAll($id);
		$data['news'] = $this->news->getByID($id);

		$header['title'] = 'Новость: ' . $data['news']->news_name;
		$header['key'] = $data['news']->meta_key;
		$header['desc'] = $data['news']->meta_desc;

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('singleNews',$data);
		$this->load->view('block/bottom');
	}

	public function categoryView()
	{
		$id = $this->uri->segment(2);
		$this->load->model('news');

		// Пагинация
		$config['base_url'] = '/category/' .$id . '/pagination/page';
		$config['total_rows'] = $this->news->getCount($id);
		$config['per_page'] = 10;
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['listCategory'] = $this->news->getAllPreviewCategory($id, $config['per_page'], $this->uri->segment(5));
		$header['title'] = 'Категория: ' . $data['listCategory'][0]->cat_name;

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('categoryView',$data);
		$this->load->view('block/bottom');			
	}

	public function admin()
	{
		//Создание формы
		$login = array(
            'name'        => 'login',
			'id'          => 'login',
			'maxlength'   => '500',
			'size'        => '20',
			'value'		=>	$this->input->post('login')
			);
		$password = array(
			'name'        => 'password',
			'id'          => 'password',
			'maxlength'   => '500',
			'size'        => '20',
			);
		$form['formopen'] =  form_open('/admin');
		$form['login'] = form_input($login);
		$form['password'] = form_password($password);	
		$form['submit']   = form_submit('submit', 'Войти');
		$form['formclose']=  form_close();

		//Действия при отправке формы
        $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE){
        	$this->load->model('users');
        	$login = $this->input->post('login');
        	$password = do_hash($this->input->post('password'), 'md5');
        	$result = $this->users->enterUsers($login, $password, 'admin');
        	if($result == null){
        		$form['error'] = "Не правильный логин или пароль!";
        	} else {
        	   //Новые длянные для сессии
        	   $newdata = array(
        	       'user_id'	=> $result->id,
                   'login'  	=> $result->login,
                   'email'     	=> $result->email,
                   'username'	=> $result->name,
                   'role'		=> $result->role,
                   'enter'      => 'on'
                   );
        	   $this->session->set_userdata($newdata);       //Запись новых данных в сессию				
        	   redirect('/admin/enter', 'refresh');           //Перенаправление на страницу пользователя
        	   }
            }

        // Отображение
        $form['title'] = 'Вход в панель администратора';

		$this->load->view('admin/a_index', $form);
	}

	public function registration()
	{
		$header['title'] = 'Регистрация';

		$form['error'] = '';

		$this->load->helper('captcha');
	    //создаем captcha
	    $vals = array(
	        'word' => mt_rand(0, 99999),
	        'img_path' => str_replace(SELF, "", FCPATH).'captcha'.DIRECTORY_SEPARATOR,
	        'img_url' => base_url().'captcha/',
	        'font_path' => str_replace(SELF, "", FCPATH).'system'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'AntsyPants.ttf',
	        'img_width' => '100',
	        'img_height' => '30',
	        'expiration' => '7200',
	    );

    	$captcha = create_captcha($vals);

		$dataCap = array(
		     'captcha_time' => $captcha['time'],
		     'ip_address' => $this->input->ip_address(),
		     'word' => $captcha['word']
		     );
		$this->load->model('captcha');
		$this->captcha->add($dataCap);

    	$form['captcha'] = $captcha['image'];

		$login = array (
			'name'		=>	'login',
			'id'		=>	'login',
			'maxlength'	=> 	'20',
			'size'		=>	'25',
			'value'		=>	$this->input->post('login'),
			'onKeyUp'	=>  'checkExistence(this);',
			'chesk'		=>	'login',
			);
		$password = array (
			'name'		=>	'password',
			'id'		=>	'password',
			'maxlength'	=> 	'30',
			'size'		=>	'25',
			'value'		=>	$this->input->post('password'),
            'onChange'	=> 'checkPassword(this);', 			
			);
		$password_repeat = array (
			'name'		=>	'password_repeat',
			'id'		=>	'password_repeat',
			'maxlength'	=> 	'30',
			'size'		=>	'25',
            'onChange'	=> 'checkPassword(this);', 			
			);		
		$name = array(
            'name'   	=> 'name',    // Имя поля
			'id'       	=> 'name',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$this->input->post('name'),
                                        // Значение, для сохраненения при перезагрузке стр.
			);
		$captch = array(
            'name'   	=> 'captch',    // Имя поля
			'id'       	=> 'captch',    // ID поля
			'maxlength'	=> '5',     // Максимальное кол-во знаков
			'size'     	=> '5',      // Размер
            'onChange'	=> 'checkCaptcha(this);',                           // Значение, для сохраненения при перезагрузке стр.
			);		
		$email = array(
            'name'   	=> 'email',    // Имя поля
			'id'       	=> 'email',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$this->input->post('email'),           
			'onKeyUp'	=>  'checkExistence(this);',
			'chesk'		=>	'email',			
			);
		$skype = array(
            'name'   	=> 'skype',    // Имя поля
			'id'       	=> 'skype',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$this->input->post('name'),          
			);
		$city = array(
            'name'   	=> 'city',    // Имя поля
			'id'       	=> 'city',    // ID поля
			'maxlength'	=> '100',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$this->input->post('city'),		
			);        
		$button_name = 'Пройти Регестрацию';


		for ($i=1; $i < 32; $i++) $day[$i] = $i;
		for ($i = date('Y'); $i >= 1960; $i--) $year[$i] = $i;
		$mounth = array('1' => 'Январь','2' => 'Февраль','3' => 'Март','4' => 'Апрель','5' => 'Май','6' => 'Июнь','7' => 'Июль','8' => 'Август','9' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь');

		$form['formopen'] =  form_open('registration');
		$form['login'] = form_input($login);
		$form['password'] = form_password($password);
        $form['password_repeat'] = form_password($password_repeat);
        $form['name'] = form_input($name);
        $form['email'] = form_input($email); //form_dropdown('category', $this->category->getDropDownList(), $mem_category);        
        $form['skype'] = form_input($skype);
        $form['day'] = form_dropdown('day', $day, $this->input->post('day'), 'class="day"');  
       	$form['mounth']  = form_dropdown('mounth', $mounth, $this->input->post('mounth')); 
       	$form['year']  = form_dropdown('year', $year, $this->input->post('year'));
       	$form['city']  = form_input($city);       	    
		$form['captch'] = form_input($captch);
		$form['submit']   = form_submit('submit', $button_name);
		$form['formclose']=  form_close();

		$this->form_validation->set_rules('login', 'Логин', 'trim|alpha_dash|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('password', 'Пароль', 'trim[password_repeat]|required|min_length[6]|max_length[30]|xss_clean|');
		$this->form_validation->set_rules('password_repeat', 'Повторный пароль', 'trim|required|min_length[6]|max_length[30]|xss_clean');

		$this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('skype', 'Skype', 'trim|alpha_dash|min_length[2]|xss_clean');
		
		$this->form_validation->set_rules('day', 'День', 'trim|xss_clean');
		$this->form_validation->set_rules('mounth', 'Месяц', 'trim|xss_clean');
		$this->form_validation->set_rules('year', 'Год', 'trim|xss_clean');						        
		
		$this->form_validation->set_rules('city', 'Город', 'trim|max_length[30]|xss_clean');		
   
        // Валидация успешна
        if ($this->form_validation->run() == TRUE){
        	$repeat_user = $this->checkExistence($this->input->post('login'),'login');
        	$repeat_email = $this->checkExistence($this->input->post('email'),'email');
        	$captcha_chk = $this->chesckCaptcha($this->input->post('captch'));
        	if($repeat_email == 'false') $form['error'] .= '<div class="error">Такой E-mail уже существует!</div>';
        	if($repeat_user == 'false') $form['error'] .= '<div class="error">Такой Логин уже существует!</div>';
        	if($captcha_chk == 'false') $form['error'] .= '<div class="error">Вы не ввели каптчу, или ввели не правильно!</div>';
        	if($form['error'] == ''){
        		$time = mktime('0','0','0',$this->input->post('mounth'),$this->input->post('day'),$this->input->post('year'));
        		$data = array(
        			'login' 	=> $this->input->post('login'),
        			'password' 	=> do_hash($this->input->post('password'), 'md5'),
        			'role'		=> 'user', 
        			'name'		=> $this->input->post('name'),
        			'email'		=> $this->input->post('email'),
        			'skype'		=> $this->input->post('skype'),
        			'birthday'	=> $time,
        			'city'		=> $this->input->post('city'), 
        			);    		

        		$this->load->model('users');
        		$this->users->newUser($data);

        		redirect('/home/successReg', 'refresh'); 
        	}
        }

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('registr',$form);
		$this->load->view('block/bottom');		
	}

	public function AJAXRequest()
	{
		if(isset($_GET['term'])) 
			$data = $this->autocompliteTown($this->input->get('term'));

		if(isset($_POST['users']) && isset($_POST['how']))
			$data = $this->checkExistence($this->input->post('users'), $this->input->post('how'));
 
		if(isset($_POST['cap']))
			$data = $this->chesckCaptcha($this->input->post('cap'));

		if(isset($_POST['news_id']) && isset($_POST['text']))
			$data = $this->newComment($this->input->post('news_id'), $this->input->post('text'));

		if(isset($_POST['login']) && isset($_POST['password']))
			if($_POST['login'] == '' || $_POST['password'] == ''){echo "false"; return false;}
			else $data = $this->enterUser($this->input->post('login'), $this->input->post('password'));

		if(isset($_POST['city']))
			$data = $this->cheskCity($this->input->post('city'));

		if(isset($_GET['autocomplete']) && isset($_GET['name'])) 
			$data = $this->autocompleteUsers($this->input->get('name'));

		echo $data;
	}

	private function autocompliteTown($term)
	{
		$this->load->model('city');
		$data = $this->city->autocomplete($term);

		$string = array();

        foreach ($data as $citys => $var) {
        	array_push($string, $var->city_name_ru);
        }

        return json_encode($string);
	}


	private function autocompleteUsers($name)
	{
		$this->load->model('users');
		$data = $this->users->autocomplete($name);

		$string = array();

        foreach ($data as $user => $var) {
        	//array_push($string, $var->login);
        	$string[] = array( 
        		//'label'	=>	$var->login,
        		'thumb'	=>  base_url() . $var->avatar_thums,
        		'value'	=>	$var->login,
        		'id'	=>	$var->id,
        		'name'	=> 	$var->name
        		);
        }

        return str_replace('\/','/',json_encode($string));//json_encode($string, JSON_UNESCAPED_SLASHES);
	}


	private function checkExistence($data, $how)
	{
		$this->load->model('users');

		$data = $this->users->checkExistence($data, $how);

		return $data;
	}

	private function chesckCaptcha($cap)
	{
		$this->load->model('captcha');

		$request = $this->captcha->check($cap);
		
		return $request;
	}

	private function newComment($news_id, $text)
	{
		$this->load->model('comment');

		$data = array (
				'news_id'	=> $news_id,
				'user_id'	=> $this->session->userdata('user_id'),
				'text'		=> $text,
				'date'		=> mktime('0',date('i'),date('G'),date('m'),date('d'),date('Y')),
			);

		return $this->comment->newComment($data);
	}

	private function enterUser($login, $password)
	{	
		$this->load->model('users');

		//enterUsers($login, $password, $role)
		$data = $this->users->enterUsers($login, do_hash($password, 'md5'), 'any'); 
		if($data != 'false'){
		    $newdata = array(
					'user_id'	=> $data->id,
					'login'  	=> $data->login,
					'email'     => $data->email,
					'username'	=> $data->name,
					'role'		=> $data->role,
					'enter'     => 'on'
				);
			$this->session->set_userdata($newdata);       //Запись новых данных в сессию	
		}

		return $data == 'false' ? json_encode('false') : json_encode($newdata);
	}

	private function cheskCity($city)
	{	
		$this->load->model('city');

		$data = $this->city->cheskExist($city);

		return json_encode($data);
	}

	public function logout()
	{
			$this->session->sess_destroy();
			redirect('/home', 'refresh'); 
	}
}