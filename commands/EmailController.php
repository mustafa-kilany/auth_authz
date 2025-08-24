<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Email testing controller
 */
class EmailController extends Controller
{
    /**
     * Test email sending
     * @param string $email Email address to send test to
     * @return int
     */
    public function actionTest($email)
    {
        if (!$email) {
            $this->stderr("Please provide an email address: php yii email/test your-email@example.com\n");
            return ExitCode::USAGE;
        }

        $this->stdout("Testing email sending to: $email\n");

        try {
            $message = Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' Book Club'])
                ->setTo($email)
                ->setSubject('Test Email - Book Club')
                ->setHtmlBody($this->renderPartial('_test_email', [
                    'email' => $email,
                    'time' => date('Y-m-d H:i:s'),
                ]));

            if ($message->send()) {
                $this->stdout("✅ Test email sent successfully!\n");
                $this->stdout("Check your inbox for the test email.\n");
                return ExitCode::OK;
            } else {
                $this->stderr("❌ Failed to send test email.\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } catch (\Exception $e) {
            $this->stderr("❌ Error sending email: " . $e->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Test verification email
     * @param string $email Email address to send verification to
     * @return int
     */
    public function actionTestVerification($email)
    {
        if (!$email) {
            $this->stderr("Please provide an email address: php yii email/test-verification your-email@example.com\n");
            return ExitCode::USAGE;
        }

        $this->stdout("Testing verification email to: $email\n");

        try {
            // Create a test verification
            $verification = new \app\models\EmailVerification();
            $verification->email = $email;
            $verification->token = Yii::$app->security->generateRandomString(32);
            $verification->type = \app\models\EmailVerification::TYPE_SIGNUP;
            $verification->expires_at = time() + (24 * 3600);
            $verification->is_used = false;
            $verification->created_at = time();

            $message = Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' Book Club'])
                ->setTo($email)
                ->setSubject('Test Verification Email - Book Club')
                ->setHtmlBody($this->renderPartial('_verification_email', [
                    'verification' => $verification
                ]));

            if ($message->send()) {
                $this->stdout("✅ Test verification email sent successfully!\n");
                $this->stdout("Check your inbox for the verification email.\n");
                return ExitCode::OK;
            } else {
                $this->stderr("❌ Failed to send test verification email.\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } catch (\Exception $e) {
            $this->stderr("❌ Error sending verification email: " . $e->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Check email configuration
     * @return int
     */
    public function actionCheck()
    {
        $this->stdout("Checking email configuration...\n\n");

        // Check mailer configuration
        $mailer = Yii::$app->mailer;
        $this->stdout("Mailer class: " . get_class($mailer) . "\n");
        $this->stdout("File transport: " . ($mailer->useFileTransport ? 'Enabled' : 'Disabled') . "\n");

        if (!$mailer->useFileTransport) {
            $transport = $mailer->transport;
            $this->stdout("Transport class: " . get_class($transport) . "\n");
            
            // For SymfonyMailer, get DSN info
            if (method_exists($transport, 'getStream')) {
                $stream = $transport->getStream();
                if ($stream) {
                    $this->stdout("SMTP Host: " . $stream->getHost() . "\n");
                    $this->stdout("SMTP Port: " . $stream->getPort() . "\n");
                }
            }
        }

        // Check email parameters
        $this->stdout("\nEmail parameters:\n");
        $this->stdout("Admin email: " . Yii::$app->params['adminEmail'] . "\n");
        $this->stdout("Sender email: " . Yii::$app->params['senderEmail'] . "\n");
        $this->stdout("Sender name: " . Yii::$app->params['senderName'] . "\n");

        return ExitCode::OK;
    }
}
