<?php
namespace ma3obblu\gii\generators\tests\fixture;

use yii\db\ActiveRecord;

/**
 * Class Post
 * @package ma3obblu\gii\generators\tests\fixture
 */
class Post extends ActiveRecord
{
    public static function tableName()
    {
        return 'post';
    }
} 