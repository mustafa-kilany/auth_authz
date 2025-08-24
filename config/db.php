<?php

return [
    'class' => 'yii\db\Connection',
    
    // MySQL connection via XAMPP
    'dsn' => 'mysql:host=localhost;dbname=book_club',
    
    // Default XAMPP MySQL credentials
    'username' => 'root',
    'password' => '', // Empty password by default in XAMPP
    
    // MySQL settings
    'charset' => 'utf8mb4',
    
    // Enable schema cache for better performance
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
    
    // Connection attributes
    'attributes' => [
        PDO::ATTR_TIMEOUT => 30,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ],
    
    // MySQL specific options
    'emulatePrepare' => true,
];
