<?php
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\data\ActiveDataProvider;
use tests\runtime\data\PostSearch;

/** @var $this View */
/** @var $searchModel PostSearch */
/** @var $dataProvider ActiveDataProvider */

$this->title = \Yii::t('app', 'Все Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <p>
        <?= Html::a(\Yii::t('app', 'Добавить Post'), ['create'], ['class' => 'btn btn-flat btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'ticker',
            'name',
            'type_id',
            'exchange_id',
            //'google_link:ntext',
            //'sector_id',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
