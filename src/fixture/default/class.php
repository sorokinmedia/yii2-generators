<?php
use yii\web\View;
use ma3obblu\gii\generators\fixture\Generator;
/** @var $this View */
/** @var $generator Generator */

echo "<?php\n";
?>
namespace <?= $generator->fixtureNs ?>;

use yii\test\ActiveFixture;
use <?= $generator->modelClass ?>;

/**
 * Class <?= $generator->getFixtureClassName() . "\n"; ?>
 * @package <?= $generator->fixtureNs . "\n"; ?>
 *
 * @param string $modelClass
 * @param string $dataFile
 */
class <?= $generator->getFixtureClassName(); ?> extends ActiveFixture
{
    public $modelClass = <?= $generator->getModelClassName(); ?>::class;
    public $dataFile = '<?= $generator->dataPath . '/' . $generator->getDataFileName() ?>';
}