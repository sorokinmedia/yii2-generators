<?php
namespace ma3obblu\gii\generators\tests\fixture_data;

use yii\db\ActiveRecord;

/**
 * Class Bill
 * @package ma3obblu\gii\generators\tests\fixture_data
 *
 * @property User $user
 */
class Bill extends ActiveRecord
{
    public static function tableName()
    {
        return 'bill';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}