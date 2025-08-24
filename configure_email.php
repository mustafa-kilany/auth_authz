<?php
/**
 * Email Configuration Helper
 * Run this script to configure your email settings
 */

echo "📧 Book Club Email Configuration Helper\n";
echo "=====================================\n\n";

echo "Please provide your email configuration:\n\n";

// Get email provider
echo "Select your email provider:\n";
echo "1. Gmail (Recommended)\n";
echo "2. Outlook/Hotmail\n";
echo "3. Yahoo\n";
echo "4. Custom SMTP\n";
echo "Enter choice (1-4): ";
$provider = trim(fgets(STDIN));

// Get email address
echo "Enter your email address: ";
$email = trim(fgets(STDIN));

// Get password/app password
if ($provider == 1) {
    echo "Enter your Gmail App Password (16 characters): ";
    echo "Note: You need to enable 2FA and generate an app password\n";
} else {
    echo "Enter your email password: ";
}
$password = trim(fgets(STDIN));

// Generate DSN based on provider
switch ($provider) {
    case 1: // Gmail
        $dsn = "smtp://{$email}:{$password}@smtp.gmail.com:587";
        break;
    case 2: // Outlook
        $dsn = "smtp://{$email}:{$password}@smtp-mail.outlook.com:587";
        break;
    case 3: // Yahoo
        $dsn = "smtp://{$email}:{$password}@smtp.mail.yahoo.com:587";
        break;
    case 4: // Custom
        echo "Enter SMTP host: ";
        $host = trim(fgets(STDIN));
        echo "Enter SMTP port (587): ";
        $port = trim(fgets(STDIN)) ?: '587';
        $dsn = "smtp://{$email}:{$password}@{$host}:{$port}";
        break;
    default:
        echo "Invalid choice. Using Gmail.\n";
        $dsn = "smtp://{$email}:{$password}@smtp.gmail.com:587";
}

echo "\n📝 Configuration Summary:\n";
echo "Email: {$email}\n";
echo "DSN: {$dsn}\n\n";

echo "This will update your configuration files. Continue? (y/n): ";
$confirm = trim(fgets(STDIN));

if (strtolower($confirm) !== 'y') {
    echo "Configuration cancelled.\n";
    exit;
}

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

// Update params.php
$paramsConfig = file_get_contents('config/params.php');
$paramsConfig = preg_replace(
    "/'adminEmail' => '[^']*'/",
    "'adminEmail' => '{$email}'",
    $paramsConfig
);
$paramsConfig = preg_replace(
    "/'senderEmail' => '[^']*'/",
    "'senderEmail' => '{$email}'",
    $paramsConfig
);
file_put_contents('config/params.php', $paramsConfig);

echo "✅ Configuration updated successfully!\n\n";
echo "Next steps:\n";
echo "1. Test your configuration: php yii email/check\n";
echo "2. Test email sending: php yii email/test {$email}\n";
echo "3. Try the signup flow at http://localhost:8081\n\n";
echo "If you encounter issues, check the EMAIL_SETUP.md file for troubleshooting.\n";
