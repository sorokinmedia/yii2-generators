<?php
use yii\web\View;
use yii\base\Model;
use Ma3oBblu\gii\form\Generator;
use Ma3oBblu\gii\helpers\GeneratorHelper;

/** @var $this View */
/** @var $generator Generator */

/** @var Model $entity */
$entity = new $generator->modelClass;
echo "<?php\n";
?>
namespace <?= $generator->componentUrl . "\\" . $generator->formUrl ?>;

use yii\base\Model;

/**
 * Class <?= $generator->getFormClassName(); ?>
 * @package <?= $generator->componentUrl . "\\" . $generator->formUrl; ?>;
 *
 <?= GeneratorHelper::generatePhpDocForClassAttributes($entity); ?>
*/
class <?= $generator->getFormClassName(); ?> extends Model
{
    <?= GeneratorHelper::generateClassParams($entity); ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= GeneratorHelper::convertRules($entity);?>
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            <?= GeneratorHelper::generateAttributeLabels($entity); ?>
        ];
    }

    <?= GeneratorHelper::generateFormConstructor($entity); ?>
}