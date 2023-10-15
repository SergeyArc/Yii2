<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Organizer $model */

$this->title = 'Изменение организатора' . $model->name;

?>
<div class="update">

    <h1>
        <?= Html::a('←', ['/organizers'], ['style' => ['text-decoration' => 'none']]) ?>
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
