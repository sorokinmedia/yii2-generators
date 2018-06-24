<?php
namespace Ma3oBblu\gii\form;

use yii\db\ActiveRecord;
use yii\gii\CodeFile;

/**
 * Class Generator
 * @package Ma3oBblu\gii\form
 *
 * @property string $modelClass
 * @property string $componentUrl
 * @property string $formUrl
 * @property string $formClass
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass;
    public $componentUrl = 'common\components';
    public $formUrl = 'forms';
    public $formClass;

    /**
     * @return string
     */
    public function getName()
    {
        return 'Form Class Generator';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'This generator create Form Class from Entity Class.';
    }

    /**
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
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
            [['componentUrl'], 'validatePath'],
        ]);
    }

    /**
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
     * @return array
     */
    public function requiredTemplates()
    {
        return ['class.php'];
    }

    /**
     * @return array|CodeFile[]
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            \Yii::getAlias('@' . str_replace('\\', '/', $this->componentUrl)) . '/' . $this->formUrl . '/' . $this->getFormClassName() . '.php',
            $this->render('class.php')
        );

        return $files;
    }

    /**
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
     * @return string
     */
    public function successMessage()
    {
        $output = "<p>Form class generated successfully.</p>";

        return $output;
    }
}