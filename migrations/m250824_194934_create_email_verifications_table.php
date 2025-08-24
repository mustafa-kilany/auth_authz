<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%email_verifications}}`.
 */
class m250824_194934_create_email_verifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%email_verifications}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull(),
            'token' => $this->string(255)->notNull()->unique(),
            'type' => $this->string(50)->notNull(), // 'signup', 'password_reset', etc.
            'expires_at' => $this->integer()->notNull(),
            'is_used' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Create indexes for better performance
        $this->createIndex('idx-email_verifications-email', '{{%email_verifications}}', 'email');
        $this->createIndex('idx-email_verifications-token', '{{%email_verifications}}', 'token');
        $this->createIndex('idx-email_verifications-type', '{{%email_verifications}}', 'type');
        $this->createIndex('idx-email_verifications-expires_at', '{{%email_verifications}}', 'expires_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_verifications}}');
    }
}
