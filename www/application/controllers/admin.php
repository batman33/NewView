<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


    private $imageUploadPath = "files/img/big/";
    private $thumbnailUploadPath = "files/img/thumb/";
    private $thumbnailMinUploadPath = "files/img/thumb_small/";


    function Admin()
    {
        parent::__construct();
        //Проверка на авторизованность
        if(!$this->session->userdata('username'))
            if($this->session->userdata('role') != 'admin')
                redirect('/admin', 'refresh');
        
    }

    /* Главная страница админ панели */
	public function enter()
	{
        $title['title'] = 'Добро пожаловать '.$this->session->userdata('username');

        $this->load->view('admin/block/top',$title);
		$this->load->view('admin/a_enter');
        $this->load->view('admin/block/menu');  

	}
 
    /* Метод выхода из админ понели */
	public function aExit()
	{
		$this->session->sess_destroy();   //Убить сессию
		redirect('/admin', 'refresh');         //Редирект на главную страницу
	}	


    /* Метод вывода всех новостей */
	public function allNews()
	{
        // Загрузка модели новостей
		$this->load->model('news');
        
        // Сбор всех новостей
		$data['data'] = $this->news->getAll();

        // Титульник
        $title['title'] = 'Добро пожаловать '.$this->session->userdata('username');
        
        // Подгрузка вьюшек
        $this->load->view('admin/block/top',$title);
        $this->load->view('admin/a_news',$data);
        $this->load->view('admin/block/menu');  
	}
    

    /*  Метод редактирование и    *
    *   добавление новых записей  * 
    *   в таблицу news            */
    public function newNews()
    {
        $this->load->model('news');
        $this->load->model('category');        

        // Первоначальные данные для формы
        $mem_name       = '';
        $mem_text       = '';
        $mem_date       = '';
        $mem_desc       = '';
        $mem_category   = '';
        $mem_key        = '';       
        
        // Редактирование или новая запись
        if($this->uri->segment(3)){
            // Выборка по ID
            $datas = $this->news->getByIDForEdit($this->uri->segment(3));

            // Заголовок
            $title['title'] = 'Изменение записи "' . $datas[0]->name . '"';

            // Запись данных
            $mem_name       = $datas[0]->name;
            $mem_text       = $datas[0]->text;
            $mem_category   = $datas[0]->category;
            $mem_date       = date("m/d/Y", $datas[0]->date);
            $mem_desc       = $datas[0]->meta_desc;
            $mem_key        = $datas[0]->meta_key;    

            // Скрытое поле с ID записи
            $hidden = array('newsID' => $this->uri->segment(3));
            $form['hidden'] = form_hidden($hidden);

            // URL Картинка
            $form['img_src'] = $datas[0]->img_min;    

            // Название кнопки
            $button_name = 'Изменить запись';

        } else {
            // Заголовок
            $title['title'] = 'Добавление записи';

            // Название для кнопки сохранения
            $button_name = 'Добавить запись';
        }

        // Конфиг для загрузки фото
        $config['upload_path'] = './' . $this->imageUploadPath;
		$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
        $config['max_size'] = '10000';
        $config['max_width'] = '4024';
        $config['max_height'] = '4016';            
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$this->load->library('upload', $config);
        
        // Если форма была перезагруженна, заполняется старрыми введенными данными
        if($this->input->post('name') || $this->input->post('text') || $this->input->post('date') || $this->input->post('meta_desc') || $this->input->post('meta_key')){
            $mem_name       = $this->input->post('name');
            $mem_text       = $this->input->post('text');
            $mem_category   = $this->input->post('category');                        
            $mem_date       = $this->input->post('date');
            $mem_desc       = $this->input->post('meta_desc');
            $mem_key        = $this->input->post('meta_key');
        }

        // Конфиг полей формы
    	$name = array(
            'name'        => 'name',    // Имя поля
			'id'          => 'name',    // ID поля
			'maxlength'   => '500',     // Максимальное кол-во знаков
			'size'        => '70',      // Размер
			'value'		=>	$mem_name,
                                        // Значение, для сохраненения при перезагрузке стр.
			);
		$text = array(
			'name'        => 'text',
			'id'          => 'text',
			'rows'        => 10,
            'cols'        => 30,
			'value'		=>	$mem_text,            
			);
		$date = array(
            'name'        => 'date',
			'id'          => 'date',
			'maxlength'   => '500',
			'size'        => '20',
			'value'		=>	$mem_date,
			);
		$img = array(
            'name'        => 'img',
			'id'          => 'img',
			'size'        => '20',
            'value'       => ''
			);        
        // Поле CKField связанно с 'text'
        $textCK = array(
			'id' 	=> 	'text',
			'path'	=>	'ckeditor',
			'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"920px",	//Setting a custom width
				'height' 	=> 	'200px',	//Setting a custom height
 
			),
		);
        $category = array(
            'name'        => 'category',
            'id'          => 'category',
            'value'       =>  $mem_desc,            
            );
        $meta_desc = array(
            'name'        => 'meta_desc',
            'id'          => 'meta_desc',
            'rows'        => 5,
            'cols'        => 30,
            'value'       => $mem_desc,            
            );
        $meta_key = array(
            'name'        => 'meta_key',
            'id'          => 'meta_key',
            'rows'        => 5,
            'cols'        => 30,
            'value'       => $mem_key,            
            );
        
        // Массив с данными для формы
		$form['formopen'] =  form_open_multipart('admin/newNews');
		$form['name'] = form_input($name);
		$form['text'] = form_textarea($text);
        $form['ckeditor'] = $textCK; // Поле CKField связанно с 'text'
        $form['date'] = form_input($date);
        $form['category'] = form_dropdown('category', $this->category->getDropDownList(), $mem_category);        
        $form['meta_desc'] = form_textarea($meta_desc);
        $form['meta_key'] = form_textarea($meta_key);        
       	$form['img']  = form_upload($img);
		$form['submit']   = form_submit('submit', $button_name);
		$form['formclose']=  form_close();
        
		//Действия при отправке формы
        $this->form_validation->set_rules('name', 'Название', 'trim|required|xss_clean');
        $this->form_validation->set_rules('text', 'Текст', 'required');
        $this->form_validation->set_rules('date', 'Дата', 'trim|required|xss_clean');
        $this->form_validation->set_rules('meta_desc', 'Мета описание', 'trim|required|xss_clean');
        $this->form_validation->set_rules('category', 'Категория', 'trim|required|xss_clean');        
        $this->form_validation->set_rules('meta_key', 'Мета ключи', 'trim|required|xss_clean');
        $this->form_validation->set_rules('img', 'Картинка', 'trim|xss_clean');                
        
        // Валидация успешна
        if ($this->form_validation->run() == TRUE){
            
            // Перевод времени в формат UNIX
            list($month, $day, $year) = explode('/',$this->input->post('date')); 

            $second = mktime('0',date('i'),date('G'),$month,$day,$year);

            // Загрузка картинки с поля IMG, и проверка на сущетсвование                      
            if($this->upload->do_upload('img')){

                /* ЛОГИКА ЗАГРУЗКИ | СОЗДАНИЕ МИНИАТЮРЫ
                Берем меньшую велечину относительно высоты и ширины
                изображения, обрезаем по меньшей велечине, и делаем
                миниатюру размером 160х160, квадратной за счет обрезания. 
                */
                $upload_data = $this->upload->data();

                // Ширина высота загружаемого изображения
                $w_orig = $upload_data['image_width'];
                $h_orig = $upload_data['image_height'];

                // Выбираем по меньшеми из ширины и высоты
                if ($w_orig > $h_orig) $w_orig = $h_orig;
                else $h_orig = $w_orig;

                // Конфиг для кропинга
                $config = array(    
                    'image_library'     => 'gd2',   // Библеотека
                    'source_image'      => './' . $this->imageUploadPath . $upload_data['file_name'],   
                    'new_image'         => './' . $this->thumbnailUploadPath, // Куда ложить
                    'maintain_ratio'    => FALSE,   // сохранения пропорций
                    'x_axis'            => 0,       // Откуда начать обрезать координата х
                    'y_axis'            => 0,       // Откуда начать обрезать координата у
                    'width'             => $w_orig, // Ширина обрезания
                    'height'            => $h_orig, // высота обрезания
                    );

                $this->image_lib->initialize($config);
                $this->image_lib->crop();

                // Конфиг для ресайзинга thumb
                $config = array(
                    'image_library'     => 'gd2',
                    'source_image'      => './' . $this->thumbnailUploadPath . $upload_data['file_name'],
                    'width'             => 150,
                    'height'            => 150,
                    'create_thumb'      => TRUE,    // Создание миниатюры
                    'maintain_ratio'    => TRUE,    // Сохранения пропорций
                    'thumb_marker'      => '',      // Обнулирование приставки миниатюры
                    );

                $this->image_lib->clear();  // Отчистка библеотеки изображений
                $this->image_lib->initialize($config);  // Установка новых конфигураций
                $this->image_lib->resize(); // Ресайз с заменой уже существующего изображения

                // Конфиг для ресайзинга Mini
                $config = array(
                    'image_library'     => 'gd2',
                    'source_image'      => './' . $this->thumbnailUploadPath . $upload_data['file_name'],
                    'new_image'         => './' . $this->thumbnailMinUploadPath, // Куда ложить                    
                    'width'             => 30,
                    'height'            => 30,
                    'create_thumb'      => TRUE,    // Создание миниатюры
                    'maintain_ratio'    => TRUE,    // Сохранения пропорций
                    'thumb_marker'      => '',      // Обнулирование приставки миниатюры
                    );

                $this->image_lib->clear();  // Отчистка библеотеки изображений
                $this->image_lib->initialize($config);  // Установка новых конфигураций
                $this->image_lib->resize(); // Ресайз с заменой уже существующего изображения

                // Данные для БД
                $data['img']       =  $this->imageUploadPath . $upload_data['file_name'];
                $data['img_min']   =  $this->thumbnailUploadPath . $upload_data['file_name'];
                $data['img_small']   =  $this->thumbnailMinUploadPath . $upload_data['file_name'];
            }

            // Массив для БД
            $data['name']      =  $this->input->post('name');
            $data['text']      =  $this->input->post('text');
            $data['date']      =  $second;
            $data['category']  =  $this->input->post('category');
            $data['meta_desc'] =  $this->input->post('meta_desc');
            $data['meta_key']  =  $this->input->post('meta_key');


           
            // Новая запись или редактирование - проверка на существование скрытого поля
            if($this->input->post('newsID')){
                // Редактирование
                $this->news->updateNews($data, $this->input->post('newsID'));
            } else {
                // Новая запись
                $this->news->newNews($data);
            }

            // Редирект на страницу со всеми записями
            redirect('/admin/allNews', 'refresh');         //Редирект на главную страницу
        }
            
        // Загрузка вьюшек
        $this->load->view('admin/block/top',$title);
        $this->load->view('admin/a_newnews',$form);
        $this->load->view('admin/block/menu');   
    }
    
    public function options(){
        $title['title'] = 'Настройки';
        
        $this->load->view('admin/block/top',$title);
        $this->load->view('admin/a_options');
        $this->load->view('admin/block/menu');    
    }
    
    // Метод добавления удаления
    // Редактирование категорий
    public function category(){
        $title['title'] = 'Категории';
        $this->load->model('category');        
        // Конфиг полей формы
    	$name = array(
            'name'        => 'name',    // Имя поля
			'id'          => 'name',    // ID поля
			'maxlength'   => '500',     // Максимальное кол-во знаков
			'size'        => '35',      // Размер
			//'value'		=>	$name,
			);
		$description = array(
			'name'        => 'description',
			'id'          => 'description',
			'rows'        => 5,
            'cols'        => 30,
			//'value'		=>	$text,            
			);
        $hidden = array(
            'action'       => 'new',
            );


        // Загрузка всех категорий
        $data['category'] = $this->category->getAll();

        // Массив с данными для формы
		$data['formopen'] =  form_open('admin/category');
		$data['name'] = form_input($name);
		$data['description'] = form_textarea($description);
        $data['hidden'] = form_hidden($hidden);        
		$data['submit']   = form_submit('submit', 'Создать');
		$data['formclose']=  form_close();

        //Действия при отправке формы
        $this->form_validation->set_rules('name', 'Название', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Описание', 'trim|xss_clean');             
        
        // Валидация успешна
        if ($this->form_validation->run() == TRUE){
            
            // Данные для БД
            $data_db['name'] = $this->input->post('name');
            $data_db['description'] = $this->input->post('description');
            
            if($this->input->post('action') == 'new'){
                // Отправка в обхект табл
                $this->category->newCategory($data_db);
            } elseif($this->input->post('action') == 'edit'){
                // Отправка в обхект табл
                $this->category->update($data_db, $this->input->post('id'));
            }

            // Обновление страницы
            redirect('/admin/category', 'refresh');           
        }
        
        $this->load->view('admin/block/top',$title);
        $this->load->view('admin/a_category',$data);
        $this->load->view('admin/block/menu');    
    }

    // Метод удаление категорий из БД (AJAX)
    public function categoryDelete(){
        $this->load->model('category');
        $this->category->delete($this->input->post('delete_id'));
    }
    
    public function users()
    {

        $title['title'] = 'Пользователи';
        
        // Загрузка модели пользователей
        $this->load->model('users');

        // Получение всех пользователей с БД
        $data['users'] = $this->users->getAll(); 

        // Рендер
        $this->load->view('admin/block/top',$title);
        $this->load->view('admin/a_users', $data);
        $this->load->view('admin/block/menu');         
    }

}
