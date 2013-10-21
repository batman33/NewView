<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel="stylesheet" type="text/css" href="<? echo base_url(); ?>css/admin.css">
</head>
<body>

<?=$formopen;?>
<table class="autorize">
	<tr>
		<th colspan="2">Авторизация</th>
	</tr>
	<tr>
		<td>Логин</td>
		<td><?=$login?></td>
	</tr>
	<tr>
		<td>Пароль</td>
		<td><?=$password?></td>
	</tr>
	<tr>
		<td colspan="2" id="center"><?=$submit?></td>
	</tr>	
</table>	
<?=$formclose;?>

<?=validation_errors('<div class="error">', '</div>');?>
<?php if(isset($error)) echo '<div class="error">'.$error.'</div>';?>

</body>
</html>