<?php
namespace ma3obblu\gii\generators\fixture_data;

use ma3obblu\gii\generators\helpers\GeneratorHelper;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\gii\CodeFile;

/**
 * Class Generator
 * @package ma3obblu\gii\generators\fixture_data
 *
 * @property string $modelClass
 * @property string $pkFirstName
 * @property string $pkFirstValue
 * @property string $pkSecondName
 * @property string $pkSecondValue
 * @property string $relations
 * @property string $dataFile
 * @property string $dataPath
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass;
    public $pkFirstName = 'id';
    public $pkFirstValue;
    public $pkSecondName;
    public $pkSecondValue;
    public $relations;
    public $dataFile;
    public $dataPath = '@tests/fixtures/data';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Fixture Data Grabber';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates fixture data from real DB. it can work with main model, its relations and prepares fixture data files.';
    }

    /**
     * генерация файлов для основной модели и связей с наборами данными
     * @return array|CodeFile[]
     * @throws Exception
     */
    public function generate()
    {
        $files = [];
        // генерация данных для основной модели
        $files[] = new CodeFile(
            \Yii::getAlias($this->dataPath) . '/' . $this->getDataFileName(),
            $this->render('data.php', ['items' => $this->getMainFixtureData()])
        );
        // генерация данных для всех указанных связей
        $data = $this->getRelationsFixtureData();
        if (!empty($data)){
            foreach ($data as $filename => $array){
                //var_dump($array); exit();
                $files[] = new CodeFile(
                    \Yii::getAlias($this->dataPath) . '/' . $filename . '.php',
                    $this->render('data.php', ['items' => $array])
                );
            }
        }

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'dataPath', 'pkFirstName', 'pkSecondName', 'pkFirstValue', 'pkSecondValue'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'dataPath', 'pkFirstName', 'pkFirstValue'], 'required'],
            [['pkFirstName', 'pkSecondName'], 'match', 'pattern' => '/^[\w\\_]*$/', 'message' => 'Only word characters and underscores are allowed.'],
            [['modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
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
            'pkFirstName' => 'PK first field name',
            'pkFirstValue' => 'PK first field value',
            'pkSecondName' => 'PK second field name(optional)',
            'pkSecondValue' => 'PK second field value(optional)',
            'relations' => 'Relations you need for main Model',
            'dataFile' => 'Fixture Data File',
            'dataPath' => 'Fixture Data Path',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['data.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['dataPath', 'pkFirstName']);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the model class. You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'pkFirstName' => 'Primary key first field name, e.g. "id"',
            'pkFirstValue' => 'Primary key first field value, e.g. "12"',
            'pkSecondName' => 'Primary key second field name, e.g. "user_id"',
            'pkSecondValue' => 'Primary key second field value, e.g. "12"',
            'relations' => 'Relations you need for main Model. Each relation from new line. E.g., "user", "user->bill"',
            'dataFile' => 'This is the name for the generated fixture data file, e.g., <code>post.php</code>.',
            'dataPath' => 'This is the root path to keep the generated fixture data files. You may provide either a directory or a path alias, e.g., <code>@tests/fixtures/data</code>.',
        ]);
    }

    /**
     * генерация сообщения об успешной генерации
     * @return string
     */
    public function successMessage()
    {
        $output = <<<EOD
<p>To access the data, you need to add this to your test class:</p>
EOD;
        $files = $this->getRelationsDataFileNames();
        $code = '';
        foreach ($files as $file){
            $code .= <<<EOD
<?php

public function fixtures()
{
    return [
        '{$file['id']}' => [
            'class' => //TODO: add fixture class,
            'dataFile' => '{$file['link']}',
        ],
    ];
}
EOD;
        }
        return $output . '<pre>' . highlight_string($code, true) . '</pre>';
    }

    /**
     * проверка на существование пути
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
     * генерит название файла с данными для основной модели
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
     * генерит названия файлов с данными для связей
     * @return array
     */
    public function getRelationsDataFileNames() : array
    {
        /** @var ActiveRecord $modelClass */
        $relations = explode("\r\n", trim($this->relations));
        $items = [];
        if (empty($relations)){
            return $items;
        }
        foreach ($relations as $relation) {
            $name = GeneratorHelper::generateFixtureRelationName($relation);
            $items[] = [
                'id' => $name,
                'link' => \Yii::getAlias($this->dataPath) . '/' . $name . '.php',
            ];
        }
        return $items;
    }

    /**
     * название модели без namespace
     * @return string
     */
    public function getModelClassName() : string
    {
        return mb_substr($this->modelClass, strripos($this->modelClass, '\\') + 1);
    }

    /**
     * данные для основной модели
     * @return array
     * @throws Exception
     */
    protected function getMainFixtureData() : array
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $items = [];
        if ($this->pkSecondName != '' && $this->pkSecondValue != ''){
            $item = $modelClass::find()->where([$this->pkFirstName => $this->pkFirstValue, $this->pkSecondName => $this->pkSecondValue])->asArray()->one();
        } else {
            $item = $modelClass::find()->where([$this->pkFirstName => $this->pkFirstValue])->asArray()->one();
        }
        if (is_null($item)){
            throw new Exception('Модель не найдена в БД');
        }
        $items[] = GeneratorHelper::array2string($item);
        return $items;
    }

    /**
     * данные для связей основной модели
     * @return array
     */
    protected function getRelationsFixtureData() : array
    {
        $items = [];
        if ($this->relations == ''){
            return $items;
        }
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $item = $modelClass::find()->where([$this->pkFirstName => $this->pkFirstValue])->one();
        $relations = explode("\r\n", trim($this->relations));
        if (empty($relations)){
            return $items;
        }
        foreach ($relations as $relation){
            $relation_array = explode('->', $relation);
            /** @var ActiveRecord|ActiveRecord[]|null $model_relations */
            if (count($relation_array) == 1){
                $model_relations = $item->{$relation_array[0]};
            } elseif (count($relation_array) == 2){
                $model_relations = $item->{$relation_array[0]}->{$relation_array[1]};
            } elseif (count($relation_array) == 3){
                $model_relations = $item->{$relation_array[0]}->{$relation_array[1]}->{$relation_array[2]};
            } elseif (count($relation_array) == 4){
                $model_relations = $item->{$relation_array[0]}->{$relation_array[1]}->{$relation_array[2]}->{$relation_array[3]};
            }
            $name = GeneratorHelper::generateFixtureRelationName($relation);
            if (!is_null($model_relations)){
                if (is_array($model_relations)){
                    foreach ($model_relations as $model_relation){
                        $items[$name][] = GeneratorHelper::array2string($model_relation->getAttributes());
                    }
                } else {
                    $items[$name][] = GeneratorHelper::array2string($model_relations->getAttributes());
                }
            }
        }
        return $items;
    }
}
