<div class="content full">
	<h2>Аватар</h2>
	
	<div class="avatar-upload">
	    <!-- Форма загрузки -->
	    <?=$form_open?>
	        <?=$hidden?>
	        <div class="step1">
		        <?=form_label('Загрузите фото', 'upload', array('class' => 'upload-button'));?>
		        <?=$upload?>
	        </div>
	        <div class="step2">
	            Пожалуйста выделите регион для обрезания
	            <img id="preview" />
	            <?=$submit?>
	        </div>
	        <div class="error"></div>
	    <?=$form_close?>
	</div>

</div>