<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

?>

<div class="books-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'genre')->dropDownList([
        '' => 'All Genres',
        'Fiction' => 'Fiction',
        'Non-Fiction' => 'Non-Fiction',
        'Science Fiction' => 'Science Fiction',
        'Fantasy' => 'Fantasy',
        'Mystery' => 'Mystery',
        'Romance' => 'Romance',
        'Thriller' => 'Thriller',
        'Biography' => 'Biography',
        'History' => 'History',
        'Self-Help' => 'Self-Help',
        'Business' => 'Business',
        'Technology' => 'Technology',
        'Philosophy' => 'Philosophy',
        'Poetry' => 'Poetry',
        'Drama' => 'Drama',
        'Comedy' => 'Comedy',
        'Horror' => 'Horror',
        'Adventure' => 'Adventure',
        'Children' => 'Children',
        'Young Adult' => 'Young Adult',
        'Other' => 'Other',
    ]) ?>

    <?= $form->field($model, 'publication_year') ?>

    <div class="form-group">
        <?= Html::submitButton('🔍 Search', ['class' => 'btn btn-primary w-100']) ?>
        <?= Html::a('🔄 Reset', ['index'], ['class' => 'btn btn-outline-secondary w-100 mt-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
