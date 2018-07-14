<?php
namespace ma3obblu\gii\generators\tests\fixture_data;

use ma3obblu\gii\generators\fixture_data\Generator as FixtureExtraGenerator;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;
use Ma3oBblu\gii\generators\tests\TestCase;

/**
 * Class FixtureDataGeneratorTest
 * @package ma3obblu\gii\generators\tests\fixture_data
 */
class FixtureDataGeneratorTest extends TestCase
{
    /**
     * тест ошибок валидации
     */
    public function testValidateIncorrect()
    {
        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'tests\Fake';
        $generator->dataPath = '@tests/runtime/fake';

        $this->assertFalse($generator->validate());
        $this->assertEquals($generator->getFirstError('pkFirstValue'), 'PK first field value cannot be blank.');
        $this->assertEquals($generator->getFirstError('modelClass'), 'Class \'tests\\Fake\' does not exist or has syntax error.');
        $this->assertEquals($generator->getFirstError('dataPath'), 'Path does not exist.');
        //$this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * тест успешной валидации
     */
    public function testValidateCorrect()
    {
        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\fixture_data\Post';
        $generator->dataPath = '@tests/runtime/data';
        $generator->pkFirstName = 'id';
        $generator->pkFirstValue = 1;


        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * дефолтные названия
     */
    public function testDefaultNames()
    {
        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\fixture_data\Post';
        $generator->dataPath = '@tests/runtime/data';

        $this->assertEquals('post.php', $generator->getDataFileName());
    }

    /**
     * кастомные названия
     */
    public function testSpecificNames()
    {
        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\fixture_data\Post';
        $generator->dataFile = 'post-custom.php';
        $generator->dataPath = '@tests/runtime/data';

        $this->assertEquals('post-custom.php', $generator->getDataFileName());
    }

    /**
     * тест генерации файлов со стандартным набором данных
     * @throws \yii\db\Exception
     */
    public function testGenerateWithData()
    {
        $this->initDb();

        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\fixture_data\Post';
        $generator->dataPath = '@tests/runtime/data';
        $generator->pkFirstName = 'id';
        $generator->pkFirstValue = 1;
        $generator->relations = "user\r\nuser->bill";

        /** @var CodeFile[] $files */
        $this->assertCount(3, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-post.php', $files[0]->content);
        $this->assertEquals('runtime/data/post.php', $files[0]->relativePath);
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-user.php', $files[1]->content);
        $this->assertEquals('runtime/data/user.php', $files[1]->relativePath);
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-bill.php', $files[2]->content);
        $this->assertEquals('runtime/data/bill.php', $files[2]->relativePath);
    }

    /**
     * @throws \yii\db\Exception
     */
    public function testGenerateWithDataMultiply()
    {
        $this->initDb();

        $generator = new FixtureExtraGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\fixture_data\User';
        $generator->dataPath = '@tests/runtime/data';
        $generator->pkFirstName = 'id';
        $generator->pkFirstValue = 1;
        $generator->relations = "posts\r\nbill";

        /** @var CodeFile[] $files */
        $this->assertCount(3, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-user-multiply.php', $files[0]->content);
        $this->assertEquals('runtime/data/user.php', $files[0]->relativePath);
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-post-multiply.php', $files[1]->content);
        $this->assertEquals('runtime/data/posts.php', $files[1]->relativePath);
        $this->assertStringEqualsFile(__DIR__ . '/expected/data-bill.php', $files[2]->content);
        $this->assertEquals('runtime/data/bill.php', $files[2]->relativePath);
    }

    /**
     *
     */
    private function initDb()
    {
        @unlink(__DIR__ . '/runtime/sqlite.db');
        $db = new Connection([
            'dsn' => 'sqlite:' . \Yii::$app->getRuntimePath() . '/sqlite.db',
            'charset' => 'utf8',
        ]);
        \Yii::$app->set('db', $db);
        if ($db->getTableSchema('post')){
            $db->createCommand()->dropTable('post')->execute();
        }
        if ($db->getTableSchema('user')){
            $db->createCommand()->dropTable('user')->execute();
        }
        if ($db->getTableSchema('bill')){
            $db->createCommand()->dropTable('bill')->execute();
        }
        $db->createCommand()->createTable('post', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'content' => Schema::TYPE_TEXT,
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
        ])->execute();
        $db->createCommand()->createTable('user', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ])->execute();
        $db->createCommand()->createTable('bill', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'sum' => Schema::TYPE_MONEY,
        ])->execute();
        $db->createCommand()->insert('post', [
            'id' => 1,
            'title' => 'First Title',
            'content' => null,
            'status' => 0,
            'created_at' => 1459672035,
            'user_id' => 1
        ])->execute();
        $db->createCommand()->insert('post', [
            'id' => 2,
            'title' => 'Second Title',
            'content' => 'Second Content',
            'status' => 1,
            'created_at' => 1459672036,
            'user_id' => 1
        ])->execute();
        $db->createCommand()->insert('user', [
            'id' => 1,
            'name' => 'Ma3oBblu1'
        ])->execute();
        $db->createCommand()->insert('user', [
            'id' => 2,
            'name' => 'Ma3oBblu2'
        ])->execute();
        $db->createCommand()->insert('bill', [
            'id' => 1,
            'user_id' => 1,
            'sum' => 500
        ])->execute();
        $db->createCommand()->insert('bill', [
            'id' => 2,
            'user_id' => 2,
            'sum' => 1000
        ])->execute();
    }
}
