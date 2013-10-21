<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tableNews').dataTable();
        $('select').selectbox();
        $('.select').css('width','25px');
    });
</script>
<h2>Управление новостями</h2>
<?=anchor('admin/newNews', 'Добавить новость','class="under_h"');?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableNews">
	<thead>
		<tr>
			<th></th>
			<th>Название</th>
			<th>Дата</th>
			<th>Категория</th>			
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($data as $val) {
				echo '<tr>'.
						'<td style="padding:0;text-align:center;width:30px;">'. img(base_url().$val->thumbnail) .'</td>'.
						'<td><b>'.$val->news_name.'</b></td>'.
						'<td>'. date("d.m.Y G:i", $val->news_date).'</td>'.
						'<td>'. $val->cat_name .'</td>'.						
						'<td>'.anchor('admin/editNews/'.$val->news_id, ' ','class="table-link-edit"').' 
							 '.anchor('admin/deleteNews/'.$val->news_id, ' ','class="table-link-delete"').
						'</td>'.
					'</tr>';
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th>Название</th>
			<th>Дата</th>
			<th>Категория</th>				
			<th></th>
		</tr>
	</tfoot>            
</table>