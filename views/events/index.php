<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мероприятия';
?>
<div class="site-index">
    <div class="body-content">
        <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?></p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'title',
                'date',
                'description',
                [
                    'attribute'=>'Организаторы',
                    'value' => function ($model) {
                        $names = ArrayHelper::getColumn($model->organizers, 'name');

                        return implode(", ", $names);
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                ],
            ],
        ]) ?>
    </div>
</div>