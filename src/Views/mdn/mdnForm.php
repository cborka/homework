<form action="/mdn/save" method="post">
    <h3>Редактирование записи</h3>
    id<br>
    <input type="number" name="id" value="<?php echo $_POST['id'] ?>" readonly><br>
    Дата и время<br>
    <input type="datetime-local" name="dt" value="<?php echo $_POST['dt'] ?>"><br>
    Заголовок<br>
    <input type="text" name="header" value="<?php echo $_POST['header'] ?>"><br>
    Текст<br>
    <textarea name="content" rows="10" cols="60"><?php echo $_POST['content'] ?></textarea><br>
    Пароль<br>
    <input type="number" name="password" value="0"><br>
    <button>Сохранить</button>
</form>