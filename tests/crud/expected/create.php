<?php
use yii\web\View;
use ma3obblu\gii\generators\tests\crud\Post;
use tests\runtime\data\forms\PostForm;

/** @var $this View */
/** @var $model Post */
/** @var $model_form PostForm */

$this->title = \Yii::t('app', 'Создать Post');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_form' => $model_form,
    ]) ?>
</div>