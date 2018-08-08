<?php
use yii\web\View;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use sorokinmedia\gii\generators\crud\Generator;

/* @var $this View */
/* @var $generator Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>
use yii\web\View;
use <?= ltrim($generator->modelClass) ?>;
use <?= $generator->componentNs . '\\forms\\' . $modelClass . "Form;\n"; ?>

/** @var $this View */
/** @var $model <?= $modelClass ?> */
/** @var $model_form <?= $modelClass . 'Form'; ?> */

$this->title = <?= $generator->generateString('Изменить') ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString("Все " . Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">
    <?= '<?= ' ?>$this->render('_form', [
        'model' => $model,
        'model_form' => $model_form,
    ]) ?>
</div>
