<?php
namespace tests\runtime\data\forms;

use yii\base\Model;
use ma3obblu\gii\generators\tests\form\Post;

/**
 * Class PostForm
 * @package tests\runtime\data\forms
 *
 * @property string $ticker
 * @property string $name
 * @property integer $type_id
 * @property integer $exchange_id
 * @property string $google_link
 * @property integer $sector_id
 */
class PostForm extends Model
{
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
            [['ticker', 'name', 'type_id', 'exchange_id'], 'required'],
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
            $this->ticker = $post->ticker;
            $this->name = $post->name;
            $this->type_id = $post->type_id;
            $this->exchange_id = $post->exchange_id;
            $this->google_link = $post->google_link;
            $this->sector_id = $post->sector_id;
        }
        parent::__construct($config);
    }
}