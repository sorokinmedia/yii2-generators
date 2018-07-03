<?php
use yii\web\View;
use ma3obblu\gii\generators\entity\Generator;
use ma3obblu\gii\generators\helpers\GeneratorHelper;
/** @var $this View */
/** @var $generator Generator */
/** @var $className string class name */
/** @var $classARName string AR class name */
/** @var $classFormName string Form class name */
/** @var $nsForm string Form class namespace */
/** @var $properties array list of properties (property => [type, name. comment]) */

echo "<?php\n";
?>
namespace <?= $generator->ns ?>;

use <?= $nsForm . $classFormName . ";\n"; ?>
use yii\db\Exception;

/**
 * Class <?= $className . "\n"; ?>
 * @package <?= $generator->ns . "\n"; ?>
 *
 * @property <?= $classFormName; ?> $form
 */
class <?= $className ?> extends <?= $classARName . "\n" ?>
{
    public $form;

    /**
     * <?= $className; ?> constructor.
     * @param array $config
     * @param <?= $classFormName; ?>|null $form
     */
    public function __construct(array $config = [], <?= $classFormName; ?> $form = null)
    {
        if (!is_null($form)){
            $this->form = $form;
        }
        parent::__construct($config);
    }

    /**
     * трансфер данных из формы в модель
     */
    public function getFromForm()
    {
        if (!is_null($this->form)){
<?= GeneratorHelper::generateGetFromForm($properties, $generator->needId); ?>
        }
    }

<?php if ($generator->needInsertModel === true) { ?>
    /**
     * добавление модели в БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function insertModel() : bool
    {
        $this->getFromForm();
        if (!$this->insert()){
            throw new Exception(\Yii::t('app', 'Ошибка при добавлении модели в БД'));
        }
        return true;
    }
<?php } ?>

<?php if ($generator->needUpdateModel === true) { ?>
    /**
     * обновление модели в БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateModel() : bool
    {
        $this->getFromForm(); //TODO: убери, если не нужно
        if (!$this->update()){
            throw new Exception(\Yii::t('app', 'Ошибка при обновлении модели в БД'));
        }
        return true;
    }
<?php } ?>

<?php if ($generator->needDeleteModel === true) { ?>
    /**
     * удаление модели из БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteModel() : bool
    {
        if (!$this->delete()){
            throw new Exception(\Yii::t('app', 'Ошибка при удалении модели из БД'));
        }
        return true;
    }
}
<?php } ?>