<?php
use yii\web\View;
use yii\bootstrap\ActiveForm;
use ma3obblu\gii\generators\fixture_data\Generator;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $generator Generator */

echo $form->field($generator, 'modelClass');
?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($generator, 'pkFirstName');?>
        </div>
        <div class="col-gl-6">
            <?= $form->field($generator, 'pkFirstValue'); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-8">
            <?= $form->field($generator, 'pkSecondName'); ?>
        </div>
        <div class="col-gl-4">
            <?= $form->field($generator, 'pkSecondValue'); ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
echo $form->field($generator, 'relations')->textarea(['rows' => 6]);
echo $form->field($generator, 'dataFile');
echo $form->field($generator, 'dataPath');
