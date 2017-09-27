<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Новости (из RSS)';
?>

<?= Html::a('Обновить новости', 'update-list', [
    'class' => 'btn btn-success'
]) ?>
&nbsp;
<?= Html::a('Ручное отправление', 'send-manually', [
    'class' => 'btn btn-danger'
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
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{toggle}',
            'buttons' => [
                'toggle' => function($url, $model, $key){
                    return Html::a('Переключить статус', [
                        'toggle-news-status', 
                        'id' => $model->id
                    ], [
                        'class' => 'btn btn-danger btn-xs'
                    ]);
                }
            ]
        ]
    ]
]) ?>

