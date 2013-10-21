<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	// Путь до полных изображенией пользователя
    private $fullUploadPath = "files/avatar/full/";

    // Путь до средних изображений пользователя
    private $smallUploadPath = "files/avatar/small/";

    // Путь до маленьких изображений пользователя
    private $thumbsUploadPath = "files/avatar/thumbs/";

	public function User()
	{
        parent::__construct();
        //Проверка на авторизованность
        if(!$this->session->userdata('username'))
                redirect('/home', 'refresh');
	}

	public function profile()
	{
		// Загрузка модели пользователей
		$this->load->model('users');

		// Получение данных пользователя с городами
		$data['profile'] = $this->users->getUserCountryRegion($this->session->userdata('user_id'));

		$header['title'] = 'Профиль пользователя - ' . $data['profile']->name;

		$login = array (
			'name'		=>	'login', 					// атрибут name поля
			'id'		=>	'login',					// атрибут id поля
			'maxlength'	=> 	'20',						// максимальное кол-во символов в поле
			'size'		=>	'25',						// размер поля
			'value'		=>	$data['profile']->login,	// атрибут value поля, заполняется данными с БД
			'onKeyUp'	=>  'checkExistence(this);',	// Добавление атрибута и свойства
			'chesk'		=>	'login',					// Добавление атрибута и свойства
			);
		$name = array(
            'name'   	=> 'name',    // Имя поля
			'id'       	=> 'name',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$data['profile']->name,
                                        // Значение, для сохраненения при перезагрузке стр.
			);
		$email = array(
            'name'   	=> 'email',    // Имя поля
			'id'       	=> 'email',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$data['profile']->email,           
			'onKeyUp'	=>  'checkExistence(this);',
			'chesk'		=>	'email',			
			);
		$skype = array(
            'name'   	=> 'skype',    // Имя поля
			'id'       	=> 'skype',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$data['profile']->skype,          
			);
		$city = array(
            'name'   	=> 'city',    // Имя поля
			'id'       	=> 'city',    // ID поля
			'maxlength'	=> '100',     // Максимальное кол-во знаков
			'size'     	=> '25',      // Размер
			'value'		=>	$data['profile']->city_name,		
			//'onKeyUp'	=>  'checkCity(this);',
			); 

		$city_id = array('city_id' => $data['profile']->city_id);

		// создание массива для SelectBox - а, с датами (до 31)
		for ($i=1; $i < 32; $i++) $day[$i] = $i;

		// создание массива для SelectBox - а, с годами, начиная с 1960 до нынешнего года
		for ($i = date('Y'); $i >= 1960; $i--) $year[$i] = $i;

		// Массив с месецами для SelectBox
		$mounth = array('1' => 'Январь','2' => 'Февраль','3' => 'Март','4' => 'Апрель','5' => 'Май','6' => 'Июнь','7' => 'Июль','8' => 'Август','9' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь');

		// Создание формы с помощью помощника форм Codeigniter
		$data['formopen'] =  form_open('user/profile', 'class="form-user-profile"'); 					  // Открывающая часть формы
		$data['login'] = form_input($login);										 					  // Текствовое поле
        $data['name'] = form_input($name);											 					  // Текствовое поле
        $data['email'] = form_input($email);										 					  // Текствовое поле
        $data['skype'] = form_input($skype);										 					  // Текствовое поле
        $data['day'] = form_dropdown('day', $day, mdate("%d",$data['profile']->birthday), 'class="day"'); // SelectBox
       	$data['mounth']  = form_dropdown('mounth', $mounth, mdate("%m",$data['profile']->birthday)); 	  // SelectBox
       	$data['year']  = form_dropdown('year', $year, mdate("%Y",$data['profile']->birthday));			  // SelectBox
       	$data['city']  = form_input($city);											 					  // Текствовое поле
       	$data['city_id'] = form_hidden($city_id);   													  // Скрытые поля формы 	    
		$data['submit']   = form_submit('submit', 'Сохранить'); 										  // Сабмит формы
		$data['formclose']=  form_close(); 											 					  // Закрытие формы

		// Валидация формы					
		$this->form_validation->set_rules('login', 'Логин', 'trim|alpha_dash|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('skype', 'Skype', 'trim|alpha_dash|min_length[2]|xss_clean');
		
		$this->form_validation->set_rules('day', 'День', 'trim|xss_clean');
		$this->form_validation->set_rules('mounth', 'Месяц', 'trim|xss_clean');
		$this->form_validation->set_rules('year', 'Год', 'trim|xss_clean');						        
		
		$this->form_validation->set_rules('city', 'Город', 'trim|max_length[40]|xss_clean');		

        // Валидация успешна
        if ($this->form_validation->run() == TRUE){

        	// Преоброзование даты в UNIX формат
    		$time = mktime('0','0','0',$this->input->post('mounth'),$this->input->post('day'),$this->input->post('year'));
    		
    		$data = array(
    			'login' 	=> $this->input->post('login'),
    			'name'		=> $this->input->post('name'),
    			'email'		=> $this->input->post('email'),
    			'skype'		=> $this->input->post('skype'),
    			'birthday'	=> $time,
    			'city'		=> $this->input->post('city_id'), 
    			);    		

    		$this->load->model('users');
    		
    		$this->users->updateUser($data,$this->session->userdata('user_id'));

    		redirect('user/profile', 'refresh'); 
        }

		$this->load->view('user/block/top', $header);
		$this->load->view('user/block/left');
		$this->load->view('user/u_profile',$data);
		$this->load->view('user/block/bottom');
	}



	public function message()
	{
		$header['title'] = 'Сообщения пользователя';

		$this->load->model('messages');

		$data['inbox'] = $this->messages->getAllMessage($this->session->userdata('user_id'));

		$this->load->view('user/block/top', $header);
		$this->load->view('user/block/left');
		$this->load->view('user/u_message',$data);
		$this->load->view('user/block/bottom');
	}

	public function newMessage()
	{
		$header['title'] = 'Новое сообщение';

		$title = array(
            'name'   	=> 'title',    // Имя поля
			'id'       	=> 'title',    // ID поля
			'maxlength'	=> '50',     // Максимальное кол-во знаков
			'size'     	=> '40',      // Размер
			'value'		=>	$this->input->post('title'),
			);
		$message = array(
            'name'   	=> 'message',    // Имя поля
			'id'       	=> 'message',    // ID поля
			'rows'		=> '5',
			'cols'		=> '50',
			'value'		=>	$this->input->post('message'),          		
			);
		$to = array(
            'name'   	=> 'to',    // Имя поля
			'id'       	=> 'to',    // ID поля
			'maxlength'	=> '100',     // Максимальное кол-во знаков
			'size'     	=> '40',      // Размер
			'value'		=>	$this->input->post('to'),
			);		
		$hidden = array(
			'user_to'	=>	$this->input->post('user_to'),
			);
		
		$data['form_open'] = form_open('user/newMessage');
		$data['to'] = form_input($to);
		$data['title'] = form_input($title);
		$data['message'] = form_textarea($message);
		$data['submit'] = form_submit('submit', 'Отправить');
		$data['hidden'] = form_hidden($hidden);
		$data['form_close'] = form_close();

		// Валидация формы					
		$this->form_validation->set_rules('user_to', 'получатель', 'trim|required|xss_clean');
		$this->form_validation->set_rules('title', 'Название', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message', 'сообщение', 'trim|required|xss_clean');				        
		
        // Валидация успешна
        if ($this->form_validation->run() == TRUE)
        {

        	$data_db = array(
        		'title'		=> $this->input->post('title'),
        		'message'	=> $this->input->post('message'),
        		'from'		=> $this->session->userdata('user_id'),
        		'to'		=> $this->input->post('user_to'),
        		'status'	=> true,
        		'created'	=> time(),
        		);

        	$this->load->model('messages');

        	$this->messages->newMessage($data_db);

        	//echo "<pre>";
        	//print_r($data_db);
        	//echo "</pre>";

        }		

		$this->load->view('user/block/top', $header);
		$this->load->view('user/block/left');
		$this->load->view('user/u_new_message', $data);
		$this->load->view('user/block/bottom');	
	}

	public function createAvatar()
	{
		$header['title'] = 'Сообщения пользователя';

        // Конфиг для загрузки фото
        $config['upload_path'] = './' . $this->fullUploadPath;
		$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
        $config['max_size'] = '10000';
        $config['max_width'] = '4016';
        $config['max_height'] = '4016';            
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$this->load->library('upload', $config);

		$hidden = array(
			'x1' 		=> '',
			'y1' 		=> '',
			'x2' 		=> '',
			'y2' 		=> '',
			'width'		=> '',
			'height'	=> '',
			);

		$upload = array(
            'name'        => 'image_file',
			'id'          => 'image_file',
			'size'        => '20',
            'value'       => '',
            'onChange'	  => 'fileSelectHandler()',
			);

		$data['form_open'] = form_open_multipart('user/createAvatar', 'onSubmit="return checkForm()"');
		$data['hidden'] = form_hidden($hidden);
		$data['upload'] = form_upload($upload);
		$data['submit'] = form_submit('submit', 'Загрузить');
		$data['form_close'] = form_close();

        $this->form_validation->set_rules('x1', 'Размер изображения', 'trim|integer|xss_clean');
        $this->form_validation->set_rules('x2', 'Размер изображения', 'trim|integer|xss_clean');
        $this->form_validation->set_rules('y1', 'Размер изображения', 'trim|integer|xss_clean');
        $this->form_validation->set_rules('y2', 'Размер изображения', 'trim|integer|xss_clean');                        
        $this->form_validation->set_rules('img', 'Картинка', 'trim|xss_clean');

        if ($this->form_validation->run() == TRUE)
        {
			if($this->upload->do_upload('image_file')){
                $upload_data = $this->upload->data();
                
                // Конфиг для кропинга
                $config = array(    
                    'image_library'     => 'gd2',   						// Библеотека
                    'source_image'      => './' . $this->fullUploadPath . $upload_data['file_name'],   
                    'new_image'         => './' . $this->smallUploadPath, 	// Куда ложить
                    'maintain_ratio'    => FALSE,   						// сохранения пропорций
                    'x_axis'            => $this->input->post('x1'),       	// Откуда начать обрезать координата х
                    'y_axis'            => $this->input->post('y1'),       	// Откуда начать обрезать координата у
                    'width'             => $this->input->post('width'), 	// Ширина обрезания
                    'height'            => $this->input->post('height'), 	// высота обрезания
                    );

                $this->image_lib->initialize($config);
                $this->image_lib->crop();

                // Конфиг для ресайзинга thumb
                $config = array(
                    'image_library'     => 'gd2',
                    'source_image'      => './' . $this->smallUploadPath . $upload_data['file_name'],
                    'width'             => 150,
                    'height'            => 150,
                    'create_thumb'      => TRUE,    // Создание миниатюры
                    'maintain_ratio'    => TRUE,    // Сохранения пропорций
                    'thumb_marker'      => '',      // Обнулирование приставки миниатюры
                    );

                $this->image_lib->clear();  // Отчистка библеотеки изображений
                $this->image_lib->initialize($config);  // Установка новых конфигураций
                $this->image_lib->resize(); // Ресайз с заменой уже существующего изображения                

                // Конфиг для ресайзинга thumbs
                $config = array(
                    'image_library'     => 'gd2',
                    'source_image'      => './' . $this->smallUploadPath . $upload_data['file_name'],
                    'new_image'         => './' . $this->thumbsUploadPath, // Куда ложить                    
                    'width'             => 36,
                    'height'            => 36,
                    'create_thumb'      => TRUE,    // Создание миниатюры
                    'maintain_ratio'    => TRUE,    // Сохранения пропорций
                    'thumb_marker'      => '',      // Обнулирование приставки миниатюры
                    );

                $this->image_lib->clear();  // Отчистка библеотеки изображений
                $this->image_lib->initialize($config);  // Установка новых конфигураций
                $this->image_lib->resize(); // Ресайз с заменой уже существующего изображения                

                $data_db = array(
                	'avatar_full' 	=> $this->fullUploadPath . $upload_data['file_name'], 
                	'avatar_small'	=> $this->smallUploadPath . $upload_data['file_name'],
                	'avatar_thums'	=> $this->thumbsUploadPath . $upload_data['file_name'],
                	);

                $this->load->model('users');
                $result = $this->users->updateAvatar($data_db, $this->session->userdata('user_id'));
                if($result == true)
                	redirect('/user/profile', 'refresh'); 
            }

        }

		$this->load->view('user/block/top', $header);
		$this->load->view('user/block/left');
		$this->load->view('user/u_avatar',$data);
		$this->load->view('user/block/bottom');
	}	

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */