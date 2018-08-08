<?php
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use sorokinmedia\gii\generators\tests\crud\Post;

/** @var $this yii\web\View */
/** @var $model Post */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Все Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
    <p>
        <?= Html::a(\Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::a(\Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-flat btn-danger',
            'data' => [
                'confirm' => \Yii::t('app', 'Вы действительно хотите удалить этот элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ticker',
            'name',
            'type_id',
            'exchange_id',
            'google_link:ntext',
            'sector_id',
        ],
    ]) ?>
</div>