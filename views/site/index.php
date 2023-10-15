<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
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
            ],
        ]) ?>
    </div>
</div>
