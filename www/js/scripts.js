// Функция оокрашивания колонки табл при валидности или не валидности
// @submit - boolen прошла ли форма проверку, блакировка кнопки сабмит
// @classes - boolen прошла ли форма проверку, добавление класса с иконкой
// @backg - цвет фона для строки в таблице
// @obj - Объект
var recolorColom = function (submit, classes, backg, obj)
{
    if (typeof submit === 'undefined') submit = true;
    if (typeof classes === 'undefined') classes = true;

	if(submit == true)
		$(obj).parent().parent().parent().find('input[type=submit]').removeAttr('disabled');
	else 
		$(obj).parent().parent().parent().find('input[type=submit]').attr('disabled','disabled');
	if(classes == true) 
		$(obj).parent().removeClass('bad').addClass('cool');
	else 
		$(obj).parent().removeClass('cool').addClass('bad');
	
	$(obj).parent().parent().css('background-color', backg);
}



/*********************************************** AVATAR UPLOAD ****************************************************/
// Проверка на выьранный регион.
function checkForm() {
    if (parseInt($('#x1').val())) return true;
    $('.error').html('Пожалуйста, выберите область подрезки, а затем нажмите Загрузить').show();
    return false;
};

// Обновление данных после выбора или изменение пользователем размеров кропинга
function updateInfo(e) {
    $('#x1').val(e.x);
    $('#y1').val(e.y);
    $('#x2').val(e.x2);
    $('#y2').val(e.y2);
    $('#width').val(e.w);
    $('#height').val(e.h);    
};

function fileSelectHandler() {

    // Берем выбранный файл
    var oFile = $('#image_file')[0].files[0];

    // Скрытие всех ошибок
    $('.error').hide();

    // Проверка изображение на тип формата
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        $('.error').html('Пожалуйста, выберите правильный файл изображения (JPG и PNG разрешено)').show();
        return;
    }

    // Проверка на доступный размер
    if (oFile.size > 2500 * 1024) {
        $('.error').html('Вы выбрали слишком большой файл, выбирите пожалуйста файл меньшего размера').show();
        return;
    }

    // Вывод изображения
    var oImage = document.getElementById('preview');

    // подготовить HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {

        // e.target.result содержит DataURL который можно использовать в качестве источника изображения
        oImage.src = e.target.result;
        oImage.onload = function () { // onload event handler

            // Скрытие первого шага, всплытие второго.
            $('.step1').slideUp(1000);
            setTimeout(function() {$('.step2').slideDown(1000);}, 1000);

            // Создание переменных (в этой сфере), о проведении API Jcrop и размера изображения
            var jcrop_api, boundx, boundy;

            // Уничтожить Jcrop если он существует
            if (typeof jcrop_api != 'undefined')
                jcrop_api.destroy();

            // инициализация Jcrop
            $('#preview').Jcrop({
                minSize: [64, 64], 	// минимальный размер
                aspectRatio : 1, 	// соответсвтвие сторон 1:1
                bgOpacity: .2, 		// Прозрачность
                onChange: updateInfo,
                onSelect: updateInfo,
            }, function(){

                // Jcrop использовать API, чтобы получить реальный размер
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];

                // Храните API Jcrop в переменной jcrop_api
                jcrop_api = this;
            });
        };
    };

    // считать выбранный файл в качестве DataURL
    oReader.readAsDataURL(oFile);
}