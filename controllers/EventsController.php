<?php

namespace app\controllers;

use app\models\{Event, Organizer};
use yii\web\Controller;
use yii\helpers\ArrayHelper;

class EventsController extends Controller
{
    public function actionIndex()
    {
        $model = new Event();

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $model->eventsDataProvider(),
        ]);
    }

    public function actionCreate()
    {
        $model = new Event();
        $organizersList = Organizer::allOrganizers();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $post = \Yii::$app->request->post();

                $postOrganizers = !empty($post['Event']['organizers']) ? $post['Event']['organizers'] : [];
                foreach ($postOrganizers as $i => $organizer) {
                    $postOrganizers[$i] = (int) $organizer;
                }

                $organizers = Organizer::findById($postOrganizers);
                foreach ($organizers as $organizer) {
                    $model->link('organizers', $organizer);
                }

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'organizersList' => ArrayHelper::map($organizersList, 'id', 'name'),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Event::findEvent($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $organizersList = Organizer::allOrganizers();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->unlinkAll('organizers', true);
            $post = \Yii::$app->request->post();

            $postOrganizers = !empty($post['Event']['organizers']) ? $post['Event']['organizers'] : [];
            foreach ($postOrganizers as $i => $organizer) {
                $postOrganizers[$i] = (int) $organizer;
            }

            $organizers = Organizer::findById($postOrganizers);
            foreach ($organizers as $organizer) {
                $model->link('organizers', $organizer);
            }

            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
            'organizersList' => ArrayHelper::map($organizersList, 'id', 'name'),
        ]);
    }

    public function actionDelete($id)
    {
        $model = Event::findEvent($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->delete();

        return $this->redirect(['index']);
    }
}
