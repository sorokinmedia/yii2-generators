<?php
namespace ma3obblu\gii\generators\tests\handler;

use yii\db\ActiveRecord;

/**
 * Class Post
 * @package ma3obblu\gii\generators\tests\handler
 *
 * @property int $id
 * @property string $ticker
 * @property string $name
 * @property int $type_id
 * @property int $exchange_id
 * @property string $google_link
 * @property int $sector_id
 *
 * @property string $type
 */
class Post extends ActiveRecord
{
    const TYPE_STOCK = 1;
    const TYPE_BOND = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ticker', 'name', 'type_id', 'exchange_id'], 'required'],
            [['ticker'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 255],
            [['type_id', 'exchange_id', 'sector_id'], 'integer'],
            [['google_link'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'ticker' => \Yii::t('app', 'Тикер'),
            'name' => \Yii::t('app', 'Название'),
            'type_id' => \Yii::t('app', 'Тип'),
            'exchange_id' => \Yii::t('app', 'Рынок'),
            'google_link' => \Yii::t('app', 'Ссылка на гугл'),
            'sector_id' => \Yii::t('app', 'Сектор рынка')
        ];
    }

    /**
     * получение русского названия типа
     * @param int|null $type_id
     * @return array|mixed
     */
    public static function getTypes(int $type_id = null)
    {
        $types = [
            self::TYPE_STOCK => \Yii::t('app', 'Акция'),
            self::TYPE_BOND => \Yii::t('app', 'Облигация'),
        ];
        if (!is_null($type_id)){
            return $types[$type_id];
        }
        return $types;
    }

    /**
     * получить тип текущей модели
     * @return string
     */
    public function getType() : string
    {
        return self::getTypes($this->type_id);
    }
}