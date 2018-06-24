<?php
use yii\web\View;
use yii\bootstrap\ActiveForm;
use ma3oBblu\gii\generators\form\GeneratorAsset;
use ma3obblu\gii\generators\form\Generator;

/** @var $this View */
/** @var $form ActiveForm */
/** @var $generator Generator */

GeneratorAsset::register($this);
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'componentUrl');
echo $form->field($generator, 'formUrl');
echo $form->field($generator, 'formClass');