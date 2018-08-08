<?php
use yii\web\View;
use sorokinmedia\gii\generators\tests\crud\Post;
use tests\runtime\data\forms\PostForm;

/** @var $this View */
/** @var $model Post */
/** @var $model_form PostForm */

$this->title = \Yii::t('app', 'Изменить');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Все Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="post-update">
    <?= $this->render('_form', [
        'model' => $model,
        'model_form' => $model_form,
    ]) ?>
</div>
