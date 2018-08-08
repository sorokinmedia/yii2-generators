<?php
namespace sorokinmedia\gii\generators\handler;

use yii\db\ActiveRecord;
use yii\gii\CodeFile;

/**
 * Генератор класса хендлера, интерфейсов и экшенов
 *
 * Class Generator
 * @package sorokinmedia\gii\generators\handler
 *
 * @property string $modelClass
 * @property string $handlerClass
 * @property string $componentUrl
 * @property boolean $needCreate
 * @property boolean $needUpdate
 * @property boolean $needDelete
 */
class Generator extends \yii\gii\Generator
{
    const PATH_INTERFACES = 'interfaces';
    const PATH_ACTIONS = 'actions';

    public $modelClass;
    public $handlerClass;
    public $componentUrl = '@common/components';
    public $needCreate = true;
    public $needUpdate = true;
    public $needDelete = true;

    /**
     * название генератора
     * @return string
     */
    public function getName()
    {
        return 'Handler Generator';
    }

    /**
     * описание генератора
     * @return string
     */
    public function getDescription()
    {
        return 'This generator create Handler Class, interfaces, actions for CRUD';
    }

    /**
     * автоматически заполненные атрибуты
     * @return array
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['componentUrl']);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'componentUrl', 'componentUrl'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'componentUrl', 'componentUrl'], 'required'],
            [['modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['handlerClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::class]],
            [['componentUrl'], 'validatePath'],
            [['needCreate', 'needUpdate', 'needDelete'], 'boolean']
        ]);
    }

    /**
     * валидация пути на существование
     * @param $attribute
     */
    public function validatePath($attribute)
    {
        $path = \Yii::getAlias($this->$attribute, false);
        if ($path === false || !is_dir($path)) {
            $this->addError($attribute, 'Path does not exist.');
        }
    }

    /**
     * лейблы полей формы
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class path',
            'handlerClass'=> 'Handler Class name',
            'componentUrl' => 'Component namespace',
            'needCreate' => 'Need create action',
            'needUpdate' => 'Need update action',
            'needDelete' => 'Need delete action',
        ]);
    }

    /**
     * подсказки к полям формы
     * @return array
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'Type full path to model class. Example: common\components\exchange\entities\Exchange\Exchange',
            'handlerClass' => 'Type name of Handler Class. Example: ExchangeHandler',
            'componentUrl' => 'Type the namespace for component. Example @common/components/exchange',
            'needCreate' => 'Check if you need create action',
            'needUpdate' => 'Check if you need update action',
            'needDelete' => 'Check if you need delete action',
        ]);
    }

    /**
     * дефолтные шаблоны
     * @return array
     */
    public function requiredTemplates()
    {
        return [
            'handler.php',
            'action-executable.php',
            'interface-create.php',
            'interface-update.php',
            'interface-delete.php',
            'abstract-action.php',
            'action-create.php',
            'action-update.php',
            'action-delete.php',
        ];
    }

    /**
     * генерация файла
     * @return array|CodeFile[]
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            $this->getHandlerFilePath(),
            $this->render('handler.php')
        );
        $files[] = new CodeFile(
            $this->getInterfacesPath('ActionExecutable'),
            $this->render('action-executable.php')
        );
        if ($this->needCreate === true) {
            $files[] = new CodeFile(
                $this->getInterfacesPath('Create'),
                $this->render('interface-create.php')
            );
        }
        if ($this->needUpdate === true) {
            $files[] = new CodeFile(
                $this->getInterfacesPath('Update'),
                $this->render('interface-update.php')
            );
        }
        if ($this->needDelete === true) {
            $files[] = new CodeFile(
                $this->getInterfacesPath('Delete'),
                $this->render('interface-delete.php')
            );
        }
        $files[] = new CodeFile(
            $this->getActionsPath('AbstractAction'),
            $this->render('abstract-action.php')
        );
        if ($this->needCreate === true) {
            $files[] = new CodeFile(
                $this->getActionsPath('Create'),
                $this->render('action-create.php')
            );
        }
        if ($this->needUpdate === true){
            $files[] = new CodeFile(
                $this->getActionsPath('Update'),
                $this->render('action-update.php')
            );
        }
        if ($this->needDelete === true){
            $files[] = new CodeFile(
                $this->getActionsPath('Delete'),
                $this->render('action-delete.php')
            );
        }
        return $files;
    }

    /**
     * сформировать имя класса формы
     * @return string
     */
    public function getModelClassName()
    {
        return pathinfo(str_replace('\\', '/', $this->modelClass), PATHINFO_BASENAME);
    }

    /**
     * сформировать путь нового файла
     * @return string
     */
    public function getHandlerFilePath() : string
    {
        return \Yii::getAlias(str_replace('\\', '/', $this->componentUrl)) . '/handlers/' . $this->getModelClassName() . '/' . $this->handlerClass . '.php';
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getInterfacesPath(string $filename) : string
    {
        return \Yii::getAlias(str_replace('\\', '/', $this->componentUrl)) . '/handlers/' . $this->getModelClassName() . '/' . self::PATH_INTERFACES . '/' . $filename . '.php';
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getActionsPath(string $filename) : string
    {
        return \Yii::getAlias(str_replace('\\', '/', $this->componentUrl)) . '/handlers/' . $this->getModelClassName() . '/' . self::PATH_ACTIONS . '/' . $filename . '.php';
    }

    /**
     * получение namespace из указанного пути с алиасом
     * @return string
     */
    public function getNamespace() : string
    {
        if (mb_substr($this->componentUrl, 0, 1) === '@'){
            return str_replace('/', '\\', mb_substr($this->componentUrl, 1));
        }
        return str_replace('/', '\\', $this->componentUrl);
    }

    /**
     * сообщение при успешной генерации
     * @return string
     */
    public function successMessage()
    {
        $output = "<p>Form class generated successfully.</p>";

        return $output;
    }
}