<?php

global $logger;

// Фокус оказался в том, что php работает с файловой системой сервера, а html обращается к сайту
// и поэтому адрес картинки (src) указывается относительно корня сайта или текущего каталога.
// Поэтому копирую файл из хранилища в доступный каталог и уже затем показываю.

$dirname = strtoupper(substr($params['file'], 0, 1));

$filename = DOCUMENT_ROOT . "/storage/" . $dirname . '/' . $params['file'];

if (!file_exists($filename)) {
    $logger->debug("+show_image: входной файл: {$params['file']} ");
    $logger->debug('+show_image: файл ' . $filename . ' не существует.');
    return;
}

$copy = DOCUMENT_ROOT . "/public/storage/" . $params['file'];
if (!copy($filename, $copy)) {
    $logger->debug('show_image: Не удалось скопировать файл ' . $params['file']);
}

?>

<h1>Картинка</h1>

<img src="<?= '/storage/' . $params['file']; ?>" alt="<?= $params['file']; ?>">


<script>
//    alert('script');
//    $.ajaxSetup({async:false});

    $.post("/storage/delete",
        {
            filename: "<?= $params['file']; ?>",
        },
        function (data, status) {
            //ar_result = data;
        }
    );

</script>


<?php //unlink($copy); ?>

