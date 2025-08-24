<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_verifications".
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property string $type
 * @property int $expires_at
 * @property bool $is_used
 * @property int $created_at
 */
class EmailVerification extends \yii\db\ActiveRecord
{
    const TYPE_SIGNUP = 'signup';
    const TYPE_PASSWORD_RESET = 'password_reset';
    
    const TOKEN_EXPIRY_HOURS = 24; // Token expires in 24 hours

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email_verifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'token', 'type', 'expires_at', 'created_at'], 'required'],
            [['expires_at', 'created_at'], 'integer'],
            [['is_used'], 'boolean'],
            [['email'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['token'], 'unique'],
            [['email'], 'email'],
            [['type'], 'in', 'range' => [self::TYPE_SIGNUP, self::TYPE_PASSWORD_RESET]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'expires_at' => Yii::t('app', 'Expires At'),
            'is_used' => Yii::t('app', 'Is Used'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Create a new verification token
     * @param string $email
     * @param string $type
     * @return EmailVerification|null
     */
    public static function createToken($email, $type = self::TYPE_SIGNUP)
    {
        // Delete any existing unused tokens for this email and type
        self::deleteAll(['email' => $email, 'type' => $type, 'is_used' => false]);
        
        $model = new self();
        $model->email = $email;
        $model->token = Yii::$app->security->generateRandomString(32);
        $model->type = $type;
        $model->expires_at = time() + (self::TOKEN_EXPIRY_HOURS * 3600);
        $model->is_used = false;
        $model->created_at = time();
        
        return $model->save() ? $model : null;
    }

    /**
     * Verify a token
     * @param string $token
     * @param string $type
     * @return EmailVerification|null
     */
    public static function verifyToken($token, $type = self::TYPE_SIGNUP)
    {
        $model = self::findOne([
            'token' => $token,
            'type' => $type,
            'is_used' => false
        ]);
        
        if (!$model) {
            return null;
        }
        
        // Check if token has expired
        if ($model->expires_at < time()) {
            return null;
        }
        
        return $model;
    }

    /**
     * Mark token as used
     * @return bool
     */
    public function markAsUsed()
    {
        $this->is_used = true;
        return $this->save();
    }

    /**
     * Check if token is expired
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at < time();
    }

    /**
     * Get verification URL
     * @return string
     */
    public function getVerificationUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'site/verify-email',
            'token' => $this->token,
            'type' => $this->type
        ]);
    }
}
