<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use \yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Organizer $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'date')->widget(DatePicker::className(),
        [
            'value' => \Yii::$app->formatter->asDate($model->date, 'long'),
            'language' => 'ru',
            'dateFormat' => 'php:d.m.Y',
            'clientOptions' => [
                'changeYear' => true,
                'changeMonth' => true,
            ],
        ])->textInput()
     ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'organizers')->widget(Select2::classname(), [
            'data' => $organizersList,
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Организаторы');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
