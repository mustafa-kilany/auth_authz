<?php

/** @var yii\web\View $this */

$this->title = 'Book Club - Home';
?>
<div class="site-index">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">📚 Welcome to Book Club!</h1>
            <p class="lead mb-4">Discover, read, and share amazing books with our community. Join thousands of readers and authors in the ultimate literary experience.</p>
            
            <?php if (Yii::$app->user->isGuest): ?>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a class="btn btn-light btn-lg px-4" href="<?= \yii\helpers\Url::to(['site/login']) ?>">
                        🔐 Login
                    </a>
                    <a class="btn btn-outline-light btn-lg px-4" href="<?= \yii\helpers\Url::to(['site/send-verification']) ?>">
                        📝 Sign Up
                    </a>
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a class="btn btn-light btn-lg px-4" href="<?= \yii\helpers\Url::to(['books/index']) ?>">
                        📖 Browse Books
                    </a>
                    <?php if (Yii::$app->user->can('author') || Yii::$app->user->can('admin')): ?>
                        <a class="btn btn-outline-light btn-lg px-4" href="<?= \yii\helpers\Url::to(['books/create']) ?>">
                            ✍️ Add Your Book
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <span style="font-size: 3rem;">👥</span>
                    </div>
                    <h3 class="card-title h4">For Readers</h3>
                    <p class="card-text text-muted">
                        Explore our vast collection of books from various genres and authors. Read reviews, discover new titles, and expand your literary horizons with our curated selection.
                    </p>
                    <a href="<?= \yii\helpers\Url::to(['books/index']) ?>" class="btn btn-outline-primary">
                        📚 Browse Books
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <span style="font-size: 3rem;">✍️</span>
                    </div>
                    <h3 class="card-title h4">For Authors</h3>
                    <p class="card-text text-muted">
                        Share your stories with the world! Upload your books, manage your publications, and connect with readers who appreciate your work. Build your author platform.
                    </p>
                    <?php if (Yii::$app->user->can('author') || Yii::$app->user->can('admin')): ?>
                        <a href="<?= \yii\helpers\Url::to(['books/create']) ?>" class="btn btn-outline-success">
                            ✍️ Add Your Book
                        </a>
                    <?php else: ?>
                        <a href="<?= \yii\helpers\Url::to(['site/contact']) ?>" class="btn btn-outline-success">
                            📧 Contact Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mb-3">
                        <span style="font-size: 3rem;">⚙️</span>
                    </div>
                    <h3 class="card-title h4">For Admins</h3>
                    <p class="card-text text-muted">
                        Manage the book club community, oversee user accounts, and ensure the platform runs smoothly for all members. Keep the literary community thriving.
                    </p>
                    <?php if (Yii::$app->user->can('admin')): ?>
                        <a href="<?= \yii\helpers\Url::to(['users/index']) ?>" class="btn btn-outline-warning">
                            👥 Manage Users
                        </a>
                    <?php else: ?>
                        <a href="<?= \yii\helpers\Url::to(['site/about']) ?>" class="btn btn-outline-warning">
                            ℹ️ About Us
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!Yii::$app->user->isGuest): ?>
    <!-- Stats Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">📊 Community Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-number"><?= \app\models\Books::find()->count() ?></div>
                                <div class="stat-label">📚 Total Books</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-number"><?= \app\models\Users::find()->where(['role' => 'author'])->count() ?></div>
                                <div class="stat-label">✍️ Authors</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-number"><?= \app\models\Users::find()->where(['role' => 'reader'])->count() ?></div>
                                <div class="stat-label">👥 Readers</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-number"><?= \app\models\Users::find()->count() ?></div>
                                <div class="stat-label">👤 Total Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body text-center p-5">
                    <h2 class="mb-3">Ready to Start Your Literary Journey?</h2>
                    <p class="lead mb-4">Join our community of book lovers and discover your next favorite read.</p>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= \yii\helpers\Url::to(['site/send-verification']) ?>" class="btn btn-light btn-lg px-5">
                            🚀 Get Started Today
                        </a>
                    <?php else: ?>
                        <a href="<?= \yii\helpers\Url::to(['books/index']) ?>" class="btn btn-light btn-lg px-5">
                            📖 Explore Books
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background-size: cover;
    background-position: center;
}

.feature-icon {
    transition: transform 0.3s ease;
}

.card:hover .feature-icon {
    transform: scale(1.1);
}

.stat-card {
    padding: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: #6c757d;
    font-weight: 500;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>
