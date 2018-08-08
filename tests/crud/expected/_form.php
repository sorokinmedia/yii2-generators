<?php
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var $this View */
/** @var $model sorokinmedia\gii\generators\tests\crud\Post */
/** @var $form ActiveForm */
?>
<div class="post-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php //TODO: убери ID если не нужен ?>
    <?= $form->field($model_form, 'id')->textInput() ?>
    <?= $form->field($model_form, 'ticker')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model_form, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model_form, 'type_id')->textInput() ?>
    <?= $form->field($model_form, 'exchange_id')->textInput() ?>
    <?= $form->field($model_form, 'google_link')->textarea(['rows' => 6]) ?>
    <?= $form->field($model_form, 'sector_id')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-flat btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>