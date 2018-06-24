<?php
namespace tests\runtime;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $ticker
 * @property string $name
 * @property int $type_id
 * @property int $exchange_id
 * @property string $google_link
 * @property int $sector_id
 */
class PostAR extends \ma3obblu\gii\generators\tests\entity\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticker', 'name'], 'required'],
            [['type_id', 'exchange_id', 'sector_id'], 'integer'],
            [['google_link'], 'string'],
            [['ticker'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'id'),
            'ticker' => \Yii::t('app', 'ticker'),
            'name' => \Yii::t('app', 'name'),
            'type_id' => \Yii::t('app', 'type_id'),
            'exchange_id' => \Yii::t('app', 'exchange_id'),
            'google_link' => \Yii::t('app', 'google_link'),
            'sector_id' => \Yii::t('app', 'sector_id'),
        ];
    }
}
