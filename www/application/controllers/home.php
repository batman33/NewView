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

    /* Главная страница сайта отображает 
       записи начиная с первой, с пагинацией
       по 10 записей на страницу, с выводом 
       сообщений на главной странице такие как 
       ошибки предупреждение успешно выполненно */
	public function index()
	{
		$this->load->model('news');

		// Пагинация
		$config['base_url'] = '/pagination/page/';				// ссылка для пагинации, для подставки номера страницы
		$config['total_rows'] = $this->news->getCount();		// Сколько всего записей в таблице
		$config['per_page'] = 10;								// Сколько отображать записей на странице
		$config['uri_segment'] = 3;								// Сегмет в ссылке, номер страницы
		$this->pagination->initialize($config);					// создаем объект пагинации
		$data['pagination'] = $this->pagination->create_links();// Записываем HTML код в переменную для отображение во view

		// Вывод сообщение на главной странице
		$message_type = $this->uri->segment(2);
		if($message_type == 'successReg'){
		
			// Сообщение об успешной регистрации
			$data['message'] = '<div class="success">Регистрация успешна!</div>';
		
		} elseif($message_type == 'no-permision'){
		
			// Сообщение о нехватке прав на страницу
			$data['message'] = '<div class="error">Зарегестрируйтесь для просмотра!</div>';
		}


		// Титульник страницы
		$header['title'] = 'Главная';

		// Вывод десяти записей, учитываю настройки в пагинации
		$data['list_news'] = $this->news->getAllPreview($config['per_page'], $this->uri->segment(3));

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('index_action',$data);
		$this->load->view('block/bottom');
	}


	/* Отображение одной новости */
	public function singleNews()
	{
		// ID новости, фрагмет ссылки
		$id = $this->uri->segment(2);

		// Загрузка модели новостей
		$this->load->model('news');

		// Загрузка модели комментариев
		$this->load->model('comment');

		// Подгрузка комментариев к статье по ID статье
		$data['comment'] = $this->comment->getAll($id);

		// Подгрузка данных о статье по ID статье
		$data['news'] = $this->news->getByID($id);

		// Титульник статьи
		$header['title'] = 'Новость: ' . $data['news']->news_name;

		// Ключевые слова и мето аписание
		$header['key'] = $data['news']->meta_key;
		$header['desc'] = $data['news']->meta_desc;

		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('singleNews',$data);
		$this->load->view('block/bottom');
	}


	/* Страница отображение статей в категрии */
	public function categoryView()
	{
		// ID категориии статьи
		$id = $this->uri->segment(2);

		// Подгрузка вмодели новостей
		$this->load->model('news');

		// Пагинация
		$config['base_url'] = '/category/' .$id . '/pagination/page';// ссылка для пагинации, для подставки номера страницы 
		$config['total_rows'] = $this->news->getCount($id);			 // Сколько всего записей в таблице
		$config['per_page'] = 10;									 // Сколько отображать записей на странице
		$config['uri_segment'] = 5;									 // Сегмет в ссылке, номер страницы
		$this->pagination->initialize($config);						 // создаем объект пагинации
		$data['pagination'] = $this->pagination->create_links();	 // Записываем HTML код в переменную для отображение во view

		// Берем все записи по данной категории
		$data['listCategory'] = $this->news->getAllPreviewCategory($id, $config['per_page'], $this->uri->segment(5));
		
		// Титульник для страницы
		$header['title'] = 'Категория: ' . $data['listCategory'][0]->cat_name;

		// Загрузка отображений в порядке для страницы
		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('categoryView',$data);
		$this->load->view('block/bottom');			
	}

	public function admin()
	{
		//Создание формы
		$login = array(
            'name'        => 'login',					// Атрибут name
			'id'          => 'login',					// Атрибут id
			'maxlength'   => '500',						// Максимальная длинна для набора в строке
			'size'        => '20',						// Размер поля
			'value'		=>	$this->input->post('login')	// Значение по умолчанию
			);
		$password = array(
			'name'        => 'password',	// Атрибут name
			'id'          => 'password',	// Атрибут id
			'maxlength'   => '500',			// Максимальная длинна для набора в строке
			'size'        => '20',			// Размер поля
			);
		$form['formopen'] =  form_open('/admin');			// Открытие формы с action на /admin
		$form['login'] = form_input($login);				// поле логин
		$form['password'] = form_password($password);		// поле пароль
		$form['submit']   = form_submit('submit', 'Войти');	// кнопка обработки формы
		$form['formclose']=  form_close();					// закрытие формы

		//Действия при отправке формы
        $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean');		// обезательное для щаполнения отчистка
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|xss_clean');	// обезательное для щаполнения отчистка

        // Если форма прошла валидацию
        if ($this->form_validation->run() == TRUE){

        	// Загрузка модели пользователей
        	$this->load->model('users');
        	
        	// Сохраняем введное значение login
        	$login = $this->input->post('login');

        	// Сохраняем введное значение password в кодированном виде
        	$password = do_hash($this->input->post('password'), 'md5');

        	// Если есть такой пользоватль значит логин верный
        	$result = $this->users->enterUsers($login, $password, 'admin');

        	// Иначе выводим на форму ошибку
        	if($result == null){
        		$form['error'] = "Не правильный логин или пароль!";
        	} else {

        	   //Новые длянные для сессии
        	   $newdata = array(
        	       'user_id'	=> $result->id,		// ID пользователя
                   'login'  	=> $result->login,	// Логин пользователя
                   'email'     	=> $result->email,	// Почта пользователя
                   'username'	=> $result->name,	// Имя пользователя
                   'role'		=> $result->role,	// Роль пользователя
                   'enter'      => 'on'				// Статус входа
                   );

        	   // Записываем массив в сессию
        	   $this->session->set_userdata($newdata);       //Запись новых данных в сессию				
        	   redirect('/admin/enter', 'refresh');           //Перенаправление на страницу пользователя
        	   }
            }

        // Титульник страницы
        $form['title'] = 'Вход в панель администратора';

        // Отображение вьюшки
		$this->load->view('admin/a_index', $form);
	}


	/* Регистрация новых пользователей в системе
	   Используется защита от ботов в виде капчи
	   при регистрации не заливается аватар */
	public function registration()
	{
		// Титульник страницы
		$header['title'] = 'Регистрация';

		// Сообщения об ошибках
		$form['error'] = '';

		// Загрузка хелпера каптч
		$this->load->helper('captcha');
	    
	    //создаем captcha
	    $vals = array(
	        'word' => mt_rand(0, 99999), // Берем любое число в диапазоне от 0 до 99999
	        'img_path' => str_replace(SELF, "", FCPATH).'captcha'.DIRECTORY_SEPARATOR, // Куда сохронять картинки
	        'img_url' => base_url().'captcha/',	// Берем картинку обртано
	        'font_path' => str_replace(SELF, "", FCPATH).'system'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'AntsyPants.ttf', // Шрифт для каптчи
	        'img_width' => '100',	// Ширина изображения
	        'img_height' => '30',	// Высота изображения
	        'expiration' => '7200',	// Время жизни картинки
	    );

	    // Создаем объекст капчи по данным масива vals
    	$captcha = create_captcha($vals);

    	// Подготавливаем данные для БД
		$dataCap = array(
		     'captcha_time' => $captcha['time'],		// Время жизни картинкии
		     'ip_address' => $this->input->ip_address(),// IP адресс пользователя
		     'word' => $captcha['word']					// Число на картинке
		     );

		// Загружаем модель каптчи
		$this->load->model('captcha');

		// Добовляем данные в БД					
		$this->captcha->add($dataCap);

		// Отсылаем в отображение ссылку на картинку
    	$form['captcha'] = $captcha['image'];

    	// СОЗДАНИЕ ФОРМЫ РЕГИСТРАЦИИ
		$login = array (
			'name'		=>	'login',					// Атрибут name
			'id'		=>	'login',					// Атрибкт ID 
			'maxlength'	=> 	'20',						// Максимальная длинна строки
			'size'		=>	'25',						// Ширина поля
			'value'		=>	$this->input->post('login'),// Значение по умолчанию
			'onKeyUp'	=>  'checkExistence(this);',	// При изменении данных в поле
			'chesk'		=>	'login',					// Флаг поля
			);
		$password = array (
			'name'		=>	'password',						// Атрибут name
			'id'		=>	'password',						// Атрибкт ID 
			'maxlength'	=> 	'30',							// Максимальная длинна строки
			'size'		=>	'25',							// Ширина поля
			'value'		=>	$this->input->post('password'),	// Значение по умолчанию
            'onChange'	=> 'checkPassword(this);', 			// При изменении данных в поле
			);
		$password_repeat = array (
			'name'		=>	'password_repeat',		// Атрибут name
			'id'		=>	'password_repeat',		// Атрибкт ID
			'maxlength'	=> 	'30',					// Максимальная длинна строки
			'size'		=>	'25',					// Ширина поля
            'onChange'	=> 'checkPassword(this);',	// При изменении данных в поле
			);		
		$name = array(
            'name'   	=> 'name',    					// Имя поля
			'id'       	=> 'name',    					// ID поля
			'maxlength'	=> '50',     					// Максимальное кол-во знаков
			'size'     	=> '25',      					// Размер
			'value'		=>	$this->input->post('name'),	// Значение по умолчанию
			);
		$captch = array(
            'name'   	=> 'captch',    			// Имя поля
			'id'       	=> 'captch',    			// ID поля
			'maxlength'	=> '5',     				// Максимальное кол-во знаков
			'size'     	=> '5',      				// Размер
            'onChange'	=> 'checkCaptcha(this);',   // Значение по умолчанию
			);		
		$email = array(
            'name'   	=> 'email',    					// Имя поля
			'id'       	=> 'email',    					// ID поля
			'maxlength'	=> '50',     					// Максимальное кол-во знаков
			'size'     	=> '25',      					// Размер
			'value'		=>	$this->input->post('email'),// Значение по умолчанию
			'onKeyUp'	=>  'checkExistence(this);',	// При изменении данных в поле
			'chesk'		=>	'email',					// Флаг поля
			);
		$skype = array(
            'name'   	=> 'skype',    					// Имя поля
			'id'       	=> 'skype',    					// ID поля
			'maxlength'	=> '50',     					// Максимальное кол-во знаков
			'size'     	=> '25',      					// Размер
			'value'		=>	$this->input->post('name'), // Значение по умолчанию           
			);
		$city = array(
            'name'   	=> 'city',    					// Имя поля
			'id'       	=> 'city',    					// ID поля
			'maxlength'	=> '100',     					// Максимальное кол-во знаков
			'size'     	=> '25',      					// Размер
			'value'		=>	$this->input->post('city'),	// Значение по умолчанию 	
			);        

		// Кнопка submit
		$button_name = 'Пройти Регестрацию';

		// Цикл для формы поля select day 1-31
		for ($i=1; $i < 32; $i++) $day[$i] = $i;

		// Цикл для формы поля select year 1960 - NOW YEAR
		for ($i = date('Y'); $i >= 1960; $i--) $year[$i] = $i;

		// Массив с месецами для select
		$mounth = array('1' => 'Январь','2' => 'Февраль','3' => 'Март','4' => 'Апрель','5' => 'Май','6' => 'Июнь','7' => 'Июль','8' => 'Август','9' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь');

		$form['formopen'] =  form_open('registration');										// Открытие формы
		$form['login'] = form_input($login);												// Текстовое поле ввода
		$form['password'] = form_password($password);										// Поле ввода пароля
        $form['password_repeat'] = form_password($password_repeat);							// Поле ввода пароля
        $form['name'] = form_input($name);													// Текстовое поле ввода
        $form['email'] = form_input($email);												// Текстовое поле ввода
        $form['skype'] = form_input($skype);												// Текстовое поле ввода
        $form['day'] = form_dropdown('day', $day, $this->input->post('day'), 'class="day"');// Поле выбора SELECT
       	$form['mounth']  = form_dropdown('mounth', $mounth, $this->input->post('mounth')); 	// Поле выбора SELECT
       	$form['year']  = form_dropdown('year', $year, $this->input->post('year'));			// Поле выбора SELECT
       	$form['city']  = form_input($city);       	    									// Текстовое поле ввода
		$form['captch'] = form_input($captch);												// Текстовое поле ввода
		$form['submit']   = form_submit('submit', $button_name);							// Кнопка обработки формы
		$form['formclose']=  form_close();													// Закрытие формы


		// Обезательные поля для заполнения
		$this->form_validation->set_rules('login', 'Логин', 'trim|alpha_dash|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('password', 'Пароль', 'trim[password_repeat]|required|min_length[6]|max_length[30]|xss_clean|');
		$this->form_validation->set_rules('password_repeat', 'Повторный пароль', 'trim|required|min_length[6]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('name', 'Имя', 'trim|required|min_length[2]|max_length[20]|xss_clean');

		// Не обезательные поля для заполнения
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('skype', 'Skype', 'trim|alpha_dash|min_length[2]|xss_clean');
		
		$this->form_validation->set_rules('day', 'День', 'trim|xss_clean');
		$this->form_validation->set_rules('mounth', 'Месяц', 'trim|xss_clean');
		$this->form_validation->set_rules('year', 'Год', 'trim|xss_clean');						        
		
		// Автокомплит городов
		$this->form_validation->set_rules('city', 'Город', 'trim|max_length[30]|xss_clean');		
   
        // Валидация успешна
        if ($this->form_validation->run() == TRUE){

        	// Вторичная проверка полей
        	$repeat_user = $this->checkExistence($this->input->post('login'),'login');
        	$repeat_email = $this->checkExistence($this->input->post('email'),'email');
        	$captcha_chk = $this->chesckCaptcha($this->input->post('captch'));

        	// Если нет то отправляем ошибки
        	if($repeat_email == 'false') $form['error'] .= '<div class="error">Такой E-mail уже существует!</div>';
        	if($repeat_user == 'false') $form['error'] .= '<div class="error">Такой Логин уже существует!</div>';
        	if($captcha_chk == 'false') $form['error'] .= '<div class="error">Вы не ввели каптчу, или ввели не правильно!</div>';

        	// Если ошибок нет то сохраняем данные
        	if($form['error'] == ''){
        		
        		// День рождения пользователя преобразовываем в UNIX время
        		$time = mktime('0','0','0',$this->input->post('mounth'),$this->input->post('day'),$this->input->post('year'));
        		
        		// Данные для БД
        		$data = array(
        			'login' 	=> $this->input->post('login'),						// Логин пользователя
        			'password' 	=> do_hash($this->input->post('password'), 'md5'),	// Пароль пользователя в зашифрованном виде
        			'role'		=> 'user', 											// Роль пользователя по умолчанию
        			'name'		=> $this->input->post('name'),						// Имя пользователя
        			'email'		=> $this->input->post('email'),						// Почта пользователя
        			'skype'		=> $this->input->post('skype'),						// Скайп пользователя
        			'birthday'	=> $time,											// День рождения пользовтеля
        			'city'		=> $this->input->post('city'), 						// Город проживания пользователя
        			);    		

        		// Загрузка модели пользователя
        		$this->load->model('users');

        		// Сохранение в БД Данных
        		$this->users->newUser($data);

        		// Редирект на главную страницу с сообщение об успешной регистрации
        		redirect('/home/successReg', 'refresh'); 
        	}
        }

        // Отображение вьюшек для страницы
		$this->load->view('block/top', $header);
		$this->load->view('block/left');
		$this->load->view('registr',$form);
		$this->load->view('block/bottom');		
	}

	/*
	API функция принимает параметры
	и распределяет их по внутренним
	методам private и возвращает то
	что возвращает метод
	 */
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

	/* АвтоЗавершение написанного города 
	   @term - строка
	   return JSON  */
	private function autocompliteTown($term)
	{
		// Загрузка модели городов
		$this->load->model('city');

		// Нахождение по совпадению города
		$data = $this->city->autocomplete($term);

		// перемнная для парсинга
		$string = array();

		// Парсим города в массив для отображения
        foreach ($data as $citys => $var) {
        	array_push($string, $var->city_name_ru);
        }

        // возвращаем JSON
        return json_encode($string);
	}

	/* АвтоЗавершение написанного имени или логина пользователя 
	   @term - строка
	   return JSON  */
	private function autocompleteUsers($name)
	{
		// Загрузка модели пользователей
		$this->load->model('users');

		// Нахождение по совпадению имени или логина
		$data = $this->users->autocomplete($name);

		// перемнная для парсинга
		$string = array();

		// Парсим имена логин ID и картинку в массив для отображения
        foreach ($data as $user => $var) {
        	$string[] = array( 
        		'thumb'	=>  base_url() . $var->avatar_thums,
        		'value'	=>	$var->login,
        		'id'	=>	$var->id,
        		'name'	=> 	$var->name
        		);
        }

        // возвращаем JSON
        return str_replace('\/','/',json_encode($string));
	}

	/* Проверка данных пользователя в БД ни уникальность 
	   @data - данные для проверка
	   @how - поле для проверка
	   return boolean string  */
	private function checkExistence($data, $how)
	{
		// Загружаем модель пользователя
		$this->load->model('users');

		// Получаем статус boolean найдено ли совпадение
		$data = $this->users->checkExistence($data, $how);

		// Возвращаем boolean string
		return $data;
	}

	/* Проверка капчи на совпадение 
	   @cap - введеннае пользователем данные
	   return boolean string  */
	private function chesckCaptcha($cap)
	{
		// Загрузка модели капчи
		$this->load->model('captcha');

		// Проверка на совпадение
		$request = $this->captcha->check($cap);

		// Возвращаем boolean string		
		return $request;
	}

	/* Проверка данных пользователя в БД ни уникальность 
	   @news_id - ID статьи
	   @text - текст комментария
	   return boolean  */
	private function newComment($news_id, $text)
	{
		// Загружаем модель комментариев
		$this->load->model('comment');

		// Массив для добавление в БД
		$data = array (
				'news_id'	=> $news_id,													 // ID статьи
				'user_id'	=> $this->session->userdata('user_id'),							 // Пользователь добавивший
				'text'		=> $text,														 // Текст комментария
				'date'		=> mktime('0',date('i'),date('G'),date('m'),date('d'),date('Y')),// Время добавления
 			);

		// Возвращаем boolean
		return $this->comment->newComment($data);
	}

	/* Проверка данных пользователя в БД для входа
	   @login - логин пользователя
	   @password - пароль пользователя
	   return boolean или данные сессии  */
	private function enterUser($login, $password)
	{	
		// Загрузка модели пользователя
		$this->load->model('users');

		// Загрузка пользователя по данным
		$data = $this->users->enterUsers($login, do_hash($password, 'md5'), 'any'); 

		// Если пользователь есть то записываем его данные в сессию
		if($data != 'false'){

			// Данные для сесии
		    $newdata = array(
					'user_id'	=> $data->id,		// ID пользователя
					'login'  	=> $data->login,	// Логин пользователя
					'email'     => $data->email,	// Почта пользователя
					'username'	=> $data->name,		// Имя пользователя
					'role'		=> $data->role,		// Роль пользователя
					'enter'     => 'on'				// Статус входа
				);

		    // Сохраняем масив newdata в сессию
			$this->session->set_userdata($newdata);       //Запись новых данных в сессию	
		}

		// Возвращаем false в случае не правильных данных, иди данные пользователя если вход успешен
		return $data == 'false' ? json_encode('false') : json_encode($newdata);
	}

	/* Проверка города на наличии в бд 
	   @city - название города
	   return boolean  */
	private function cheskCity($city)
	{
		// Загрузка модели городов
		$this->load->model('city');

		// Возвращает boolean
		return json_encode($this->city->cheskExist($city));
	}

	// Выход пользователем
	public function logout()
	{
		// Удаляем сессию
		$this->session->sess_destroy();

		// Редирект на главную страницу
		redirect('/home', 'refresh'); 
	}
}