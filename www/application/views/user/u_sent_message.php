<script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery-latest.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery.tablesorter.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/library/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">

$(document).ready(function(){

    $('#message-list').tablesorter({widthFixed: true})
    				  .tablesorterPager({container: $("#pager")});

});

</script>
<div class="content full">
	<h2>Исходящие сообщения <?php echo anchor('/user/message', 'Входящие сообщения', 'class="header-link"'); ?> <?php echo anchor('/user/newMessage', 'Написать сообщение', 'class="header-link"'); ?></h2>

	<?php if($inbox) : ?>
	<table class="tablesorter message-list" id="message-list">
		<thead>
			<tr>
				<th>Тема пиисьма</th>
				<th>Получатель</th>
				<th>Дата отправки</th>
			</tr>
		</thead> 
		<tbody> 
			<?php 
				foreach ($inbox as $key) {
					echo '<tr>' .
							'<td>'. anchor('user/viewMessage/' . $key->mess_id . '/sent', $key->mess_title) .'</a></td>' .
							'<td>'. anchor('/user/viewUser/' . $key->u_id, $key->u_name) .'</td>' .
							'<td>'. mdate("%d.%m.%Y г. %H:%i",$key->mess_created) .'</td>' .
						 '</tr>';
				}
			?>
		</tbody>
	</table>
	<div id="pager" class="tablesorterPager">
		<form>
			<span class="first"><span class="angle-left"></span><span class="angle-left"></span></span>
			<span class="prev"><span class="angle-left"></span></span>
			<label>Страница
				<input type="text" disabled="disabled" class="pagedisplay"/>
			</label>
			<span class="next"><span class="angle-right"></span></span>
			<span class="last"><span class="angle-right"></span><span class="angle-right"></span></span>
			<label>Показывать по:
				<select class="pagesize" name="showof">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select> записаей
			</label>
		</form>
	</div>
	<?php else : ?>
		<h3>Входящих сообщений нет</h3>
	<?php endif;  ?>
</div>