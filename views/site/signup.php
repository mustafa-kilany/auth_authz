<?php

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */
/** @var string|null $verifiedEmail */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Sign up';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">✅ Complete Registration</h3>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($verifiedEmail): ?>
                        <!-- Email is verified, show signup form -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <span style="font-size: 3rem;">✅</span>
                            </div>
                            <h4>Email Verified!</h4>
                            <p class="text-muted">Your email <strong><?= Html::encode($verifiedEmail) ?></strong> has been verified. Please complete your registration.</p>
                        </div>

                        <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email')->textInput([
                            'value' => $verifiedEmail,
                            'readonly' => true,
                            'class' => 'form-control bg-light'
                        ])->hint('This email has been verified.') ?>

                        <?= $form->field($model, 'first_name') ?>

                        <?= $form->field($model, 'last_name') ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= $form->field($model, 'confirm_password')->passwordInput() ?>

                        <?= $form->field($model, 'role')->dropDownList([
                            'reader' => 'Reader - Can view and read books',
                            'author' => 'Author - Can create and edit own books',
                        ], ['prompt' => 'Select your role']) ?>

                        <div class="d-grid gap-2">
                            <?= Html::submitButton('✅ Complete Registration', [
                                'class' => 'btn btn-success btn-lg',
                                'name' => 'signup-button'
                            ]) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    <?php else: ?>
                        <!-- Email not verified, redirect to verification -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <span style="font-size: 3rem;">📧</span>
                            </div>
                            <h4>Email Verification Required</h4>
                            <p class="text-muted">Before you can create an account, you need to verify your email address.</p>
                        </div>

                        <div class="alert alert-warning">
                            <strong>⚠️ Important:</strong> Email verification is required for security reasons. This helps us ensure that only real email addresses are used for registration.
                        </div>

                        <div class="d-grid gap-2">
                            <?= Html::a('📧 Verify Email First', ['send-verification'], [
                                'class' => 'btn btn-primary btn-lg'
                            ]) ?>
                            
                            <?= Html::a('🔐 Already Have Account?', ['login'], [
                                'class' => 'btn btn-outline-secondary'
                            ]) ?>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-2">Already have an account?</p>
                        <?= Html::a('🔐 Login here', ['login']) ?>
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

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
