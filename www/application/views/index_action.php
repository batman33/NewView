<div class="content">
	<?php if(isset($message)) echo $message; ?>
	<h2>NewView</h2>
	<?=$pagination?>	
	<div class="wrapper-newses">
		<ul class="newses">
			<?php
			$i = 1;
			foreach ($list_news as $key) {
				echo '<li class="news news-'.$i.'">' .
						'<h3>' . anchor('news/' . $key->news_id, $key->news_name) . '</h3>' .
						'<span class="date">' . mdate("%d.%m.%Y г. %H:%i",$key->news_date) . '</span>' .
						'<span class="category">' . anchor('category/' . $key->cat_id, $key->cat_name) . '</span>' .
						'<span class="wrapper-img">' . img($key->thumbnail) . '</span>' .
						'<span class="link-next">' . anchor('news/' . $key->news_id, 'Читать') . '</span>' .
						'<p>' . word_limiter(strip_tags($key->news_text), 70) . '</p>' . 
					'</li>';
				$i++;
			}
			?>
		</ul>
	</div>
	<?=$pagination?>
</div>