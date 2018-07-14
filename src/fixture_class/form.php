<?php
use yii\web\View;
use yii\bootstrap\ActiveForm;
use ma3obblu\gii\generators\fixture_class\Generator;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $generator Generator */

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'fixtureClass');
echo $form->field($generator, 'fixtureNs');
echo $form->field($generator, 'dataFile');
echo $form->field($generator, 'dataPath');
