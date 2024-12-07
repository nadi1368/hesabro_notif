<?php

namespace hesabro\notif\models;

use hesabro\notif\Module;
use Yii;
use yii\base\Event;
use yii\helpers\Html;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collections "notification".
 *
 * @property int $_id
 * @property boolean $seen
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $model_class
 * @property string $event
 * @property int|null $model_id
 * @property int $slave_id
 * @property boolean $send_sms
 * @property int $send_sms_delay
 * @property boolean $send_email
 * @property int $send_email_delay
 * @property int $link
 * @property int $created_by
 * @property int $created_at
 *
 * @property object $user
 * @property object $createBy
 */
class Notif extends ActiveRecord
{
    const EVENT_AFTER_INSERT_NOTIF = 'EVENT_AFTER_INSERT_NOTIF';

    public static function collectionName(): string
    {
        return 'notif';
    }

    public function attributes()
    {
        return [
            '_id', 'title', 'description', 'seen', 'model_class', 'model_id', 'event',
            'send_sms', 'send_sms_delay', 'send_email', 'send_email_delay', 'link',
            'user_id', 'created_at', 'created_by', 'slave_id'
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'model_class'], 'required'],
            [['user_id', 'send_sms_delay', 'send_email_delay', 'model_id', 'created_at', 'created_by', 'slave_id'], 'number'],
            [['send_sms', 'send_email'], 'boolean'],
            [['send_sms', 'send_email'], 'default', 'value' => false],
            [['send_sms_delay', 'send_email_delay'], 'default', 'value' => 0],
            [['title', 'description', 'model_class', 'link'], 'string'],
            [['seen'], 'boolean'],
            [['seen'], 'default', 'value' => false],
            ['user_id', 'exist', 'targetClass' => Module::getInstance()->user, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => Module::t('module', 'User'),
            'title' => Module::t('module', 'Title'),
            'description' => Module::t('module', 'Text'),
            'created_by' => Module::t('module', 'Created By'),
            'send_sms' => Module::t('module', 'Send Sms'),
            'send_sms_delay' => Module::t('module', 'Send Sms Delay'),
            'send_email' => Module::t('module', 'Send Email'),
            'send_email_delay' => Module::t('module', 'Send Email Delay'),
            'model_class' => Module::t('module', 'Model Class'),
            'model_id' => Module::t('module', 'Model Id'),
            'created_at' => Module::t('module', 'Created At'),
            'event' => Module::t('module', 'Event')
        ];
    }

    public static function find(): NotifQuery
    {
        return new NotifQuery(get_called_class());
    }

    public function afterFind(): void
    {
        parent::afterFind();
    }

    public function getUser()
    {
        return $this->hasOne(Module::getInstance()->user, ['id' => 'user_id']);
    }

    public function getCreateBy()
    {
        return $this->hasOne(Module::getInstance()->user, ['id' => 'created_by']);
    }

    public function sendSms(): void
    {
        if (Module::getInstance()->sms && $this->send_sms && $this->user) {
            Module::getInstance()->sms::send(
                $this->user,
                $this->title,
                Module::getInstance()->websiteName . PHP_EOL . strip_tags($this->description),
                $this->send_sms_delay,
                $this->model_class,
                $this->model_id
            );
        }
    }

    public function sendEmail(): void
    {
        if (Module::getInstance()->email  && $this->send_email && $this->user) {
            $text = ($this->description ?: '') . implode($this->link ? [
                    '<br />',
                    Module::t('module', 'See More Details'),
                    ': <br />',
                    Html::a($this->link, $this->link)
                ] : []);

            Module::getInstance()->email::send(
                $this->user,
                $this->title,
                $text,
                $this->send_email_delay,
                $this->model_class,
                $this->model_id
            );
        }
    }

    public function markAsSeen(): void
    {
        if (!$this->seen) {
            $this->seen = true;
            $this->save(false);
        }
    }

    public function beforeSave($insert)
    {
        $this->created_by = Yii::$app->user?->id;
        $this->created_at = time();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->sendSms();
        $this->sendEmail();

        $this->trigger(self::EVENT_AFTER_INSERT_NOTIF, new class(['notif' => $this]) extends Event {
            public $notif = null;
        });
    }

    /**
     * @return Notif[]
     */
    public static function findCurrentUserUnSeen($limit = null): array
    {
        $query = self::find()
            ->own();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->unseen()->all();
    }

    /**
     * @return Notif[]
     */
    public static function countCurrentUserUnSeen(): int
    {
        return self::find()
            ->own()
            ->unseen()
            ->count();
    }

    public static function findCurrentUser(): array
    {
        return self::find()
            ->own()
            ->all();
    }
}
