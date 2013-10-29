<script type="text/javascript">
    $(function() {
        $('#date').datepicker();
        /*$('select').selectbox();
        $('.selectbox ul, .selectbox .dropdown').css('width', '100%');     */   
    });   
</script>
<h2>Добавить новость</h2>
<?=$formopen?>
<table>
    <tr>
    	<td>Название</td>
    	<td><?=$name?></td>
    </tr>
    <tr>
    	<td colspan="2"><?=$text?> <?=display_ckeditor($ckeditor); ?></td>
    </tr>
    <tr>
    	<td>Дата публикации</td>
    	<td><?=$date?></td>
    </tr>
    <tr>
        <td>Категория</td>
        <td>
            <label><input type="checkbox" checked="checked" value="1" name="category[]">Новости</label><br />
            <?php
            foreach ($categorys as $key)
                if($key->id != 1)
                    echo '<label>' . form_checkbox('category[]', $key->id, (in_array($key->id, $category) ? true : false)) . $key->name . '</label><br />'; 
            ?>
        </td>
    </tr>
    <tr>
        <td>Мета описание</td>
        <td><?=$meta_desc?></td>
    </tr>
    <tr>
        <td>Мета ключи</td>
        <td><?=$meta_key?></td>
    </tr>    

    <tr>
    	<td>Картинка</td>
    	<td>
            <?=$img?>
            <?php if(isset($img_src)) echo '<img src="'. base_url() . $img_src . '">' ?>
        </td>
    </tr>
    <tr>
    	<td class="center" colspan="2"><?=$submit?></td>
    </tr>
</table>
<?php if(isset($hidden)) echo $hidden; ?>
<?=$formclose?>

