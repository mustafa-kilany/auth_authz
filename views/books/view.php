<?php

/** @var yii\web\View $this */
/** @var app\models\Books $model */
/** @var bool $isGuest */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="books-view">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
            
            <?php if ($isGuest): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>👀 Guest Preview:</strong> You're viewing this book as a guest. 
                    <a href="<?= \yii\helpers\Url::to(['site/signup']) ?>" class="alert-link">Sign up</a> or 
                    <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="alert-link">login</a> 
                    to read the full content and rate this book!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if ($model->cover_image): ?>
                                <img src="<?= Html::encode($model->cover_image) ?>" class="img-fluid rounded" alt="Book Cover">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">📚 No Cover Image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
                            <p class="card-text text-muted">
                                <strong>Author:</strong> 
                                <?= Html::encode($model->author ? $model->author->first_name . ' ' . $model->author->last_name : 'Unknown') ?>
                            </p>
                            
                            <?php if ($model->genre): ?>
                                <p class="card-text">
                                    <span class="badge bg-primary"><?= Html::encode($model->genre) ?></span>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($model->publication_year): ?>
                                <p class="card-text">
                                    <small class="text-muted">Published: <?= Html::encode($model->publication_year) ?></small>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($model->isbn): ?>
                                <p class="card-text">
                                    <small class="text-muted">ISBN: <?= Html::encode($model->isbn) ?></small>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Rating Display -->
                            <div class="mb-3">
                                <?php
                                $avgRating = $model->getAverageRating();
                                $ratingCount = $model->getRatingCount();
                                ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $avgRating): ?>
                                                <span class="text-warning">⭐</span>
                                            <?php else: ?>
                                                <span class="text-muted">☆</span>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-muted">(<?= $avgRating ?> / 5) - <?= $ratingCount ?> ratings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📖 Description</h5>
                </div>
                <div class="card-body">
                    <?php if ($model->description): ?>
                        <p class="card-text"><?= nl2br(Html::encode($model->description)) ?></p>
                    <?php else: ?>
                        <p class="text-muted">No description available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$isGuest): ?>
                <!-- Rating Form for Authenticated Users -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">⭐ Rate this Book</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $userRating = $model->getUserRating(Yii::$app->user->id);
                        ?>
                        <form method="post" action="<?= \yii\helpers\Url::to(['rate', 'id' => $model->id]) ?>">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                            <div class="mb-3">
                                <label class="form-label">Your Rating:</label>
                                <div class="rating-stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" 
                                               <?= ($userRating && $userRating->rating == $i) ? 'checked' : '' ?>>
                                        <label for="star<?= $i ?>">☆</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <?= $userRating ? 'Update Rating' : 'Submit Rating' ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Ratings -->
            <?php if ($ratingCount > 0): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">💬 Recent Ratings</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $recentRatings = $model->getRatings()->with('user')->orderBy(['created_at' => SORT_DESC])->limit(5)->all();
                        ?>
                        <?php foreach ($recentRatings as $rating): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= Html::encode($rating->user->username) ?></strong>
                                        <div class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?= ($i <= $rating->rating) ? '⭐' : '☆' ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('M j, Y', $rating->created_at) ?>
                                    </small>
                                </div>
                                <?php if ($rating->review): ?>
                                    <p class="mt-1 mb-0"><?= Html::encode($rating->review) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Book Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= $model->status == 1 ? 'success' : 'warning' ?>">
                            <?= $model->status == 1 ? 'Published' : 'Draft' ?>
                        </span>
                    </p>
                    <p><strong>Added:</strong> <?= date('M j, Y', $model->created_at) ?></p>
                    <p><strong>Last Updated:</strong> <?= date('M j, Y', $model->updated_at) ?></p>
                    
                    <?php if (!$isGuest && (Yii::$app->user->can('admin') || (Yii::$app->user->can('author') && $model->author_id == Yii::$app->user->id))): ?>
                        <hr>
                        <div class="d-grid gap-2">
                            <?= Html::a('✏️ Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
                            <?php if (Yii::$app->user->can('admin')): ?>
                                <?= Html::a('🗑️ Delete', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-outline-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this book?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: inline-block;
    direction: rtl;
}

.rating-stars input {
    display: none;
}

.rating-stars label {
    font-size: 24px;
    color: #ddd;
    cursor: pointer;
    margin: 0 2px;
}

.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}

.rating-stars input:checked ~ label {
    color: #ffc107;
}
</style>
