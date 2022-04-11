<?php

namespace common\models;

use common\models\query\VideoQuery;
use Imagine\Image\Box;
use Yii;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property string $video_id
 * @property string $title
 * @property string|null $description
 * @property int|null $created_by
 * @property string|null $tags
 * @property int|null $status
 * @property int|null $has_thumbnail
 * @property string|null $video_name
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property VideoLike[] $likes
 * @property VideoLike[] $dislikes
 * @property User $createdBy
 */
class Video extends ActiveRecord
{
    const STATUS_UNLISTED = 0;
    const STATUS_PUBLISHED = 1;
    public $video;
    public $thumbnail;
    /**
     * @var UploadedFile
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    public function behaviors()
    {
        return [

            // have to method created_at and updated_at
            TimestampBehavior::class,
            [
                // have to method mange created_by and updated_by
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // first element is an attributes, second is a rule
            [['video_id', 'title'], 'required'],
            [['description'], 'string'],
            [['created_by', 'status', 'has_thumbnail'], 'integer'],
            [['video_id'], 'string', 'max' => 16],
            [['title', 'tags', 'video_name'], 'string', 'max' => 512],
            [['video_id'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_UNLISTED],
            ['has_thumbnail', 'default', 'value' => 0],
            ['thumbnail', 'image', 'minWidth' => 1280],
            ['video', 'file', 'checkExtensionByMimeType' => false, 'extensions' => ['mp4']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'video_id' => 'Video ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_by' => 'Created By',
            'tags' => 'Tags',
            'status' => 'Status',
            'has_thumbnail' => 'Has Thumbnail',
            'video_name' => 'Video Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'thumbnail' => 'Thumbnail',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy() // magic property will be createdBy
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
    /**
     * Gets query for [[view]].
     *
     * @return ActiveQuery
     */
    public function getViews() { // magic property will be views
        return $this->hasMany(VideoView::class, ['video_id' => 'video_id']);
    }
    /**
     * {@inheritdoc}
     * @return VideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VideoQuery(get_called_class());
    }

    /**
     *
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {

        $isInsert = $this->isNewRecord;
        if ($isInsert) {
            $this->video_id = Yii::$app->security->generateRandomString(8);
            $this->title = $this->video->name;
            $this->video_name = $this->video->name;
        }
        if ($this->thumbnail) {
            $this->has_thumbnail = 1;
        }
        if ($this->thumbnail) {
            $this->has_thumbnail = 1;
        }
        $saved = parent::save($runValidation, $attributeNames);
        if (!$saved) {
            return false;
        }
        if ($isInsert) {
            // TODO NOTE we have to save file inside web to let browser accuses the file
            $videoPath = Yii::getAlias('@frontend/web/storage/videos/' . $this->video_id . '.mp4');
            if (!is_dir($videoPath)) {
                FileHelper::createDirectory(dirname($videoPath));
            }
            $this->video->saveAs($videoPath);
        }
        if ($this->thumbnail) {
            $thumbnailPath = Yii::getAlias('@frontend/web/storage/thumbnail/' . $this->video_id . '.jpg');
            if (!is_dir($thumbnailPath)) {
                FileHelper::createDirectory(dirname($thumbnailPath));
            }
            $this->thumbnail->saveAs($thumbnailPath);
            Image::getImagine()
                ->open($thumbnailPath)
                ->thumbnail(new Box(1280, 1280));
        }
        return true;
    }

    public function getVideoLink()
    {
        return Yii::$app->params['frontendUrl'] . 'storage/videos/' . $this->video_id . '.mp4';
    }

    public function getThumbnailLink()
    {
        return $this->has_thumbnail ?
            Yii::$app->params['frontendUrl'] . 'storage/thumbnail/' . $this->video_id . '.jpg'
            : '';
    }

    public function getStatusLabels()
    {
        return [
            self::STATUS_UNLISTED => 'unlisted',
            self::STATUS_PUBLISHED => 'published'
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $videoPath = Yii::getAlias('@frontend/web/storage/videos/' . $this->video_id . '.mp4');
        unlink($videoPath);
        $thumbnailPath = Yii::getAlias('@frontend/web/storage/thumbnail/' . $this->video_id . '.jpg');
        if (file_exists($thumbnailPath)) {
            unlink($thumbnailPath);
        }
    }

    public function isLikedBy($userId)
    {
        return VideoLike::find()
            ->userIdVideoId($userId, $this->video_id)
            ->liked()
            ->one();
    }

    public function isDislikedBy($userId)
    {
        VideoLike::find()
            ->userIdVideoId($userId, $this->video_id)
            ->disliked()
            ->one();
    }

    /**
     * Gets query for [[view]].
     *
     * @return ActiveQuery
     */
    public function getLikes()
    { // magic property will be views
        return $this->hasMany(VideoLike::class, ['video_id' => 'video_id'])
            ->liked();
    }

    public function getDislikes()
    {
        return $this->hasMany(VideoLike::class, ['video_id' => 'video_id'])
            ->disliked();
    }
}
