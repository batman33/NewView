<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?=$title?></title>

	<meta name=viewport content="width=device-width, initial-scale=1">	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php 
	if (isset($key) && isset($desc)) 
		echo '<meta name="description" content="'.$desc.'">' .
 			 '<meta name="keywords" content="'.$key.'">';
	?>
	
	<?=link_tag('css/style.css')?>
	<?=link_tag('css/users.css')?>
	<?=link_tag('css/library/jquery.ui.all.css')?>
	<?=link_tag('css/library/table_table.css')?>
	<?=link_tag('css/library/selectbox.css')?>
	<?=link_tag('css/library/jquery.Jcrop.min.css')?>
	<?=link_tag('css/library/style_tablesorter.css')?>	
	<?=link_tag('css/library/jquery.tablesorter.pager.css')?>	
	<?=link_tag('ckeditor/contents.css')?>

    <script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery-ui.js"></script>	
    <script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery.selectbox.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?=base_url()?>js/scripts.js"></script>

    

    <script type="text/javascript">
    	var enterUser = function(){
    		$.ajax({
    			url: '/AJAXRequest',
    			type: 'post',
    			dataType: 'json',
    			data: {	
    					login: $('.enter #login').val(),
    					password: $('.enter #password').val(),
    				},
    		})
    		.done(function(data) {
				if(data != 'false') location.reload();
				else alert("Не правильный логин или пароль!");
    		});
    	}
    </script>
</head>
<body>
	<section class="wrapper-header">
		<div class="header">
			<a class="logo" href="/"></a>

				<?php if(!$this->session->userdata('enter')) : ?>
					<div class="enter">
						<input type="text" id="login" placeholder="Логин" />
						<input type="password" id="password" placeholder="Пароль" />
						<input type="button" value="Войти" onClick="enterUser()" />
					</div>
				<?php elseif($this->session->userdata('enter') == 'on') : ?>
					<div class="enter active">
						<div>Здраствуйте: <b><?php if($this->session->userdata('username')) echo $this->session->userdata('username'); else echo $this->session->userdata('login'); ?></b></div>
						<div class="user_menu">
							<?=anchor('/user/profile', 'Мой Профиль');?>							
							<?=anchor('/user/message', 'Почта');?>
							<?=anchor('/logout', 'Выйти');?>
						</div>
					</div>
				<?php endif;?>
			<ul class="menu">
			<li><?=anchor('registration', 'Регистрация','class="li-menu"');?></li><li><?=anchor('#', 'Новости','class="li-menu"');?></li><li><?=anchor('#', 'Ссылка','class="li-menu"');?></li>
			</ul>
		</div>
	</section>	
	<section class="wrapper-content">

