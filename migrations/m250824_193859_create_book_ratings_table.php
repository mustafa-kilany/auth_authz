<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_ratings}}`.
 */
class m250824_193859_create_book_ratings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_ratings}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'rating' => $this->smallInteger()->notNull(), // 1-5 stars
            'review' => $this->text(), // Optional review text
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Create foreign keys
        $this->addForeignKey(
            'fk-book_ratings-book_id',
            '{{%book_ratings}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_ratings-user_id',
            '{{%book_ratings}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Create indexes for better performance
        $this->createIndex('idx-book_ratings-book_id', '{{%book_ratings}}', 'book_id');
        $this->createIndex('idx-book_ratings-user_id', '{{%book_ratings}}', 'user_id');
        $this->createIndex('idx-book_ratings-unique', '{{%book_ratings}}', ['book_id', 'user_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_ratings}}');
    }
}
