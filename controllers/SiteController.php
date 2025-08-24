<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\EmailVerification;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Email verification step 1: Send verification email
     *
     * @return Response|string
     */
    public function actionSendVerification()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (Yii::$app->request->isPost) {
            $email = Yii::$app->request->post('email');
            
            if ($email) {
                // Create verification token
                $verification = EmailVerification::createToken($email, EmailVerification::TYPE_SIGNUP);
                
                if ($verification) {
                    // Send verification email
                    $sent = $this->sendVerificationEmail($verification);
                    
                    if ($sent) {
                        Yii::$app->session->setFlash('success', 'Verification email sent! Please check your inbox and click the verification link.');
                        return $this->redirect(['signup', 'email' => $email]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to send verification email. Please try again.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to create verification token. Please try again.');
                }
            }
        }

        return $this->render('send-verification');
    }

    /**
     * Email verification step 2: Complete signup with verification
     *
     * @return Response|string
     */
    public function actionVerifyEmail()
    {
        $token = Yii::$app->request->get('token');
        $type = Yii::$app->request->get('type', EmailVerification::TYPE_SIGNUP);
        
        if (!$token) {
            Yii::$app->session->setFlash('error', 'Invalid verification link.');
            return $this->redirect(['signup']);
        }
        
        // Verify token
        $verification = EmailVerification::verifyToken($token, $type);
        
        if (!$verification) {
            Yii::$app->session->setFlash('error', 'Invalid or expired verification link. Please request a new verification email.');
            return $this->redirect(['signup']);
        }
        
        // Store verified email in session for signup
        Yii::$app->session->set('verified_email', $verification->email);
        Yii::$app->session->set('verification_token', $token);
        
        Yii::$app->session->setFlash('success', 'Email verified successfully! Please complete your registration.');
        return $this->redirect(['signup']);
    }

    /**
     * Signup action with email verification.
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        
        // Check if email is verified
        $verifiedEmail = Yii::$app->session->get('verified_email');
        $verificationToken = Yii::$app->session->get('verification_token');
        
        if ($verifiedEmail) {
            $model->email = $verifiedEmail;
        }
        
        if ($model->load(Yii::$app->request->post())) {
            // Verify token again before creating user
            if ($verificationToken) {
                $verification = EmailVerification::verifyToken($verificationToken, EmailVerification::TYPE_SIGNUP);
                
                if ($verification && $verification->email === $model->email) {
                    if ($model->signup()) {
                        // Mark token as used
                        $verification->markAsUsed();
                        
                        // Clear session
                        Yii::$app->session->remove('verified_email');
                        Yii::$app->session->remove('verification_token');
                        
                        Yii::$app->session->setFlash('success', 'Account created successfully! You can now login with your credentials.');
                        return $this->redirect(['login']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Email verification failed. Please verify your email again.');
                    return $this->redirect(['send-verification']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Please verify your email first.');
                return $this->redirect(['send-verification']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'verifiedEmail' => $verifiedEmail,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Send verification email
     * @param EmailVerification $verification
     * @return bool
     */
    private function sendVerificationEmail($verification)
    {
        try {
            $message = Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' Book Club'])
                ->setTo($verification->email)
                ->setSubject('Verify Your Email - Book Club Registration')
                ->setHtmlBody($this->renderPartial('_verification_email', [
                    'verification' => $verification
                ]));
            
            return $message->send();
        } catch (\Exception $e) {
            Yii::error('Email sending error: ' . $e->getMessage());
            return false;
        }
    }
}
