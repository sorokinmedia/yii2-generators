<?php
use ma3oBblu\gii\generators\form\GeneratorAsset;

/** @var $this yii\web\View */
/** @var $form yii\widgets\ActiveForm */
/** @var $generator Ma3oBblu\gii\generators\form\Generator */

GeneratorAsset::register($this);
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'componentUrl');
echo $form->field($generator, 'formUrl');
echo $form->field($generator, 'formClass');