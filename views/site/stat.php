<?php
$this->title = 'Статистика';
?>

<div>
    <p><strong>Количество получателей: </strong><?= \app\models\Receiver::find()->count() ?></p>
</div>


