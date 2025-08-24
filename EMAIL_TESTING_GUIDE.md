# 📧 Email Testing Guide - Free Online Services

This guide will help you set up email testing using free online services that can send emails to any address.

## 🚀 Quick Setup Options

### Option 1: Mailtrap (Recommended - Free)

**Mailtrap** is the best free email testing service:

1. **Sign up**: Go to https://mailtrap.io/
2. **Create account**: Free tier available
3. **Create inbox**: Click "Add Inbox"
4. **Get credentials**: Go to inbox → Settings → SMTP Settings
5. **Copy credentials**: Username, Password, Host, Port

**Configuration:**
```php
'mailer' => [
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'dsn' => 'smtp://YOUR_USERNAME:YOUR_PASSWORD@smtp.mailtrap.io:2525',
    ],
],
```

**Benefits:**
- ✅ Free forever
- ✅ Captures all emails
- ✅ No spam to real inboxes
- ✅ Professional interface
- ✅ Multiple inboxes

### Option 2: Ethereal Email (Free)

**Ethereal Email** is another free testing service:

1. **Visit**: https://ethereal.email/
2. **Create account**: Free registration
3. **Get credentials**: From your dashboard

**Configuration:**
```php
'mailer' => [
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'dsn' => 'smtp://YOUR_USERNAME:YOUR_PASSWORD@smtp.ethereal.email:587',
    ],
],
```

### Option 3: Gmail with App Password (Real Emails)

If you want to send real emails:

1. **Enable 2FA** on your Gmail
2. **Generate App Password**: Google Account → Security → App Passwords
3. **Use configuration**:
```php
'mailer' => [
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'dsn' => 'smtp://YOUR_GMAIL:YOUR_APP_PASSWORD@smtp.gmail.com:587',
    ],
],
```

## 🛠️ Setup Instructions

### Step 1: Choose Your Service

**For Testing (Recommended):** Use Mailtrap
**For Real Emails:** Use Gmail with App Password

### Step 2: Get Credentials

**Mailtrap:**
1. Go to https://mailtrap.io/
2. Sign up for free account
3. Create new inbox
4. Go to inbox settings
5. Copy SMTP credentials

**Gmail:**
1. Go to Google Account settings
2. Enable 2-Step Verification
3. Generate App Password for "Mail"
4. Copy the 16-character password

### Step 3: Update Configuration

**Update `config/web.php`:**
```php
'mailer' => [
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'dsn' => 'smtp://USERNAME:PASSWORD@HOST:PORT',
    ],
],
```

**Update `config/console.php`:**
```php
'mailer' => [
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'dsn' => 'smtp://USERNAME:PASSWORD@HOST:PORT',
    ],
],
```

### Step 4: Test Configuration

```bash
# Check configuration
php yii email/check

# Test email sending
php yii email/test any-email@example.com

# Test verification email
php yii email/test-verification any-email@example.com
```

## 🧪 Testing Your Setup

### With Mailtrap:
1. Send test email
2. Check your Mailtrap inbox
3. View the email content
4. Test verification links

### With Gmail:
1. Send test email
2. Check your Gmail inbox
3. Verify email content
4. Test verification links

## 📧 How It Works for Any Email

Once configured, **any email address can sign up**:

1. **User visits**: http://localhost:8081
2. **Clicks "Sign Up"** → Email verification page
3. **Enters any email** (e.g., `john@example.com`, `sarah@gmail.com`)
4. **System sends verification email** to that address
5. **User clicks verification link** → Completes registration
6. **User can now login** with their credentials

## 🔧 Troubleshooting

### Common Issues:

1. **"Authentication failed"**
   - Check username/password
   - Verify credentials are correct

2. **"Connection timeout"**
   - Check internet connection
   - Verify SMTP host and port

3. **"SSL/TLS required"**
   - Ensure using correct port (587 for TLS, 465 for SSL)

### Debug Commands:

```bash
# Check email configuration
php yii email/check

# View email logs
tail -f runtime/logs/app.log

# Test with specific email
php yii email/test your-test-email@example.com
```

## 🎯 Recommended Setup

**For Development/Testing:**
- Use **Mailtrap** (free, captures all emails)
- Perfect for testing without spam

**For Production:**
- Use **Gmail with App Password** or **SendGrid**
- Sends real emails to users

## ✅ Success Checklist

- [ ] Email service configured
- [ ] Credentials updated in config files
- [ ] Email configuration test passes
- [ ] Test email sent successfully
- [ ] Verification email working
- [ ] Signup flow tested

## 🆘 Need Help?

1. **Check logs**: `runtime/logs/app.log`
2. **Verify credentials**: Double-check username/password
3. **Test connection**: Use email check command
4. **Try different service**: Switch between Mailtrap/Gmail

## 🚀 Quick Start

**For immediate testing with Mailtrap:**

1. Go to https://mailtrap.io/
2. Sign up for free account
3. Create inbox and get credentials
4. Run: `php setup_mailtrap.php`
5. Follow the prompts to configure
6. Test: `php yii email/test test@example.com`
7. Check your Mailtrap inbox

**That's it! Your email system will work for any email address that wants to sign up.** 🎉
