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

/**
 * Class Delete
 * @package <?= $handler_namespace . '\\' . Generator::PATH_ACTIONS . "\n"; ?>
 */
class Delete extends AbstractAction
{
    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute() : bool
    {
        $this-><?= $param_name; ?>->deleteModel();
        return true;
    }
}