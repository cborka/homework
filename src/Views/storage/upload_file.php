<h1>Загрузка файла</h1>

<?php if (isset($_SESSION['login']) && ($_SESSION['login'] !== 'Guest')) { ?>

<form action="/storage/save_uploaded" method="post" enctype="multipart/form-data">

    Загрузить файл <input type="file" name="filename"><br>
    <br>
    <button type="submit">Загрузить</button>

</form>

<?php } else { ?>
    ... но сначала надо зарегистрироватся.
<?php } ?>
