<?php
namespace ma3obblu\gii\generators\fixture_class;

use yii\db\ActiveRecord;
use yii\gii\CodeFile;

/**
 * Class Generator
 * @package ma3obblu\gii\generators\fixture
 *
 * @property string $modelClass
 * @property string $fixtureClass
 * @property string $fixtureNs
 * @property string $dataFile
 * @property string $dataPath
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass;
    public $fixtureClass;
    public $fixtureNs = 'tests\fixtures';
    public $dataFile;
    public $dataPath = '@tests/fixtures/data';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Fixture Class Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates fixture class for existing model class with data mockup file.';
    }

    /**
     * генерация файлов
     */
    public function generate()
    {
        $files = [];
        // генерация файла класса фикстуры
        $files[] = new CodeFile(
            \Yii::getAlias('@' . str_replace('\\', '/', $this->fixtureNs)) . '/' . $this->getFixtureClassName() . '.php',
            $this->render('class.php')
        );
        // генерация файла с заглушкой под дату фикстуры
        $files[] = new CodeFile(
            \Yii::getAlias($this->dataPath) . '/' . $this->getDataFileName(),
            $this->render('data.php', ['items' => $this->getFixtureData()])
        );
        return $files;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'fixtureClass', 'fixtureNs', 'dataPath'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'fixtureNs', 'dataPath'], 'required'],
            [['modelClass', 'fixtureNs'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['fixtureClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['dataFile'], 'match', 'pattern' => '/^\w+\.php$/', 'message' => 'Only php files are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::class]],
            [['dataPath'], 'match', 'pattern' => '/^@?\w+[\\-\\/\w]*$/', 'message' => 'Only word characters, dashes, slashes and @ are allowed.'],
            [['dataPath'], 'validatePath'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'fixtureClass' => 'Fixture Class Name',
            'fixtureNs' => 'Fixture Class Namespace',
            'dataFile' => 'Fixture Data File',
            'dataPath' => 'Fixture Data Path',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['class.php', 'data.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['fixtureNs', 'dataPath']);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the model class. You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'fixtureClass' => 'This is the name for fixture class, e.g., <code>PostFixture</code>.',
            'fixtureNs' => 'This is the namespace for fixture class file, e.g., <code>tests\fixtures</code>.',
            'dataFile' => 'This is the name for the generated fixture data file, e.g., <code>post.php</code>.',
            'dataPath' => 'This is the root path to keep the generated fixture data files. You may provide either a directory or a path alias, e.g., <code>@tests/fixtures/data</code>.',
        ]);
    }

    /**
     * сообщение об успешной генерации
     * @return string
     */
    public function successMessage() : string
    {
        $output = "<p>The fixture has been generated successfully.</p>";
        return $output;
    }

    /**
     * валидации пути на существование
     * @param string $attribute
     */
    public function validatePath(string $attribute)
    {
        $path = \Yii::getAlias($this->$attribute, false);
        if ($path === false || !is_dir($path)) {
            $this->addError($attribute, 'Path does not exist.');
        }
    }

    /**
     * название файла для заглушки данных
     * @return string
     */
    public function getDataFileName() : string
    {
        if (!empty($this->dataFile)) {
            return $this->dataFile;
        } else {
            return strtolower(pathinfo(str_replace('\\', '/', $this->modelClass), PATHINFO_BASENAME)) . '.php';
        }
    }

    /**
     * название класса фикстуры
     * @return string
     */
    public function getFixtureClassName() : string
    {
        if (!empty($this->fixtureClass)) {
            return $this->fixtureClass;
        } else {
            return pathinfo(str_replace('\\', '/', $this->modelClass), PATHINFO_BASENAME) . 'Fixture';
        }
    }

    /**
     * название исходной модели
     * @return string
     */
    public function getModelClassName() : string
    {
        return mb_substr($this->modelClass, strripos($this->modelClass, '\\') + 1);
    }

    /**
     * генерации заглушки данных
     * @return array
     */
    protected function getFixtureData() : array
    {
        /** @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $items = [];
        $item = [];
        foreach ($modelClass::getTableSchema()->columns as $column) {
            $item[$column->name] = $column->allowNull ? 'null' : '\'\'';
        }
        $items[] = $item;
        return $items;
    }
}