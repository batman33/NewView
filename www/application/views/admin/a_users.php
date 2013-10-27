<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tableNews').dataTable();
        $('select').selectbox();
        $('.select').css('width','25px');
    });
</script>

<h2>Пользователи</h2>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableNews">
	<thead>
		<tr>
			<th></th>
			<th>Логин</th>
			<th>Город</th>
			<th>Возраст</th>
			<th>Роль</th>
			<th>Имя</th>			
			<th>E-mail</th>						
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($users as $val) {
				$year = date("Y") - mdate("%Y",time() - $val->birthday);
				echo '<tr>'.
						'<td><img src="' . base_url() . $val->avatar_thums . '" /></td>' .
						'<td><b>'.$val->login.'</b></td>'.
						/*'<td>' . ($val->city_name_ru ? $val->city_name_ru : ' - ') . '</td>' .*/
						'<td>' . $year . ' л. </td>' .
						'<td>' . ($val->role == 'admin' ? 'Администратор' : 'Пользователь') . '</td>'.
						'<td>'. $val->name . anchor('/user/newMessage/'.$val->id, ' ', 'class="sent-message"') . '</td>'.						
						'<td>'. $val->email .'</td>'.
						'<td>'.anchor('admin/users/'.$val->id, ' ','class="table-link-edit"').' 
							 '.anchor('admin/users/'.$val->id, ' ','class="table-link-delete"').
						'</td>'.
					'</tr>';
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th>Логин</th>
			<th>Город</th>
			<th>Возраст</th>
			<th>Роль</th>
			<th>Имя</th>			
			<th>E-mail</th>						
			<th></th>
		</tr>
	</tfoot>            
</table>