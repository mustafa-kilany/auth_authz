<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book_ratings".
 *
 * @property int $id
 * @property int $book_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $review
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Books $book
 * @property User $user
 */
class BookRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book_ratings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'user_id', 'rating', 'created_at', 'updated_at'], 'required'],
            [['book_id', 'user_id', 'rating', 'created_at', 'updated_at'], 'integer'],
            [['review'], 'string'],
            [['rating'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'book_id' => Yii::t('app', 'Book ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'rating' => Yii::t('app', 'Rating'),
            'review' => Yii::t('app', 'Review'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get average rating for a book
     * @param int $bookId
     * @return float
     */
    public static function getAverageRating($bookId)
    {
        $avgRating = self::find()
            ->where(['book_id' => $bookId])
            ->average('rating');
        
        return $avgRating ? round($avgRating, 1) : 0;
    }

    /**
     * Get rating count for a book
     * @param int $bookId
     * @return int
     */
    public static function getRatingCount($bookId)
    {
        return self::find()
            ->where(['book_id' => $bookId])
            ->count();
    }

    /**
     * Get user's rating for a book
     * @param int $bookId
     * @param int $userId
     * @return BookRating|null
     */
    public static function getUserRating($bookId, $userId)
    {
        return self::findOne(['book_id' => $bookId, 'user_id' => $userId]);
    }
}
