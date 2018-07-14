<?php
namespace ma3obblu\gii\generators\tests\fixture_data;

use yii\db\ActiveRecord;

/**
 * Class Post
 * @package ma3obblu\gii\generators\tests\fixture_data
 *
 * @property User $user
 */
class Post extends ActiveRecord
{
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
} 