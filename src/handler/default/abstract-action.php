<?php
use yii\web\View;
use yii\helpers\Inflector;
use sorokinmedia\gii\generators\handler\Generator;

/** @var $this View */
/** @var $generator Generator */

$handler_namespace = $generator->getNamespace() . '\handlers\\' . $generator->getModelClassName();
$param_name = Inflector::camel2id($generator->getModelClassName(), '_');
echo "<?php\n";
?>
namespace <?= $handler_namespace . '\\' . Generator::PATH_ACTIONS . ";\n"; ?>

use <?= $handler_namespace . '\\' . Generator::PATH_INTERFACES . '\\' . "ActionExecutable;\n"; ?>
use <?= $generator->modelClass . ";\n"; ?>

/**
 * Class AbstractAction
 * @package <?= $handler_namespace . '\\' . Generator::PATH_ACTIONS . "\n"; ?>
 *
 * @property <?= $generator->getModelClassName(); ?> $<?= $param_name . "\n"; ?>
 */
abstract class AbstractAction implements ActionExecutable
{
    protected $<?= $param_name; ?>;

    /**
     * AbstractAction constructor.
     * @param <?= $generator->getModelClassName(); ?> $<?= $param_name . "\n"; ?>
     */
    public function __construct(<?= $generator->getModelClassName(); ?> $<?= $param_name; ?>)
    {
        $this-><?= $param_name; ?> = $<?= $param_name; ?>;
        return $this;
    }
}