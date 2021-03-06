<?php

namespace Controllers;

use System\Render;
use Models\MyDailyNews\MdnDb;


/*
 * Дневник / MyDailyNews
 */
class mdnController
{
    private $logger;


    public function __construct()
    {
        global $logger;

        $this->logger = $logger;
    }

    // Показать записи на экране
    public function actionView()
    {
        $this->logger->debug(self::class . '->actionView()');

        $records = MdnDb::getRecords();
        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/mdn/mdnList.php', $records );
    }

    // Редактировать запись в форме
    public function actionForm()
    {
        $this->logger->debug(self::class . '->actionForm()');

        Render::render('',$_SERVER['DOCUMENT_ROOT'] . '/src/Views/mdn/mdnForm.php');
    }

    // Сохранить запись из формы в БД
    public function actionSave()
    {
        $this->logger->debug(self::class . '->actionSave()');

        if ($_POST['password'] == 21) {
            $result = MdnDb::saveRecord($_POST['id'], $_POST['dt'], $_POST['header'], $_POST['content']);
            if($result == 0) {
                header('location: /mdn/view');
            }
            else {
               // $this->logger->error("Запись в БД не удалась!!!");
               // Ошибка  уже выведена функцией  MdnDb::saveRecord
            }
        }
        else { ?>
            <script>
                alert('Неверный пароль, ваши труды пропали! Увы!');
                window.open('/mdn/view','_self',false);
            </script>

        <?php }
    }

//==================================================================================

    /*
     * Редактирование дневника, аякс-версия
     */
    public function actionEdit()
    {
        $this->logger->debug(self::class . '->actionEdit()');

        Render::render('','mdn/mdnEdit.php');
//        Render::render_file('mdn/mdnEdit.php');
    }


}