<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Настройки прокси';
?>

<div>
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([
                'action' => 'add-proxy'
            ]) ?>

            <?= $form->field($model, 'ip') ?>
            <?= $form->field($model, 'port') ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>
            <small>Используется последний добавленный прокси</small>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach ($proxies as $proxy): ?>
                    <li class="list-group-item">
                        <?= $proxy->ip ?>:<?= $proxy->port ?>
                        <span class="badge">
                            <?= Html::a('<i class="fa fa-close"></i>', ['delete-proxy', 'id' => $proxy->id], [
                                'style' => 'color: #fff',
                                'data' => [
                                    'method' => 'post'
                                ]
                            ]) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>  
</div>
