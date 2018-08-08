<?php
namespace sorokinmedia\gii\generators\tests\fixture_class;

use yii\db\ActiveRecord;

/**
 * Class Post
 * @package sorokinmedia\gii\generators\tests\fixture
 */
class Post extends ActiveRecord
{
    public static function tableName()
    {
        return 'post';
    }
} 