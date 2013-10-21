<script type="text/javascript" charset="utf-8">

$(function(){
	
	//Присоединяем автозаполнение
	$("#to").autocomplete({
		minLength: 1,
		delay: 300,
		autoFocus: true,
		maxItemsToShow: 10,
		autoFill: true,
		//Определяем обратный вызов к результатам форматирования				
		source: function(req, add){
			
			//Передаём запрос на сервер
			$.getJSON("<?=base_url()?>AJAXRequest?autocomplete=users&name=" + req.term, function(data) {
						
			 	//Создаем массив для объектов ответа
			 	var suggestions = [];

			 	add(data);
			});
		},	
		select: function(e, ui) {
			$("#user_to").val(ui.item.id);
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li>" ) 
       .append( "<a><img src='" + item.thumb + "'/><h3><b>" + item.label + "</b> " + item.name + "</h3></a>" )
       .appendTo( ul );
    };
});

</script>
<div class="content full">
	<h2>Новое сообщение</h2>
	<br />
	<?=$form_open?>

	<?=form_label('Кому сообщение', 'to');?>
	<?=$to?>

	<?=form_label('Название', 'title');?>
	<?=$title?>

	<?=form_label('Сообщение', 'message');?>
	<?=$message?>

	<?=$submit?>

	<?=$hidden?>

	<?=$form_close?>

	<?=validation_errors('<div class="error">', '</div>');?>

</div>