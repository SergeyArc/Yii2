<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Event $model */

$this->title = 'Добавление мероприятия';

?>
<div class="create">

    <h1>
        <?= Html::a('←', ['/events'], ['style' => ['text-decoration' => 'none']]) ?>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'organizersList' => $organizersList,
    ]) ?>

</div>
