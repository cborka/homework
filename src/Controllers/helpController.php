<?php

namespace Controllers;

use System\Render;

class helpController
{
    public function actionGit()
    {
        Render::render('Git help<br>');
    }

    public function actionHttp()
    {
        Render::render('Http help<br>');
    }

}