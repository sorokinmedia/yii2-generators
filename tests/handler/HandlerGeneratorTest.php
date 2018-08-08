<?php
namespace sorokinmedia\gii\generators\tests\handler;

use sorokinmedia\gii\generators\tests\TestCase;
use sorokinmedia\gii\generators\handler\Generator as HandlerGenerator;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;

/**
 * Class FormGeneratorTest
 * @package sorokinmedia\gii\generators\tests\form
 *
 * тестирование генератора форм
 */
class HandlerGeneratorTest extends TestCase
{
    /**
     * некорректный ввод
     */
    public function testValidateIncorrect()
    {
        $generator = new HandlerGenerator();
        $generator->modelClass = 'tests\Fake';
        $generator->handlerClass = 'FakeHandler';
        $generator->componentUrl = '@tests/runtime/fake';
        $generator->needCreate = true;
        $generator->needUpdate = true;
        $generator->needDelete = true;

        $this->assertFalse($generator->validate());
        //$this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
        $this->assertEquals($generator->getFirstError('modelClass'), 'Class \'tests\\Fake\' does not exist or has syntax error.');
        $this->assertEquals($generator->getFirstError('componentUrl'), 'Path does not exist.');
    }

    /**
     * корректный ввод
     */
    public function testValidateCorrect()
    {
        $generator = new HandlerGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\handler\Post';
        $generator->handlerClass = 'PostHandler';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->needCreate = true;
        $generator->needUpdate = true;
        $generator->needDelete = true;

        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * тест генерации файла
     */
    public function testGenerateFile()
    {
        $this->initDb();

        $generator = new HandlerGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\handler\Post';
        $generator->handlerClass = 'PostHandler';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->needCreate = true;
        $generator->needUpdate = true;
        $generator->needDelete = true;

        /** @var CodeFile[] $files */
        $this->assertCount(9, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/handler.php', $files[0]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-executable.php', $files[1]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/interface-create.php', $files[2]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/interface-update.php', $files[3]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/interface-delete.php', $files[4]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/abstract-action.php', $files[5]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-create.php', $files[6]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-update.php', $files[7]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-delete.php', $files[8]->content);
    }

    /**
     * тест генерации файла без create/delete interfaces/actions
     */
    public function testGenerateFileWithoutCreateDelete()
    {
        $this->initDb();

        $generator = new HandlerGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\handler\Post';
        $generator->handlerClass = 'PostHandler';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->needCreate = false;
        $generator->needUpdate = true;
        $generator->needDelete = false;

        /** @var CodeFile[] $files */
        $this->assertCount(5, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/handler-without.php', $files[0]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-executable.php', $files[1]->content);
        //$this->assertStringEqualsFile(__DIR__ . '/expected/interface-create.php', $files[2]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/interface-update.php', $files[2]->content);
        //$this->assertStringEqualsFile(__DIR__ . '/expected/interface-delete.php', $files[4]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/abstract-action.php', $files[3]->content);
        //$this->assertStringEqualsFile(__DIR__ . '/expected/action-create.php', $files[6]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/action-update.php', $files[4]->content);
        //$this->assertStringEqualsFile(__DIR__ . '/expected/action-delete.php', $files[8]->content);
    }

    /**
     * подготовка БД
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
        $db->createCommand()->createTable('post', [
            'id' => Schema::TYPE_PK,
            'ticker' => Schema::TYPE_STRING . '(10) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'type_id' => Schema::TYPE_INTEGER,
            'exchange_id' => Schema::TYPE_INTEGER,
            'google_link' => Schema::TYPE_TEXT,
            'sector_id' => Schema::TYPE_INTEGER
        ])->execute();
    }
}