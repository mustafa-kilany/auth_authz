<?php
/**
 * Mailtrap Setup Helper
 * This will help you set up Mailtrap for email testing
 */

echo "📧 Mailtrap Email Testing Setup\n";
echo "==============================\n\n";

echo "Mailtrap is a free email testing service that captures emails for testing.\n\n";

echo "Steps to set up Mailtrap:\n";
echo "1. Go to https://mailtrap.io/\n";
echo "2. Sign up for a free account\n";
echo "3. Create a new inbox\n";
echo "4. Go to the inbox settings\n";
echo "5. Copy the SMTP credentials\n\n";

echo "Once you have your Mailtrap credentials, update the configuration:\n\n";

echo "Example Mailtrap configuration:\n";
echo "--------------------------------\n";
echo "SMTP Host: smtp.mailtrap.io\n";
echo "SMTP Port: 2525\n";
echo "Username: your-mailtrap-username\n";
echo "Password: your-mailtrap-password\n\n";

echo "Would you like me to help you configure Mailtrap? (y/n): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) === 'y') {
    echo "\nEnter your Mailtrap credentials:\n";
    echo "Username: ";
    $username = trim(fgets(STDIN));
    
    echo "Password: ";
    $password = trim(fgets(STDIN));
    
    $dsn = "smtp://{$username}:{$password}@smtp.mailtrap.io:2525";
    
    echo "\n📝 Configuration Summary:\n";
    echo "DSN: {$dsn}\n\n";
    
    echo "Update configuration files? (y/n): ";
    $update = trim(fgets(STDIN));
    
    if (strtolower($update) === 'y') {
        // Update web.php
        $webConfig = file_get_contents('config/web.php');
        $webConfig = preg_replace(
            "/'dsn' => 'smtp:\/\/[^']*'/",
            "'dsn' => '{$dsn}'",
            $webConfig
        );
        file_put_contents('config/web.php', $webConfig);
        
        // Update console.php
        $consoleConfig = file_get_contents('config/console.php');
        $consoleConfig = preg_replace(
            "/'dsn' => 'smtp:\/\/[^']*'/",
            "'dsn' => '{$dsn}'",
            $consoleConfig
        );
        file_put_contents('config/console.php', $consoleConfig);
        
        echo "✅ Configuration updated successfully!\n\n";
        echo "Next steps:\n";
        echo "1. Test your configuration: php yii email/check\n";
        echo "2. Test email sending: php yii email/test test@example.com\n";
        echo "3. Check your Mailtrap inbox to see the emails\n";
        echo "4. Try the signup flow at http://localhost:8081\n\n";
    }
} else {
    echo "\nYou can manually configure Mailtrap by:\n";
    echo "1. Getting credentials from https://mailtrap.io/\n";
    echo "2. Updating config/web.php and config/console.php\n";
    echo "3. Using the format: smtp://username:password@smtp.mailtrap.io:2525\n\n";
}

echo "Alternative: You can also use these free testing services:\n";
echo "- Ethereal Email: smtp://ethereal.email:587\n";
echo "- MailHog: smtp://localhost:1025 (if installed locally)\n";
echo "- Gmail with App Password (as we tried before)\n\n";
