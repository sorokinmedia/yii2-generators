<?php
use yii\web\View;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use sorokinmedia\gii\generators\crud\Generator;

/** @var $this View */
/** @var $generator Generator */

/** @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
echo "<?php\n";
?>
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var $this View */
/** @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/** @var $form ActiveForm */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <?= "<?php " ?>$form = ActiveForm::begin(); ?>
    <?= "<?php //TODO: убери ID если не нужен ?>\n" ?>
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n";
    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Сохранить') ?>, ['class' => 'btn btn-flat btn-success']) ?>
    </div>
    <?= "<?php " ?>ActiveForm::end(); ?>
</div>