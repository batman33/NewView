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
			<th>Логин</th>
			<th>Роль</th>
			<th>Имя</th>			
			<th>E-mail</th>						
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($users as $val) {
				echo '<tr>'.
						'<td><b>'.$val->login.'</b></td>'.
						'<td>'; 
				echo 	$val->role == 'admin' ? 'Администратор' : 'Пользователь';
				echo    '</td>'.
						'<td>'. $val->name .'</td>'.						
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
			<th>Логин</th>
			<th>Роль</th>
			<th>Имя</th>			
			<th>E-mail</th>						
			<th></th>
		</tr>
	</tfoot>            
</table>