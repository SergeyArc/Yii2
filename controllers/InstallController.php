<?php

namespace app\controllers;

use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;

class InstallController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();

        if (!Yii::$app->bitrix24->isAdmin()) {
            throw new UnauthorizedHttpException('Доступ разрешен только администраторам портала');
        }
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'credentialsStatus' => $this->getCredentialsStatus(),
            'installStatus' => $this->getInstallStatus(),
        ]);
    }

    public function actionFinish()
    {
        Yii::$app->bitrix24->setAdminCredentials(Yii::$app->session->get('b24_auth_data'));

        return $this->render('installFinish');
    }

    private function getCredentialsStatus()
    {
        if (Yii::$app->bitrix24->getAdminCredentials()) {
            $status = [
                'success' => true,
                'message' => "Корректные",
            ];

            try {
                Yii::$app->bitrix24->admin()->call('app.info', []);
            } catch (\Exception $exception) {
                $status = [
                    'success' => false,
                    'message' => "Некорректные. Ошибка: " . $exception->getMessage(),
                ];
            }
        } else {
            $status = [
                'success' => false,
                'message' => "Не заданы",
            ];
        }

        return $status;
    }

    private function getInstallStatus()
    {
        if (Yii::$app->bitrix24->user()->call('app.info')['result']['INSTALLED']) {
            $status = [
                'success' => true,
                'message' => "Установлено",
            ];
        } else {
            $status = [
                'success' => false,
                'message' => "Не установлено",
            ];
        }

        return $status;
    }

    public function actionSetAdminCredentials(): \yii\web\Response
    {

        Yii::$app->bitrix24->setAdminCredentials(Yii::$app->session->get('b24_auth_data'));
        Yii::$app->session->setFlash('success', 'Доступы администратора обновлены.');

        return $this->redirect('index');
    }
}