<?php
use yii\web\View;
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;
use sorokinmedia\gii\generators\crud\Generator;

/* @var $this View */
/* @var $generator Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamsType = $generator->generateActionParamsWithType();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>
namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= $generator->componentNs . '\\handlers\\' . $modelClass . "\\" . $modelClass . "Handler;\n"; ?>
use <?= $generator->componentNs . '\\forms\\' . $modelClass . "Form;\n"; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class <?= $controllerClass . "\n"; ?>
 * @package <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) . "\n" ?>
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список всех моделей
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Просмотр модели
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(<?= $actionParamsType ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Саздание модели
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $model_form = new <?= $modelClass . 'Form'?>([], $model);
        if ($model_form->load(\Yii::$app->request->post())) {
            $model->form = $model_form;
            (new <?= $modelClass . 'Handler'?>($model))->create();
            $model->refresh();
            return $this->redirect(['view', <?= $urlParams ?>]);
        }
        return $this->render('create', [
            'model' => $model,
            'model_form' => $model_form,
        ]);
    }

    /**
     * Обновление модели
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(<?= $actionParamsType ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $model_form = new <?= $modelClass . 'Form'?>([], $model);
        if ($model_form->load(\Yii::$app->request->post())) {
            $model->form = $model_form;
            (new <?= $modelClass . 'Handler'?>($model))->update();
            $model->refresh();
            return $this->redirect(['view', <?= $urlParams ?>]);
        }
        return $this->render('update', [
            'model' => $model,
            'model_form' => $model_form,
        ]);
    }

    /**
     * Удаление модели
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(<?= $actionParamsType ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        (new <?= $modelClass . 'Handler'?>($model))->delete();
        return $this->redirect(['index']);
    }

    /**
     * Найти модель по первичному ключу
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass . "\n" ?>
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParamsType ?>) : <?= $modelClass . "\n" ?>
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(<?= $generator->generateString('The requested page does not exist.') ?>);
    }
}
