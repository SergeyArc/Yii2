<?php

namespace app\controllers;

use app\models\Organizer;
use yii\web\Controller;

class OrganizersController extends Controller
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
        $model = new Organizer();

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $model->allOrganizers(),
        ]);
    }

    public function actionCreate()
    {
        $model = new Organizer();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Organizer::findOrganizer($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Organizer::findOrganizer($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->delete();

        return $this->redirect(['index']);
    }
}
