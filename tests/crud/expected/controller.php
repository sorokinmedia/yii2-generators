<?php
namespace tests\runtime\data;

use ma3obblu\gii\generators\tests\crud\Post;
use tests\runtime\data\PostSearch;
use tests\runtime\data\handlers\Post\PostHandler;
use tests\runtime\data\forms\PostForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class PostController
 * @package tests\runtime\data
 */
class PostController extends Controller
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
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр модели
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        $model = $this->findModel($id);
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
        $model = new Post();
        $model_form = new PostForm([], $model);
        if ($model_form->load(\Yii::$app->request->post())) {
            $model->form = $model_form;
            (new PostHandler($model))->create();
            $model->refresh();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
            'model_form' => $model_form,
        ]);
    }

    /**
     * Обновление модели
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $model_form = new PostForm([], $model);
        if ($model_form->load(\Yii::$app->request->post())) {
            $model->form = $model_form;
            (new PostHandler($model))->update();
            $model->refresh();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'model_form' => $model_form,
        ]);
    }

    /**
     * Удаление модели
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        (new PostHandler($model))->delete();
        return $this->redirect(['index']);
    }

    /**
     * Найти модель по первичному ключу
     * @param integer $id
     * @return Post
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Post
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
