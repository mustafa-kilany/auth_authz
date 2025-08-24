<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Email - Book Club</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📚 Book Club</div>
            <h1 class="title">Test Email</h1>
        </div>
        
        <div class="content">
            <p>Hello!</p>
            
            <div class="success">
                <strong>✅ Email Test Successful!</strong><br>
                This is a test email to verify that your email configuration is working correctly.
            </div>
            
            <p><strong>Test Details:</strong></p>
            <ul>
                <li><strong>Recipient:</strong> <?= $email ?></li>
                <li><strong>Sent at:</strong> <?= $time ?></li>
                <li><strong>Status:</strong> Email sent successfully</li>
            </ul>
            
            <p>If you received this email, your email configuration is working properly and you can proceed with using the Book Club application.</p>
        </div>
        
        <div class="footer">
            <p>This is a test email from Book Club email system.</p>
            <p>&copy; <?= date('Y') ?> Book Club. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
