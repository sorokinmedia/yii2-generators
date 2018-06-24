<?php
namespace ma3obblu\gii\generators\form;

use yii\db\ActiveRecord;
use yii\gii\CodeFile;

/**
 * Генератор класса формы из класса сущности
 *
 * Class Generator
 * @package ma3obblu\gii\generators\form
 *
 * @property string $modelClass
 * @property string $componentUrl
 * @property string $formUrl
 * @property string $formClass
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass;
    public $componentUrl = '@common/components';
    public $formUrl = 'forms';
    public $formClass;

    /**
     * название генератора
     * @return string
     */
    public function getName()
    {
        return 'Form Class Generator';
    }

    /**
     * описание генератора
     * @return string
     */
    public function getDescription()
    {
        return 'This generator create Form Class from Entity Class.';
    }

    /**
     * автоматически заполненные атрибуты
     * @return array
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['formUrl']);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'componentUrl', 'formClass'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'componentUrl', 'formClass'], 'required'],
            [['modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['formClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::class]],
            [['componentUrl'], 'validatePath'],
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
            'modelClass' => 'Model Class',
            'componentUrl' => 'Component URL',
            'formUrl' => 'Form URL',
            'formClass' => 'Form Class',
        ]);
    }

    /**
     * подсказки к полям формы
     * @return array
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the model class...',
            'componentUrl' => 'This is the namespace for component..',
            'formClass' => 'This is the name for form class..',
            'formUrl' => 'This is the name for the form folder in component..',
        ]);
    }

    /**
     * дефолтные шаблоны
     * @return array
     */
    public function requiredTemplates()
    {
        return ['class.php'];
    }

    /**
     * генерация файла
     * @return array|CodeFile[]
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            $this->getFilePath(),
            $this->render('class.php')
        );
        return $files;
    }

    /**
     * сформировать имя класса формы
     * @return string
     */
    public function getFormClassName()
    {
        if (!empty($this->formClass)) {
            return $this->formClass;
        } else {
            return pathinfo(str_replace('\\', '/', $this->modelClass), PATHINFO_BASENAME) . 'Form';
        }
    }

    /**
     * сформировать путь нового файла
     * @return string
     */
    public function getFilePath() : string
    {
        return \Yii::getAlias(str_replace('\\', '/', $this->componentUrl)) . '/' . $this->formUrl . '/' . $this->getFormClassName() . '.php';
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