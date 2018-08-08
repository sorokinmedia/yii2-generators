<?php
namespace sorokinmedia\gii\generators\tests\form;

use sorokinmedia\gii\generators\tests\TestCase;
use sorokinmedia\gii\generators\form\Generator as FormGenerator;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;

/**
 * Class FormGeneratorTest
 * @package sorokinmedia\gii\generators\tests\form
 *
 * тестирование генератора форм
 */
class FormGeneratorTest extends TestCase
{
    /**
     * некорректный ввод
     */
    public function testValidateIncorrect()
    {
        $generator = new FormGenerator();
        $generator->modelClass = 'tests\Fake';
        $generator->componentUrl = '@tests/runtime/fake';
        $generator->formClass = 'FakeForm';
        $generator->formUrl = 'forms';

        $this->assertFalse($generator->validate());
        $this->assertEquals($generator->getFirstError('componentUrl'), 'Path does not exist.');
        $this->assertEquals($generator->getFirstError('modelClass'), 'Class \'tests\\Fake\' does not exist or has syntax error.');
    }

    /**
     * корректный ввод
     */
    public function testValidateCorrect()
    {
        $generator = new FormGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->formClass = 'PostForm';
        $generator->formUrl = 'forms';

        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * тест дефолтных наименований
     */
    public function testDefaultNames()
    {
        $generator = new FormGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->formClass = 'PostForm';
        $generator->formUrl = 'forms';

        $this->assertEquals('PostForm', $generator->getFormClassName());
        $this->assertEquals('/Users/sorokinmedia/sorokin/yii2-generators/tests/runtime/data/forms/PostForm.php', $generator->getFilePath());
    }

    /**
     * тест генерации файла
     */
    public function testGenerateFile()
    {
        $this->initDb();

        $generator = new FormGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->formClass = 'PostForm';
        $generator->formUrl = 'forms';
        $generator->getAttributes = true;

        /** @var CodeFile[] $files */
        $this->assertCount(1, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/class.php', $files[0]->content);
    }

    /**
     * тест генерации файла
     */
    public function testGenerateFileNeedId()
    {
        $this->initDb();

        $generator = new FormGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->formClass = 'PostForm';
        $generator->formUrl = 'forms';
        $generator->needId = true;
        $generator->getAttributes = true;

        /** @var CodeFile[] $files */
        $this->assertCount(1, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/class-needid.php', $files[0]->content);
    }

    /**
     * тест генерации файла
     */
    public function testGenerateFileNoAttributes()
    {
        $this->initDb();

        $generator = new FormGenerator();
        $generator->modelClass = 'sorokinmedia\gii\generators\tests\form\Post';
        $generator->componentUrl = '@tests/runtime/data';
        $generator->formClass = 'PostForm';
        $generator->formUrl = 'forms';
        $generator->needId = false;
        $generator->getAttributes = false;

        /** @var CodeFile[] $files */
        $this->assertCount(1, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/class-noattributes.php', $files[0]->content);
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