<?php

namespace hesabro\notif\behaviors;

use Exception;
use hesabro\notif\interfaces\NotifInterface;
use hesabro\notif\models\Notif;
use hesabro\notif\Module;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use Yii;
use yii\helpers\Html;


/**
 * this behavior add to model when target model implements NotificationInterface
 */
class NotifBehavior extends Behavior
{
    /**
     * @var NotifInterface
     */
    public $owner;

    public array $scenario = [];

    public ?string $on = null;


    public function init()
    {
        parent::init();

        if (!($this->owner instanceof NotifInterface)) {
            throw new Exception($this->owner::class . ' must be instance of ' . NotifInterface::class);
        }
    }

    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'sendNotif',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'sendNotif',
        ];
    }

    public function sendNotif(): void
    {
        $users = $this->owner->notifUsers();
        $description = $this->owner->notifDescription();

        if (Module::getInstance()->enable && $this->owner->notifConditionToSend() && count($users)) {
            $title = $this->owner->notifTitle();

            $sendSms = (int) $this->owner->notifSmsConditionToSend();
            $smsDelay = $this->owner->notifSmsDelayToSend() ?: 0;

            $sendEmail = (int) $this->owner->notifEmailConditionToSend();
            $emailDelay = $this->owner->notifEmailDelayToSend() ?: 0;

            foreach ($users as $user) {
                $notif = new Notif([
                    'time' => time(),
                    'title' => $title,
                    'user_id' => $user,
                    'description' => $description,
                    'send_sms' => $sendSms,
                    'send_sms_delay' => $smsDelay,
                    'send_email' => $sendEmail,
                    'send_email_delay' => $emailDelay,
                    'create_by' => Yii::$app->user->id,
                    'slave_id' => Module::getInstance()->getClientId(),
                ]);

                if (!$notif->save()) {
                    Yii::error(Html::errorSummary($notif), 'Notif');
                }
            }
        }
    }
}
