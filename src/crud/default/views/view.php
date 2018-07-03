<?php
use yii\web\View;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use ma3obblu\gii\generators\crud\Generator;

/* @var $this View */
/* @var $generator Generator */

$urlParams = $generator->generateUrlParams();
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>
use yii\bootstrap\Html;
use yii\widgets\DetailView;
use <?= ltrim($generator->modelClass, '\\') ?>;

/** @var $this yii\web\View */
/** @var $model <?= $modelClass; ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString("Все " . Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Изменить') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-flat btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Удалить') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-flat btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Вы действительно хотите удалить этот элемент?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>
</div>