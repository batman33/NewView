<div class="content full">
	<h2>Сообщение <?php echo anchor('/user/message', 'Вернутся', 'class="header-link"'); ?></h2><br />

	<div class="view-single-message">
		<div class="title">
			<h3 class="title message"><?=$message->mess_title?> <?=anchor('/user/deleteMessage/' . $message->mess_id, 'Удалить сообщение');?></h3>
		</div>
		<div>
			<span>Отправлено: <?=mdate("%d.%m.%Y г. %H:%i",$message->mess_created)?></span>
			<span><?=$statuses == 'inbox' ? 'От: ' : 'Получатель: '?> <?=anchor_user($message->u_id, $message->u_name, true)?></span>
		</div>

		<p><?=$message->messages?></p>
	</div>

</div>