<script type="text/javascript">
    var login = '<?=$profile->login?>',
    	email = '<?=$profile->email?>',
    	filter_email  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;

    jQuery(document).ready(function($){
        $('#date').datepicker();

        // Настрока selectbox
        $('select').selectbox();
        $('.selectbox ul, .selectbox .dropdown').css('width', '100%');  
        $('body .selectbox:nth-child(2) .select').css('width','20px');
        $('body .selectbox:nth-child(5) .select').css('width','65px');        
        $('body .selectbox:nth-child(8) .select').css('width','35px');

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
	var checkExistence = function (obj) 
	{
		var chesk = $(obj).attr('chesk'), 	// Распознание поля
			value = $(obj).val();			// Значение поля

		if(chesk == 'login')
			if(value == login) 
			{
				recolorColom(true,true,'white',obj)
				return;	
			}
		if(chesk == 'email')
			if(value == email)
			{
				recolorColom(true,true,'white',obj)
				return;	
			}			
 		
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
					recolorColom(true,true,'#C6DD73',obj);
					console.log(data.id);
					$('#city_id').val(data.id);
				} else {
					recolorColom(false,false,'#FF9393',obj);
				}
			});	
		}, 500);		
	}
</script>
<div class="content full">
	<h2>Профиль</h2>
	<?=$formopen?>
	<table class="user-profile">
		<tr>
			<td class="label-table"><?=form_label('Логин', 'login')?></td>
			<td class="input-table"><?=$login?></td>
		</tr>
		<tr>
			<td class="label-table"><?=form_label('ФИО', 'name')?></td>
			<td class="input-table"><?=$name?></td>
		</tr>
		<tr>
			<td class="label-table"><?=form_label('Email', 'email')?></td>
			<td class="input-table"><?=$email?></td>
		</tr>
		<tr>
			<td class="label-table"><?=form_label('Skype', 'skype')?></td>
			<td class="input-table"><?=$skype?></td>
		</tr>
		<tr>
			<td class="label-table">Дата рождения</td>
			<td>
				<?=form_label('День', 'day')?> <?=$day?>
				<?=form_label('Месяц', 'mounth')?> <?=$mounth?>
				<?=form_label('Год', 'year')?> <?=$year?>
			</td>
		</tr>
		<tr>
			<td class="label-table"><?=form_label('Город', 'city')?></td>
			<td class="input-table"><?=$city?> <?=$profile->region_name;?> <?=$profile->country_name;?></td>
		</tr>
		<tr>
			<td colspan="2"><?=$submit?></td>
		</tr>
	</table>
	<?=$city_id?>
	<?=$formclose?>
	<div class="user-profile-avatar">
		<?=anchor('user/createAvatar', $profile->avatar_small ? 'Моя фотография' : 'Загрузить Фото', 'class="button"');?>
		<?php if($profile->avatar_small != '') : ?>
			<img class="small" src="<?php echo base_url() . $profile->avatar_small; ?>" alt="Avatar Small" />
			<img class="thumbs" src="<?php echo base_url() . $profile->avatar_thums; ?>" alt="Avatar Thumbs" />
		<?php endif;?>
	</div?
</div>
