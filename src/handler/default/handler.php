<?php
use yii\web\View;
use yii\helpers\Inflector;
use ma3obblu\gii\generators\handler\Generator;

/** @var $this View */
/** @var $generator Generator */

$handler_namespace = $generator->getNamespace() . '\handler\\' . $generator->getModelClassName();
$param_name = Inflector::camel2id($generator->getModelClassName(), '_');
echo "<?php\n";
?>
namespace <?= $handler_namespace . ";\n"; ?>

use <?= $handler_namespace; ?>\interfaces\{
    <?= ($generator->needCreate === true) ? 'Create,' : ''; ?> <?= ($generator->needUpdate === true) ? 'Update,' : ''; ?> <?= ($generator->needDelete === true) ? 'Delete' : ''; ?><?= "\n"; ?>
};
use <?= $generator->modelClass; ?>;

/**
 * Class <?= $generator->handlerClass . "\n"; ?>
 * @package <?= $handler_namespace . "\n"; ?>
 */
class <?= $generator->handlerClass; ?> implements <?= ($generator->needCreate === true) ? 'Create,' : '';?> <?= ($generator->needUpdate === true) ? 'Update,' : '';?> <?= ($generator->needDelete === true) ? 'Delete' : ''; ?><?= "\n"; ?>
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

<?php if ($generator->needCreate === true) { ?>
    /**
     * @return bool
     */
    public function create() : bool
    {
        return (new actions\Create($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>

<?php if ($generator->needUpdate === true) { ?>
    /**
     * @return bool
     */
    public function update() : bool
    {
        return (new actions\Update($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>

<?php if ($generator->needDelete === true) { ?>
    /**
     * @return bool
     */
    public function delete() : bool
    {
        return (new actions\Delete($this-><?= $param_name; ?>))->execute();
    }
<?php } ?>
}