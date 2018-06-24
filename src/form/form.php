<?php
use yii\web\View;
use yii\bootstrap\ActiveForm;
use ma3obblu\gii\generators\form\Generator;
use ma3obblu\gii\generators\form\GeneratorAsset;

/** @var $this View */
/** @var $form ActiveForm */
/** @var $generator Generator */

GeneratorAsset::register($this);
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'componentUrl');
echo $form->field($generator, 'formUrl');
echo $form->field($generator, 'formClass');
echo $form->field($generator, 'needId')->checkbox();
echo $form->field($generator, 'getAttributes')->checkbox();