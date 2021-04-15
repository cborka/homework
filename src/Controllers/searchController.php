<?php

namespace Controllers;

use Models\Search\Search;
use System\Render;
use System\Lib;

/*
 * Файловое хранилище
 */

class searchController
{
    private $logger;

    public function __construct()
    {
        global $logger;
        $this->logger = $logger;
    }

    /*
     * Показать результат поиска
     */
    public function actionSearch($params = [])
    {
        $this->logger->debug(self::class . "->actionSearch($params)");

        $txt = $_POST['search_text'] ?? $params[0] ?? '';

        $this->logger->debug($_POST['search_text'] . $txt);

        if (trim($txt) === '') {
            return;
        }

        Search::ShowSearchResults($txt);
     }

}