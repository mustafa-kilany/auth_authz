<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $author_id
 * @property string|null $isbn
 * @property int|null $publication_year
 * @property string|null $genre
 * @property string|null $cover_image
 * @property string|null $file_path
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Users $author
 * @property BookRating[] $ratings
 */
class Books extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'isbn', 'publication_year', 'genre', 'cover_image', 'file_path'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['title', 'author_id', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['author_id', 'publication_year', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'cover_image', 'file_path'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['genre'], 'string', 'max' => 100],
            [['isbn'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'author_id' => Yii::t('app', 'Author ID'),
            'isbn' => Yii::t('app', 'Isbn'),
            'publication_year' => Yii::t('app', 'Publication Year'),
            'genre' => Yii::t('app', 'Genre'),
            'cover_image' => Yii::t('app', 'Cover Image'),
            'file_path' => Yii::t('app', 'File Path'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery|UsersQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Ratings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(BookRating::class, ['book_id' => 'id']);
    }

    /**
     * Get average rating for this book
     * @return float
     */
    public function getAverageRating()
    {
        return BookRating::getAverageRating($this->id);
    }

    /**
     * Get rating count for this book
     * @return int
     */
    public function getRatingCount()
    {
        return BookRating::getRatingCount($this->id);
    }

    /**
     * Get user's rating for this book
     * @param int $userId
     * @return BookRating|null
     */
    public function getUserRating($userId)
    {
        return BookRating::getUserRating($this->id, $userId);
    }

    /**
     * {@inheritdoc}
     * @return BooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BooksQuery(get_called_class());
    }

}
