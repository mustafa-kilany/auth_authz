# 📧 Email Setup Guide for Book Club

This guide will help you configure real email sending for the Book Club application.

## 🚀 Quick Setup Options

### Option 1: Gmail (Recommended for Testing)

#### Step 1: Enable 2-Factor Authentication
1. Go to your Google Account settings
2. Navigate to Security
3. Enable 2-Step Verification

#### Step 2: Generate App Password
1. Go to Google Account settings
2. Navigate to Security → 2-Step Verification
3. Click "App passwords"
4. Generate a new app password for "Mail"
5. Copy the 16-character password

#### Step 3: Update Configuration
Edit `config/web.php` and replace:
```php
'username' => 'your-email@gmail.com', // Your Gmail address
'password' => 'your-app-password', // The 16-character app password
```

### Option 2: Other SMTP Providers

#### Outlook/Hotmail
```php
'host' => 'smtp-mail.outlook.com',
'port' => '587',
'encryption' => 'tls',
```

#### Yahoo Mail
```php
'host' => 'smtp.mail.yahoo.com',
'port' => '587',
'encryption' => 'tls',
```

#### Custom SMTP Server
```php
'host' => 'your-smtp-server.com',
'port' => '587', // or 465 for SSL
'encryption' => 'tls', // or 'ssl'
```

## 🔧 Configuration Examples

### Gmail Configuration
```php
'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'yourname@gmail.com',
        'password' => 'abcd efgh ijkl mnop', // App password
        'port' => '587',
        'encryption' => 'tls',
    ],
],
```

### Outlook Configuration
```php
'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp-mail.outlook.com',
        'username' => 'yourname@outlook.com',
        'password' => 'your-password',
        'port' => '587',
        'encryption' => 'tls',
    ],
],
```

## 📧 Update Email Parameters

Edit `config/params.php`:
```php
<?php
return [
    'adminEmail' => 'your-email@gmail.com', // Your real email
    'senderEmail' => 'noreply@yourdomain.com', // Or your email
    'senderName' => 'Book Club',
];
```

## 🧪 Testing Email Sending

### Test with Development Server
1. Start the development server: `php yii serve`
2. Go to `http://localhost:8080`
3. Click "Sign Up"
4. Enter a real email address
5. Check your email inbox

### Debug Email Issues
If emails aren't sending, check:
1. SMTP credentials are correct
2. App password is valid (for Gmail)
3. Firewall isn't blocking port 587
4. Check application logs in `runtime/logs/`

## 🔒 Security Notes

- Never commit real email credentials to version control
- Use environment variables for production
- Consider using email services like SendGrid, Mailgun, or AWS SES for production

## 🚀 Production Setup

For production, consider using professional email services:

### SendGrid
```php
'host' => 'smtp.sendgrid.net',
'username' => 'apikey',
'password' => 'your-sendgrid-api-key',
'port' => '587',
'encryption' => 'tls',
```

### Mailgun
```php
'host' => 'smtp.mailgun.org',
'username' => 'your-mailgun-username',
'password' => 'your-mailgun-password',
'port' => '587',
'encryption' => 'tls',
```

## 📝 Troubleshooting

### Common Issues:

1. **"Authentication failed"**
   - Check username/password
   - For Gmail: Use app password, not regular password

2. **"Connection timeout"**
   - Check firewall settings
   - Verify SMTP host and port

3. **"SSL/TLS required"**
   - Ensure encryption is set to 'tls' or 'ssl'
   - Check port settings (587 for TLS, 465 for SSL)

4. **"Sender not allowed"**
   - Verify sender email matches SMTP account
   - Check email provider's sending limits

### Enable Debug Logging
Add to `config/web.php`:
```php
'log' => [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info'],
            'categories' => ['yii\mail\*'],
        ],
    ],
],
```

## ✅ Success Checklist

- [ ] SMTP credentials configured
- [ ] App password generated (Gmail)
- [ ] Email parameters updated
- [ ] Test email sent successfully
- [ ] Verification emails working
- [ ] Error logging enabled

## 🆘 Need Help?

If you're still having issues:
1. Check the application logs
2. Verify SMTP settings with your email provider
3. Test with a different email service
4. Ensure your hosting provider allows SMTP connections
