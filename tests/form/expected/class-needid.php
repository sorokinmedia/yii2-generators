<?php
namespace tests\runtime\data\forms;

use yii\base\Model;
use ma3obblu\gii\generators\tests\form\Post;

/**
 * Class PostForm
 * @package tests\runtime\data\forms
 *
 * @property //TODO $id
 * @property string $ticker
 * @property string $name
 * @property integer $type_id
 * @property integer $exchange_id
 * @property string $google_link
 * @property integer $sector_id
 */
class PostForm extends Model
{
    public $id;
    public $ticker;
    public $name;
    public $type_id;
    public $exchange_id;
    public $google_link;
    public $sector_id;

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
            'sector_id' => \Yii::t('app', 'Сектор рынка'),
        ];
    }

    /**
     * PostForm constructor.
     * @param array $config
     * @param Post|null $post
     */
    public function __construct(array $config = [], Post $post = null)
    {
        if (!is_null($post)){
            $this->setAttributes($post->getAttributes());
        }
        parent::__construct($config);
    }
}