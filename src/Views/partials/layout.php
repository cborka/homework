<!DOCTYPE html>
<html lang="ru">
<head title="Начало">
    <meta charset="utf-8" />

    <meta name="description" content="Начало...">
    <meta name="author" content="cborka">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="/css/style.css?dt=<?= date("Y-m-d H:i:s\: "); ?>" />

    <!--    <script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!--    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>-->

<!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />-->
    <script src="/js/lib.js?dt=<?= date("Y-m-d H:i:s\: "); ?>"></script>

</head>
<body>


<div class="grid-container">

    <?php include 'header.php'; ?>

<!--    --><?php //include 'left_col.php'; ?>
    <?php if ($params['left_col']) include 'left_col.php'; ?>

    <main>
    <div>
        <?php
            if ($filename) {
                include $filename;
            } else {
                echo $content;
            }
        ?>
    </div>
    </main>

    <?php if ($params['right_col']) include 'right_col.php'; ?>

    <?php include 'footer.php'; ?>

</div>

</body>
</html>
