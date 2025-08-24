<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Verify Your Email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-send-verification">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">📧 Email Verification</h3>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <span style="font-size: 3rem;">📚</span>
                        </div>
                        <h4>Welcome to Book Club!</h4>
                        <p class="text-muted">To complete your registration, we need to verify your email address.</p>
                    </div>

                    <div class="alert alert-info">
                        <strong>ℹ️ How it works:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Enter your email address below</li>
                            <li>Check your inbox for a verification link</li>
                            <li>Click the link to verify your email</li>
                            <li>Complete your registration</li>
                        </ol>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'send-verification-form',
                        'options' => ['class' => 'form-horizontal'],
                    ]); ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter your email address" required>
                        <div class="form-text">We'll send a verification link to this email address.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('📧 Send Verification Email', [
                            'class' => 'btn btn-primary btn-lg',
                            'name' => 'send-verification-button'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-2">Already have an account?</p>
                        <?= Html::a('🔐 Login here', ['login'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 8px;
    padding: 12px 15px;
}

.btn {
    border-radius: 8px;
    padding: 12px 20px;
}
</style>
