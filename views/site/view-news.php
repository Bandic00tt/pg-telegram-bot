<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Новости (отладка)';
?>

<?= Html::a('Обновить новости', '', [
    'class' => 'btn btn-success'
]) ?>
<br><br>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
        [
            'attribute' => 'url',
            'format' => 'html',
            'value' => function($model){
                return Html::a($model->url, $model->url, [
                    'target' => '_blank'
                ]);
            }
        ],
        'description',
        [
            'attribute' => 'url',
            'format' => 'html',
            'value' => function($model){
                return Html::img($model->image, [
                    'style' => 'width: 200px;'
                ]);
            }
        ],
        'pub_date',
        'saved_at',
        [
            'attribute' => 'posted',
            'value' => function($model){
                if ($model->posted){
                    return 'Да';
                } else {
                    return 'Нет';
                }
            }
        ]
    ]
]) ?>

