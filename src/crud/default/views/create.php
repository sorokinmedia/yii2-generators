<?php
use yii\web\View;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use sorokinmedia\gii\generators\crud\Generator;

/* @var $this View */
/* @var $generator Generator */

$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>
use yii\web\View;
use <?= ltrim($generator->modelClass) ?>;
use <?= $generator->componentNs . '\\forms\\' . $modelClass . "Form;\n"; ?>

/** @var $this View */
/** @var $model <?= $modelClass ?> */
/** @var $model_form <?= $modelClass . 'Form'; ?> */

$this->title = <?= $generator->generateString('Создать ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
<?= "    <?= " . "$" . "this->render('_form', [\n"; ?>
<?= "        'model' => $" . "model,\n" ;?>
<?= "        'model_form' => $" . "model_form,\n" ;?>
<?= "    ]) ?>\n" ;?>
</div>