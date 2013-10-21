</div>
<div class="a_menu">
	<h2>Навигация</h2>
    <ul class="menu">
	    <li><?=anchor('admin/enter', 'Статистика','class="admin_menu"');?></li>
        <li>
            <?=anchor('admin/allNews', 'Новости','class="admin_menu"');?>
            <ul>
                <li><?=anchor('admin/newNews', 'Добавить новость','class="admin_menu"');?></li>
                <li><?=anchor('admin/category', 'Категории','class="admin_menu"');?></li>
            </ul>
        </li>
        <li><?=anchor('admin/users', 'Пользователи','class="admin_menu"');?></li>
        <li><?=anchor('admin/options', 'Настройки','class="admin_menu"');?></li>
	    <li><?=anchor('admin/aExit', 'Выход','class="admin_menu"');?></li>
    </ul>
</div>
</body>
</html>