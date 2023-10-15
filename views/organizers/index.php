<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Организаторы';
?>
<div class="site-index">
    <div class="body-content">
        <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?></p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                'email',
                'phone',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                ],
            ],
        ]) ?>
    </div>
</div>