<!DOCTYPE html>
<html lang="ru">
<head title="Начало">
    <meta charset="utf-8" />

    <meta name="description" content="Начало...">
    <meta name="author" content="cborka">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="/css/style.css" />
    <script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->

</head>
<body>


<div class="grid-container">

    <?php include 'header.php'; ?>

    <aside class="left">
        левая колонка
    </aside>

    <main>
    <div>
        <?= $content; ?>
    </div>
    </main>

    <aside class="right">
        правая колонка
    </aside>


    <?php include 'footer.php'; ?>

</div>

</body>
</html>
