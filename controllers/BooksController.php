<?php

namespace app\controllers;

use app\models\Books;
use app\models\BooksSearch;
use app\models\BookRating;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view'],
                            'roles' => ['?', '@'], // Allow both guests and authenticated users
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['author', 'admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['author', 'admin'],
                            'matchCallback' => function ($rule, $action) {
                                return $this->canUpdateBook();
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['rate'],
                            'roles' => ['@'], // Only authenticated users can rate
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'rate' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Books models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Check if user is guest and show limited content
        $isGuest = Yii::$app->user->isGuest;
        
        return $this->render('view', [
            'model' => $model,
            'isGuest' => $isGuest,
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Books();
        $model->author_id = Yii::$app->user->id; // Set current user as author
        $model->created_at = time();
        $model->updated_at = time();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Book created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_at = time();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Book updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->session->setFlash('success', 'Book deleted successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Rate a book.
     * @param int $id Book ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRate($id)
    {
        $model = $this->findModel($id);
        $rating = Yii::$app->request->post('rating');
        
        if ($rating && is_numeric($rating) && $rating >= 1 && $rating <= 5) {
            // Check if user has already rated this book
            $existingRating = BookRating::findOne([
                'book_id' => $id,
                'user_id' => Yii::$app->user->id
            ]);
            
            if ($existingRating) {
                $existingRating->rating = $rating;
                $existingRating->updated_at = time();
                $existingRating->save();
                Yii::$app->session->setFlash('success', 'Your rating has been updated.');
            } else {
                $bookRating = new BookRating();
                $bookRating->book_id = $id;
                $bookRating->user_id = Yii::$app->user->id;
                $bookRating->rating = $rating;
                $bookRating->created_at = time();
                $bookRating->updated_at = time();
                $bookRating->save();
                Yii::$app->session->setFlash('success', 'Thank you for rating this book!');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Invalid rating value.');
        }
        
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Check if current user can update the book
     * @return bool
     */
    protected function canUpdateBook()
    {
        $bookId = Yii::$app->request->get('id');
        if (!$bookId) {
            return false;
        }

        $book = $this->findModel($bookId);
        
        // Admin can update any book
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        
        // Author can only update their own books
        if (Yii::$app->user->can('author')) {
            return $book->author_id == Yii::$app->user->id;
        }
        
        return false;
    }
}
