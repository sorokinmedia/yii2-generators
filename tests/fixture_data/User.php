<?php
namespace sorokinmedia\gii\generators\tests\fixture_data;

use yii\db\ActiveRecord;

/**
 * Class User
 * @package sorokinmedia\gii\generators\tests\fixture_data
 *
 * @property Post $posts
 * @property Bill $bill
 */
class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBill()
    {
        return $this->hasOne(Bill::class, ['user_id' => 'id']);
    }
}