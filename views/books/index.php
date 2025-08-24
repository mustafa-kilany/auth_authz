<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\BooksSearch $searchModel */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = '📚 Books Library';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="books-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->can('author') || Yii::$app->user->can('admin'))): ?>
            <?= Html::a('✍️ Add New Book', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>👋 Welcome!</strong> Browse our collection of books. 
            <a href="<?= \yii\helpers\Url::to(['site/signup']) ?>" class="alert-link">Sign up</a> or 
            <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="alert-link">login</a> 
            to rate books and access full features!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🔍 Search & Filter</h5>
                </div>
                <div class="card-body">
                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="row">
                <?php foreach ($dataProvider->getModels() as $book): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        <?= Html::a(Html::encode($book->title), ['view', 'id' => $book->id], [
                                            'class' => 'text-decoration-none'
                                        ]) ?>
                                    </h5>
                                    <?php if ($book->status == 0): ?>
                                        <span class="badge bg-warning">Draft</span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="card-text text-muted small">
                                    <strong>By:</strong> <?= Html::encode($book->author ? $book->author->first_name . ' ' . $book->author->last_name : 'Unknown') ?>
                                </p>
                                
                                <?php if ($book->genre): ?>
                                    <p class="card-text">
                                        <span class="badge bg-primary"><?= Html::encode($book->genre) ?></span>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($book->description): ?>
                                    <p class="card-text">
                                        <?= Html::encode(substr($book->description, 0, 100)) ?>
                                        <?= strlen($book->description) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Rating Display -->
                                <?php
                                $avgRating = $book->getAverageRating();
                                $ratingCount = $book->getRatingCount();
                                ?>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $avgRating): ?>
                                                    <span class="text-warning" style="font-size: 14px;">⭐</span>
                                                <?php else: ?>
                                                    <span class="text-muted" style="font-size: 14px;">☆</span>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">(<?= $avgRating ?> / 5)</small>
                                    </div>
                                    <small class="text-muted"><?= $ratingCount ?> ratings</small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Added: <?= date('M j, Y', $book->created_at) ?>
                                    </small>
                                    <?= Html::a('📖 Read More', ['view', 'id' => $book->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($dataProvider->getCount() == 0): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <h4>📚 No books found</h4>
                        <p>Try adjusting your search criteria or check back later for new additions.</p>
                        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->can('author') || Yii::$app->user->can('admin'))): ?>
                            <?= Html::a('✍️ Add the first book', ['create'], ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php Pjax::end(); ?>
</div>
