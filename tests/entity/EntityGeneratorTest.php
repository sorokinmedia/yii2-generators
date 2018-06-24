<?php
namespace ma3obblu\gii\generators\tests\entity;

use ma3obblu\gii\generators\tests\TestCase;
use ma3obblu\gii\generators\entity\Generator as EntityGenerator;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;

/**
 * Class FormGeneratorTest
 * @package ma3obblu\gii\generators\tests\form
 *
 * тестирование генератора форм
 */
class EntityGeneratorTest extends TestCase
{
    /**
     * некорректный ввод
     */
    public function testValidateIncorrect()
    {
        $generator = new EntityGenerator();
        $generator->tableName = 'fake';
        $generator->modelClass = 'Fake';
        $generator->ns = 'tests\runtime\fake';
        $generator->baseClass = 'common\components\ActiveRecord';
        $generator->db = 'db';
        $generator->useTablePrefix = false;
        $generator->generateRelations = EntityGenerator::RELATIONS_ALL;
        $generator->generateRelationsFromCurrentSchema = true;
        $generator->generateLabelsFromComments = true;
        $generator->generateQuery = false;
        $generator->enableI18N = true;
        $generator->useSchemaName = true;

        $this->assertFalse($generator->validate());
        //$this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
        $this->assertEquals($generator->getFirstError('db'), 'There is no application component named "db".');
        $this->assertEquals($generator->getFirstError('queryNs'), 'Namespace must be associated with an existing directory.');
        $this->assertEquals($generator->getFirstError('tableName'), 'Table \'fake\' does not exist.');
        $this->assertEquals($generator->getFirstError('baseClass'), 'Class \'common\components\ActiveRecord\' does not exist or has syntax error.');
    }

    /**
     * корректный ввод
     */
    public function testValidateCorrect()
    {
        $this->initDb();
        $generator = new EntityGenerator();
        $generator->tableName = 'post';
        $generator->modelClass = 'Post';
        $generator->ns = '\tests\runtime';
        $generator->queryNs = '\tests\runtime';
        $generator->baseClass = 'ma3obblu\gii\generators\tests\entity\ActiveRecord';
        $generator->db = 'db';
        $generator->useTablePrefix = false;
        $generator->generateRelations = EntityGenerator::RELATIONS_ALL;
        $generator->generateRelationsFromCurrentSchema = true;
        $generator->generateLabelsFromComments = true;
        $generator->generateQuery = false;
        $generator->enableI18N = true;
        $generator->useSchemaName = true;

        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    /**
     * тест дефолтных наименований
     */
    public function testDefaultNames()
    {
        $this->initDb();
        $generator = new EntityGenerator();
        $generator->tableName = 'post';
        $generator->modelClass = 'Post';
        $generator->useTablePrefix = false;
        $generator->generateRelations = EntityGenerator::RELATIONS_ALL;
        $generator->generateRelationsFromCurrentSchema = true;
        $generator->generateLabelsFromComments = true;
        $generator->generateQuery = false;
        $generator->enableI18N = true;
        $generator->useSchemaName = true;

        $this->assertEquals('common\components', $generator->ns);
        $this->assertEquals('db', $generator->db);
        $this->assertEquals('common\components\ActiveRecord', $generator->baseClass);
    }

    /**
     * тест генерации файлов
     */
    public function testGenerateFile()
    {
        $this->initDb();

        $generator = new EntityGenerator();
        $generator->tableName = 'post';
        $generator->modelClass = 'Post';
        $generator->ns = 'tests\runtime';
        $generator->queryNs = 'tests\runtime';
        $generator->baseClass = 'ma3obblu\gii\generators\tests\entity\ActiveRecord';
        $generator->db = 'db';
        $generator->useTablePrefix = false;
        $generator->generateRelations = EntityGenerator::RELATIONS_ALL;
        $generator->generateRelationsFromCurrentSchema = true;
        $generator->generateLabelsFromComments = true;
        $generator->generateQuery = false;
        $generator->enableI18N = true;
        $generator->useSchemaName = true;

        /** @var CodeFile[] $files */
        $this->assertCount(2, $files = $generator->generate());
        $this->assertStringEqualsFile(__DIR__ . '/expected/model-ar.php', $files[0]->content);
        $this->assertStringEqualsFile(__DIR__ . '/expected/model.php', $files[1]->content);
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