<?php
// Define Yii constants
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Load Yii configuration
$config = require __DIR__ . '/config/console.php';
$app = new yii\console\Application($config);

try {
    echo "Testing email configuration...\n";
    
    $mailer = Yii::$app->mailer;
    echo "Mailer class: " . get_class($mailer) . "\n";
    echo "File transport: " . ($mailer->useFileTransport ? 'Enabled' : 'Disabled') . "\n";
    
    $message = $mailer->compose()
        ->setFrom(['admin@bookclub.com' => 'Book Club'])
        ->setTo('test@example.com')
        ->setSubject('Test Email')
        ->setTextBody('This is a test email');
    
    echo "Message created successfully\n";
    
    $result = $message->send();
    echo "Send result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
