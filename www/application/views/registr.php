<div class="content">
	<script type="text/javascript">
		
		// Фильтр E-mail
		var filter_email  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;

	    jQuery(document).ready(function($){
	        $('#date').datepicker();
	        
	        // Настрока selectbox
	        $('select').selectbox();
	        $('.selectbox ul, .selectbox .dropdown').css('width', '100%');  
	        $('body .selectbox:nth-child(1) .select').css('width','20px');
	        $('body .selectbox:nth-child(3) .select').css('width','65px');        
	        $('body .selectbox:nth-child(5) .select').css('width','35px');

	        // Автокомплит городов
	        $('#city').autocomplete({
	        	source: "<?=base_url()?>/AJAXRequest",
				minLength: 2,
				delay: 300,
				autoFocus: true,
				maxItemsToShow: 10,
				autoFill: true,
				select: function (obj) {
	         		checkCity(obj.target, $('#city').val());
	     		}				
			});

	    });  

	    // Проверка валидност / заполненности полей Логин Почта ajax
		var checkExistence = function (obj) {
			
			var chesk = $(obj).attr('chesk'), 	// Распознание поля
				value = $(obj).val();			// Значение поля
	 		
			$.ajax({
				url: '/AJAXRequest', 	// Куда запрос
				type: 'post',							// Тип подачи запроса
				dataType: 'json',						// Тип получения ответа
				data: {users: value, 					// Передача данных
					   how: $(obj).attr('chesk')},		// value - значение поля, how - тип поля
			})
			.done(function(data) {						// Действие после успешного укончания
				if(chesk == 'login' && data != false)
					if(value.length >= 5)
						data = true; 
					else 
						data = false;
				if(chesk == 'email' && data != false) filter_email.test(value) ? data = true : data = false;

				if(data == true){						// Положительный ответ
					recolorColom(true,true,'#C6DD73',obj)
				} else if(data == false){				// Отрицательный оввет
					recolorColom(false,false,'#FF9393',obj)
				}
			});
		}

		// Проверка каптчи ajax
		var checkCaptcha = function (obj) {

			var value = $(obj).val(); // Значения поля
			
			$.ajax({
				url: '/AJAXRequest',
				type: 'post',
				dataType: 'json',
				data: {cap: value},
			})
			.done(function(data) {
				console.log(data);
				if(data == true){
					recolorColom(true,true,'#C6DD73',obj)
				} else if(data == false){
					recolorColom(false,false,'#FF9393',obj)
				}
			});
		}

		// Проверка валидности Пароля
		var checkPassword = function(obj){
			
			var id 		= $(obj).attr('id'),	// ID поля
				value 	= $(obj).val(),			// Значение поля
				len 	= $(obj).val().length;	// Длинна введеных символов

			// Проверка на кол-во символов
			if(len < 6){
				recolorColom(true,true,'#C6DD73',obj)
			} else {
				recolorColom(false,false,'#FF9393',obj)
			}

			//Проверка на правльность повторного пароля
			if(id == 'password_repeat') if(value != $('#password').val()){
				recolorColom(true,true,'#C6DD73',obj)
			} else {
				recolorColom(false,false,'#FF9393',obj)
			}
		}

		var checkCity = function(obj, val)
		{
			setTimeout(function () {
				$.ajax({
					url: '/AJAXRequest',
					type: 'post',
					dataType: 'json',
					data: {city: obj.value},
				})
				.done(function(data) {
					if(data.id){
						recolorColom(true,true,'#C6DD73',obj)
					} else {
						recolorColom(false,false,'#FF9393',obj)
					}
				});	
			}, 500);		
		}		
	</script>
	 
	<h2>Регистрация</h2>

	<?=$formopen?>
	<table align="center" class="registr">
		<tbody>
			<tr>
				<td class="label-table">Логин</td>
				<td class="input-table"><?=$login?></td>			
			</tr>
			<tr>
				<td class="label-table">Пароль</td>
				<td class="input-table"><?=$password?></td>			
			</tr>
			<tr>
				<td class="label-table">Повторите пароль</td>
				<td class="input-table"><?=$password_repeat?></td>			
			</tr>
			<tr>
				<td class="label-table">Имя</td>
				<td class="input-table"><?=$name?></td>			
			</tr>
			<tr>
				<td class="label-table">E-mail</td>
				<td class="input-table"><?=$email?></td>			
			</tr>
			<tr>
				<td class="label-table">Skype</td>
				<td class="input-table"><?=$skype?></td>			
			</tr>
			<tr>
				<td class="date" colspan="2">Дата рождения: День <?=$day?> Месяц <?=$mounth?> год <?=$year?></td>			
			</tr>
			<tr>
				<td class="label-table">Город в которым вы живете</td>
				<td class="input-table"><?=$city?></td>			
			</tr>
			<tr>
				<td class="label-table">Введите число с картинки</td>
				<td class="input-table"><?=$captcha?> <?=$captch?></td>
			</tr>												
			<tr>
				<td colspan="2" align="center"><?=$submit?></td>			
			</tr>	
		</tbody>
	</table>
	<?=$formclose?>
	<? if(isset($error)) echo $error; ?>
	<? if(isset($success)) echo $success; ?>
	<?=validation_errors('<div class="error">', '</div>');?>
</div>