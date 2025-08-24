<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m250824_192144_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'author_id' => $this->integer()->notNull(),
            'isbn' => $this->string(20)->unique(),
            'publication_year' => $this->integer(),
            'genre' => $this->string(100),
            'cover_image' => $this->string(255),
            'file_path' => $this->string(255), // For PDF or ebook files
            'status' => $this->smallInteger()->notNull()->defaultValue(1), // 1: published, 0: draft
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Create foreign key for author relationship
        $this->addForeignKey(
            'fk-books-author_id',
            '{{%books}}',
            'author_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Create indexes for better performance
        $this->createIndex('idx-books-title', '{{%books}}', 'title');
        $this->createIndex('idx-books-author_id', '{{%books}}', 'author_id');
        $this->createIndex('idx-books-genre', '{{%books}}', 'genre');
        $this->createIndex('idx-books-status', '{{%books}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
