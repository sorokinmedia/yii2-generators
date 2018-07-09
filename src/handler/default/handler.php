<?php
use yii\web\View;
use yii\helpers\Inflector;
use ma3obblu\gii\generators\handler\Generator;

/** @var $this View */
/** @var $generator Generator */

$handler_namespace = $generator->getNamespace() . '\handlers\\' . $generator->getModelClassName();
$param_name = Inflector::camel2id($generator->getModelClassName(), '_');
$use_array = [];
echo "<?php\n";
?>
namespace <?= $handler_namespace . ";\n"; ?>

use <?= $handler_namespace; ?>\interfaces\{<?= "\n    "?>
<?php if ($generator->needCreate === true) $use_array[] = 'Create'; ?>
<?php if ($generator->needUpdate === true) $use_array[] = 'Update'; ?>
<?php if ($generator->needDelete === true) $use_array[] = 'Delete'; ?>
<?= implode(', ', $use_array); ?>
<?= "\n"; ?>
};
use <?= $generator->modelClass; ?>;

/**
 * Class <?= $generator->handlerClass . "\n"; ?>
 * @package <?= $handler_namespace . "\n"; ?>
 *
 * @property <?= $generator->getModelClassName(); ?> $<?= $param_name . "\n"; ?>
 */
class <?= $generator->handlerClass; ?> implements <?= implode(', ', $use_array) . "\n"; ?>
{
    private $<?= $param_name; ?>;

    /**
     * <?= $generator->handlerClass; ?> constructor.
     * @param <?= $generator->getModelClassName(); ?> $<?= $param_name . "\n"; ?>
     */
    public function __construct(<?= $generator->getModelClassName(); ?> $<?= $param_name; ?>)
    {
        $this-><?= $param_name; ?> = $<?= $param_name; ?>;
        return $this;
    }
<?php if ($generator->needCreate == true) {
    echo "\n"; ?>
    /**
     * @return bool
     * @throws \Throwable
     */
    public function create() : bool
    {
        return (new actions\Create($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>
<?php if ($generator->needUpdate == true) {
    echo "\n"; ?>
    /**
     * @return bool
     */
    public function update() : bool
    {
        return (new actions\Update($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>
<?php if ($generator->needDelete == true) {
    echo "\n"; ?>
    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete() : bool
    {
        return (new actions\Delete($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>
}