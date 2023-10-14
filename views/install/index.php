<?php
/** @var bool $hasCredentials */
/** @var string $credentialsStatus */

/** @var \yii\web\View $this */

$this->title = 'Установка приложения';

?>

<div class="row">
    <div class="col-xs-3">
        <div class="thumbnail">
            <div class="caption">
                <h3>Установка приложения</h3>
                <hr>
                <p><?= $installStatus['message'] ?></p>
                <hr>
                <?php if (!$installStatus['success']): ?>
                    <p><a href="/install/finish" class="btn btn-primary" role="button">Установить</a></p>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="thumbnail">
            <div class="caption">
                <h3>Доступы администратора</h3>
                <hr>
                <p><?= $credentialsStatus['message'] ?></p>
                <hr>
                <p><a href="/install/set-admin-credentials" class="btn btn-primary" role="button">Обновить</a>
                </p>
            </div>
        </div>
    </div>
</div>