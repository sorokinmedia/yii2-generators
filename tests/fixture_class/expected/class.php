<?php
namespace tests\runtime;

use yii\test\ActiveFixture;
use ma3obblu\gii\generators\tests\fixture_class\Post;

/**
 * Class PostFixture
 * @package tests\runtime
 *
 * @param string $modelClass
 * @param string $dataFile
 */
class PostFixture extends ActiveFixture
{
    public $modelClass = Post::class;
    public $dataFile = '@tests/runtime/data/post.php';
}