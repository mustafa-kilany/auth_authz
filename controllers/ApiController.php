<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\EmailVerification;
use app\models\SignupForm;
use app\models\User;

/**
 * API Controller for email verification and user management
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'send-verification' => ['post'],
                    'verify-email' => ['post'],
                    'resend-verification' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['send-verification', 'verify-email', 'resend-verification'],
                        'roles' => ['?'], // Allow guests
                    ],
                ],
            ],
        ];
    }

    /**
     * Send email verification
     * @return Response
     */
    public function actionSendVerification()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $email = Yii::$app->request->post('email');
        
        if (!$email) {
            return [
                'success' => false,
                'message' => 'Email is required'
            ];
        }
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email format'
            ];
        }
        
        // Check if user already exists
        if (User::findByEmail($email)) {
            return [
                'success' => false,
                'message' => 'User with this email already exists'
            ];
        }
        
        try {
            // Create verification token
            $verification = EmailVerification::createToken($email, EmailVerification::TYPE_SIGNUP);
            
            if (!$verification) {
                return [
                    'success' => false,
                    'message' => 'Failed to create verification token'
                ];
            }
            
            // Send verification email
            $sent = $this->sendVerificationEmail($verification);
            
            if ($sent) {
                return [
                    'success' => true,
                    'message' => 'Verification email sent successfully. Please check your inbox.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to send verification email'
                ];
            }
            
        } catch (\Exception $e) {
            Yii::error('Email verification error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ];
        }
    }

    /**
     * Verify email and complete signup
     * @return Response
     */
    public function actionVerifyEmail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $token = Yii::$app->request->post('token');
        $userData = Yii::$app->request->post('user_data');
        
        if (!$token || !$userData) {
            return [
                'success' => false,
                'message' => 'Token and user data are required'
            ];
        }
        
        // Verify token
        $verification = EmailVerification::verifyToken($token, EmailVerification::TYPE_SIGNUP);
        
        if (!$verification) {
            return [
                'success' => false,
                'message' => 'Invalid or expired verification token'
            ];
        }
        
        // Validate user data
        $signupForm = new SignupForm();
        $signupForm->load($userData, '');
        $signupForm->email = $verification->email; // Use verified email
        
        if (!$signupForm->validate()) {
            return [
                'success' => false,
                'message' => 'Invalid user data',
                'errors' => $signupForm->errors
            ];
        }
        
        try {
            // Create user
            if ($signupForm->signup()) {
                // Mark token as used
                $verification->markAsUsed();
                
                return [
                    'success' => true,
                    'message' => 'Account created successfully! You can now login.',
                    'redirect' => Yii::$app->urlManager->createUrl(['site/login'])
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create account'
                ];
            }
            
        } catch (\Exception $e) {
            Yii::error('Account creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while creating your account'
            ];
        }
    }

    /**
     * Resend verification email
     * @return Response
     */
    public function actionResendVerification()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $email = Yii::$app->request->post('email');
        
        if (!$email) {
            return [
                'success' => false,
                'message' => 'Email is required'
            ];
        }
        
        // Check if user already exists
        if (User::findByEmail($email)) {
            return [
                'success' => false,
                'message' => 'User with this email already exists'
            ];
        }
        
        try {
            // Create new verification token
            $verification = EmailVerification::createToken($email, EmailVerification::TYPE_SIGNUP);
            
            if (!$verification) {
                return [
                    'success' => false,
                    'message' => 'Failed to create verification token'
                ];
            }
            
            // Send verification email
            $sent = $this->sendVerificationEmail($verification);
            
            if ($sent) {
                return [
                    'success' => true,
                    'message' => 'Verification email resent successfully. Please check your inbox.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to send verification email'
                ];
            }
            
        } catch (\Exception $e) {
            Yii::error('Resend verification error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ];
        }
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
