<?php
use yii\web\View;
use yii\bootstrap\ActiveForm;
use ma3obblu\gii\generators\handler\Generator;

/** @var $this View */
/** @var $form ActiveForm */
/** @var $generator Generator */

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'handlerClass');
echo $form->field($generator, 'componentUrl');
echo $form->field($generator, 'needCreate')->checkbox();
echo $form->field($generator, 'needUpdate')->checkbox();
echo $form->field($generator, 'needDelete')->checkbox();