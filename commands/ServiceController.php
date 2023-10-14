<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class ServiceController extends Controller
{
    public function actionRefreshAdminToken()
    {
        Yii::$app->bitrix24->refreshAdminToken();
    }
}
