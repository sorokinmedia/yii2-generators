<?php
namespace ma3obblu\gii\generators\tests\crud;

use ma3obblu\gii\generators\tests\TestCase;
use ma3obblu\gii\generators\crud\Generator as CrudGenerator;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;

/**
 * Class CrudGeneratorTest
 * @package ma3obblu\gii\generators\tests\crud
 *
 * тестирование генератора CRUD
 */
class EntityGeneratorTest extends TestCase
{
    /**
     * некорректный ввод
     */
    public function testValidateIncorrect()
    {
        $generator = new CrudGenerator();
        $generator->modelClass = 'test\runtime\Fake';
        $generator->searchModelClass = 'test\runtime\FakeSearch';
        $generator->controllerClass = 'test\runtime\contollers\FakeController';
        $generator->viewPath = '@tests/runtime/views/fake';
        $generator->enableI18N = true;

        $this->assertFalse($generator->validate());
        //$this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
        $this->assertEquals($generator->getFirstError('modelClass'), 'Class \'test\runtime\Fake\' does not exist or has syntax error.');
        $this->assertEquals($generator->getFirstError('controllerClass'), 'The class namespace is invalid: test\runtime\contollers');
        $this->assertEquals($generator->getFirstError('searchModelClass'), 'The class namespace is invalid: test\runtime');
    }

    /**
     * корректный ввод
     */
    public function testValidateCorrect()
    {
        $this->initDb();
        $generator = new CrudGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\crud\Post';
        $generator->searchModelClass = 'tests\runtime\FakeSearch';
        $generator->controllerClass = 'tests\runtime\data\FakeController';
        $generator->viewPath = '@tests/runtime/views/fake';
        $generator->enableI18N = true;

        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * тест дефолтных наименований
     */
    public function testDefaultNames()
    {
        $this->initDb();
        $generator = new CrudGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\crud\Post';
        $generator->searchModelClass = 'tests\runtime\FakeSearch';
        $generator->controllerClass = 'tests\runtime\data\FakeController';
        $generator->viewPath = '@tests/runtime/views/fake';
        $generator->enableI18N = true;

        $this->assertEquals('yii\web\Controller', $generator->baseControllerClass);
        $this->assertEquals('grid', $generator->indexWidgetType);
    }

    /**
     * тест генерации файлов
     */
    public function testGenerateFile()
    {
        $this->initDb();

        $generator = new CrudGenerator();
        $generator->modelClass = 'ma3obblu\gii\generators\tests\crud\Post';
        $generator->searchModelClass = 'tests\runtime\data\PostSearch';
        $generator->componentNs = 'tests\runtime\data';
        $generator->controllerClass = 'tests\runtime\data\PostController';
        $generator->viewPath = '@tests/runtime/date/views/fake';
        $generator->enableI18N = true;

        /** @var CodeFile[] $files */
        $this->assertCount(7, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/controller.php', $files[0]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/search.php', $files[1]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/_form.php', $files[2]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/create.php', $files[3]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/index.php', $files[4]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/update.php', $files[5]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/view.php', $files[6]->content);
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