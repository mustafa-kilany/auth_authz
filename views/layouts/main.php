<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '📚 Book Club',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-primary fixed-top']
    ]);
    
    $menuItems = [
        ['label' => '🏠 Home', 'url' => ['/site/index']],
        ['label' => '📖 Books', 'url' => ['/books/index']],
    ];
    
    // Add role-based menu items for authenticated users
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->can('author') || Yii::$app->user->can('admin')) {
            $menuItems[] = ['label' => '✍️ Create Book', 'url' => ['/books/create']];
        }
        
        if (Yii::$app->user->can('admin')) {
            $menuItems[] = ['label' => '👥 Users', 'url' => ['/users/index']];
        }
        
        $menuItems[] = ['label' => 'ℹ️ About', 'url' => ['/site/about']];
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto'],
        'items' => $menuItems,
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            Yii::$app->user->isGuest
                ? [
                    'label' => '🔐 Login',
                    'url' => ['/site/login'],
                    'options' => ['class' => 'nav-item'],
                ]
                : '<li class="nav-item dropdown">'
                    . Html::a(
                        '👤 ' . Yii::$app->user->identity->username . ' (' . Yii::$app->user->identity->role . ')',
                        '#',
                        ['class' => 'nav-link dropdown-toggle', 'data-bs-toggle' => 'dropdown']
                    )
                    . '<ul class="dropdown-menu">'
                    . '<li>' . Html::a('👤 Profile', ['/users/view', 'id' => Yii::$app->user->id], ['class' => 'dropdown-item']) . '</li>'
                    . '<li><hr class="dropdown-divider"></li>'
                    . '<li>' . Html::beginForm(['/site/logout'])
                    . Html::submitButton('🚪 Logout', ['class' => 'dropdown-item btn btn-link'])
                    . Html::endForm() . '</li>'
                    . '</ul>'
                    . '</li>'
        ]
    ]);
    
    // Add signup link for guests
    if (Yii::$app->user->isGuest) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                [
                    'label' => '📝 Sign Up',
                    'url' => ['/site/send-verification'],
                    'options' => ['class' => 'nav-item'],
                ]
            ]
        ]);
    }
    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; 📚 Book Club <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
