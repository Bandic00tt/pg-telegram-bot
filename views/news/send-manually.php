<?php
use yii\helpers\Html;

$this->title = 'Ручное отправление новости';
?>

<div class="row">
    <div class="col-md-6">
        <?= Html::beginForm() ?>
        <div class="form-group">
            <?= Html::label('Заголовок:') ?>
            <?= Html::textInput('title', null, [
                'class' => 'form-control',
                'required' => true
            ]) ?>
        </div>
        <div class="form-group">
            <?= Html::label('Ссылка:') ?>
            <?= Html::textInput('link', null, [
                'class' => 'form-control',
                'required' => true
            ]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Отправить', [
                'class' => 'btn btn-primary'
            ]) ?>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>


