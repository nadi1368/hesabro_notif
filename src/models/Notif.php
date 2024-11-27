<?php

namespace hesabro\notif\models;

use hesabro\notif\Module;
use Yii;
use yii\base\Event;
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
 * @property int|null $model_id
 * @property int $slave_id
 * @property boolean $send_sms
 * @property int $send_sms_delay
 * @property boolean $send_email
 * @property int $send_email_delay
 * @property boolean $send_ticket
 * @property int $send_ticket_delay
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
            '_id', 'title', 'description', 'seen', 'model_class', 'model_id',
            'send_sms', 'send_sms_delay', 'send_email', 'send_email_delay', 'send_ticket', 'send_ticket_delay',
            'user_id', 'created_at', 'created_by', 'slave_id'
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'model_class'], 'required'],
            [['user_id', 'send_sms_delay', 'send_email_delay', 'send_ticket_delay', 'model_id', 'created_at', 'created_by', 'slave_id'], 'number'],
            [['send_sms', 'send_email', 'send_ticket'], 'boolean'],
            [['send_sms', 'send_email', 'send_ticket'], 'default', 'value' => false],
            [['send_sms_delay', 'send_email_delay', 'send_ticket_delay'], 'default', 'value' => 0],
            [['title', 'description', 'model_class'], 'string'],
            [['seen'], 'boolean'],
            [['seen'], 'default', 'value' => false],
            ['user_id', 'exist', 'targetClass' => Module::getInstance()->user, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'کاربر',
            'time' => 'زمان',
            'title' => 'عنوان',
            'description' => 'توضیحات',
            'create_by' => 'تغییر توسط',
            'send_sms' => 'ارسال پیامک',
            'send_sms_delay' => 'تاخیر در ارسال پیامک',
            'send_email' => 'ارسال ایمیل',
            'send_email_delay' => 'تاخیر در ارسال ایمیل',
            'send_ticket' => 'ارسال تیکت',
            'send_ticket_delay' => 'تاخیر در ارسال تیکت',
            'model_class' => 'نام مدل',
            'model_id' => 'شناسه مدل',
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
            Module::getInstance()->email::send(
                $this->user,
                $this->title,
                $this->description,
                $this->send_email_delay,
                $this->model_class,
                $this->model_id
            );
        }
    }

    public function sendTicket(): void
    {
        if (Module::getInstance()->ticket && $this->send_ticket && $this->user) {
            Module::getInstance()->ticket::send(
                $this->user,
                $this->title,
                $this->description,
                $this->send_ticket_delay,
                $this->model_class,
                $this->model_id
            );
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
        $this->sendTicket();

        $this->trigger(self::EVENT_AFTER_INSERT_NOTIF, new class(['notif' => $this]) extends Event {
            public $notif = null;
        });
    }

    /**
     * @return Notif[]
     */
    public static function myUnseen(): array
    {
        return self::find()
            ->own()
            ->unseen()
            ->all();
    }

    public static function myAll(): array
    {
        return self::find()
            ->own()
            ->all();
    }
}
