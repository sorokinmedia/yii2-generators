<?php
use yii\web\View;
use yii\helpers\Inflector;
use ma3obblu\gii\generators\handler\Generator;

/** @var $this View */
/** @var $generator Generator */

$handler_namespace = $generator->getNamespace() . '\handlers\\' . $generator->getModelClassName();
$param_name = Inflector::camel2id($generator->getModelClassName(), '_');
echo "<?php\n";
?>
namespace <?= $handler_namespace . '\\' . Generator::PATH_INTERFACES . ";\n"; ?>

/**
 * Interface Update
 * @package <?= $handler_namespace . '\\' . Generator::PATH_INTERFACES . "\n"; ?>
 */
interface Update
{
    /**
     * @return bool
     */
    public function update() : bool;
}