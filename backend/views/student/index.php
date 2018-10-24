<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var backend\models\Groups $group */

$this->title = 'Cтуденты группы: ' . $group->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['group/index']];
$this->params['breadcrumbs'][] = ['label' => $group->name];
?>

<?php
if (Yii::$app->user->can('deleteStudent')) {
    $buttonsTemplate = '{view} {update} {delete}';
} else {
    $buttonsTemplate = '{view} {update}';
}
?>

<div class="student-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить студента', ['create', 'groupId' => $group->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'last_name',
            'middle_name',

            ['class' => 'yii\grid\ActionColumn', 'template' => $buttonsTemplate],
        ],
    ]); ?>
</div>
