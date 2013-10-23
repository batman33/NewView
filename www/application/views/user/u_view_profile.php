<div class="content">
	<h2>Профиль пользователя <?=$profile->name?></h2><br />
	
	<div class="user-profile">
		<div class="avatar"><img src="<?=base_url() . $profile->avatar_small?>" /></div>
		<div class="info-user">
			<div class="name"><b>Имя:</b> <?=$profile->name?></div>	
			<div class="email"><b>Email:</b> <?=mailto($profile->email, $profile->email);?></div>
			<?php if($profile->skype) : ?>
				<div class="skupe"><b>Skype:</b> <?=$profile->skype?></div>
			<?php endif; ?>
			<div class="nappy-b"><b>День рождения:</b> <?=mdate("%d.%m.%Y г.",$profile->birthday)?></div>
			<?php if($profile->country_name) : ?>
				<div class="live"><b>Место жительства:</b> <?=$profile->country_name?> > <?=$profile->region_name?> > <?=$profile->city_name?></div>
			<?php endif; ?>
			<div><?=anchor('/user/newMessage/' . $profile->id, 'Отправить сообщение этому пользователю', 'attributs');?></div>
		</div>
	</div>	

</div>
