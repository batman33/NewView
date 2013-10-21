<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tableNews').dataTable();
        $('select').selectbox();
        $('.select').css('width','25px');
    });

    var edit_cat = function(obj) {
        var id = $(obj).attr('id'),
            name = $(obj).attr('name'),
            desc = $(obj).attr('desc');

        if($('.form-edit-category').length) $('.form-edit-category').remove();
        $('.echo-category').after(
            '<div class="form-edit-category">'+
                '<form action="/index.php/admin/category" method="post" accept-charset="utf-8">'+
                    '<table>'+
                        '<tr><th colspan="2">Редактировать категорию "'+name+'"</th></tr>'+
                        '<tr><td>Название</td><td><input type="text" name="name" value="'+name+'" id="name" maxlength="500" size="35"/></td></tr>'+
                        '<tr><td>Описание</td><td><textarea name="description" cols="30" rows="5" id="description">'+desc+'</textarea></td></tr>'+
                        '<tr><td class="center" colspan="2"><input type="submit" name="submit" value="Сохранить" /> <a class="button" href="javascript:void(0);" onClick="remove()">Отмена</a></td></tr>'+
                    '</table>'+
                '<input type="hidden" name="action" value="edit"/>'+
                '<input type="hidden" name="id" value="'+id+'"/></form>'+
            '</div>');
    }

    var remove = function () {
        if($('.form-edit-category').length) $('.form-edit-category').remove();
    }

    var delete_cat = function(obj) {
        var name = $(obj).attr('name'),
            id = $(obj).attr('id');
        var confirmetion = confirm("Вы действительно хотите удалить категорию \""+ name +"\"?");

        if (confirmetion == true){
            $.post("/index.php/admin/categoryDelete", {delete_id: id});
            $(obj).parent().parent().remove();
            alert("Категория удалена!");
        }
    }


</script>
<h2>Категории</h2>

<div class="form-add-category">
    <?=$formopen?>
    <table>
        <tr>
            <th colspan="2">Добавить категорию</th>
        </tr>
        <tr>
        	<td>Название</td>
        	<td><?=$name?></td>
        </tr>
        <tr>
            <td>Описание</td>
        	<td><?=$description?></td>
        </tr>
        <tr>
        	<td class="center" colspan="2"><?=$submit?></td>
        </tr>
    </table>
    <?=$hidden?>
    <?=$formclose?>
    <?=validation_errors('<div class="error">', '</div>');?>
</div>

<div class="echo-category">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="tableNews">
        <thead>
            <tr>
                <th>Название</th>
                <th>Комментарий</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($category as $val) {
                    echo '<tr>'.
                            '<td><b>'.$val->name.'</b></td>'.
                            '<td>'. $val->description .'</td>'.
                            '<td><a href="javascript:void(0);" id="'.$val->id.'" name="'.$val->name.'" desc="'. $val->description .'" class="table-link-edit" onClick="edit_cat(this);"></a>'. 
                                 '<a href="javascript:void(0);" id="'.$val->id.'" name="'.$val->name.'" class="table-link-delete" onClick="delete_cat(this);"></a>'.
                            '</td>'.
                        '</tr>';
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Название</th>
                <th>Комментарий</th>
                <th></th>
            </tr>
        </tfoot>            
    </table>    
</div>