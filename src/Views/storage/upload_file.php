<h1>Загрузка файла</h1>

<?php if (isset($_SESSION['login']) && ($_SESSION['login'] !== 'Guest')) { ?>

<form name="upload_form" action="/storage/save_uploaded" method="post" enctype="multipart/form-data">

    Каталог <input type="text" name="folder" value="<?= $folder; ?>" readonly>
    <input type="text" name="folder_id" value="<?= $folder_id; ?>" readonly hidden>
    <button type="button" onclick="tree_show_on_click('2', 'Тест', t_on_upload);return false;"> Изменить каталог </button>
    <br>

    Загрузить файл <input type="file" name="filename"><br>
    <br>
    <button type="submit">Загрузить</button>

</form>

<?php } else { ?>
    ... но сначала надо зарегистрироватся.
<?php } ?>
