<script type="text/javascript">
	var Form = function (action) {
		if(action == 'echo') $('.comment .form').show('slow');
		else if(action == 'cancel') $('.comment .form').hide('slow');
		else if(action = 'ajax'){
			if($('.comment .form textarea').val().length > 0){
				$.ajax({
					url: '/AJAXRequest',
					type: 'post',
					dataType: 'json',
					data: {	text: $('.comment .form textarea').val(), 
							news_id: $('.comment .form input').attr('id')
						},
				})
				.done(function(data) {
					$('.comment .form textarea').val('');
					$('.comment .form').hide();
					location.reload();				
				});
			} else {
				alert('Вы не ввели сообщение!');
			}	
		}
	}

</script>
<div class="content full">
	<div class="wrapper-newses">
		<h2>
			<?=$news->news_name?> 
			<span class="single-news-date"><?=mdate("%d %m %Y г. %h:%i",$news->news_date)?></span> 
			<span class="single-news-category"><?=anchor('category/' . $news->cat_id, $news->cat_name)?></a></span>
		</h2>
		<div class="single-news-text">
			<?=$news->news_text?>
		</div>

		<div class="comment">
<?php if(!$this->session->userdata('enter')) : ?>
	<h2>Коментарии <a>Добавлять коментарии может только зарегестрированный пользователь!</a></h2>
<?php elseif($this->session->userdata('enter') == 'on') : ?>
	<h2>Коментарии <a onClick="Form('echo');">Добавить Коментарий</a></h2>
<?php endif;?>			
			<div class="form">
				<textarea cols="50" rows="5" placeholder="Текст коментария"></textarea>
				<input onClick="Form('ajax');" id="<?=$news->news_id?>" type="button" value="Добавить">
				<input onClick="Form('cancel');" type="button" value="Отмена">
			</div>
			<div class="list">
				<ul>
					<?php
						foreach ($comment as $key) {
							echo '<li>' .
									'<img align="left" src="' . base_url() . $key->users_ava . '" />' .
									'<div class="user">' . anchor_user($key->user_id, $key->user_name, $this->session->userdata('user_id')) . '</div>' .
									'<div class="date">' . mdate("%d.%m.%Y г. %H:%i",$key->date) . '</div>' .
									'<div class="text">' . parse_smileys($key->text, base_url() . 'images/smileys/') . '</div>' .
								 '</li>';

						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>

