<?php

namespace app\controllers;

use app\models\Event;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Event();

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $model->eventsDataProvider(),
        ]);
    }
}
