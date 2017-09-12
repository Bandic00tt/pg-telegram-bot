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
    'dataProvider' => $dataProvider
]) ?>

