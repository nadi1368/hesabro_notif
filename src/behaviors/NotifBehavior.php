<?php

namespace hesabro\notif\behaviors;

use Exception;
use hesabro\notif\interfaces\NotifInterface;
use hesabro\notif\models\Notif;
use hesabro\notif\models\NotifListener;
use hesabro\notif\models\NotifSetting;
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

    public ?string $event = null;


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
        if (!Module::getInstance()->enable || !$this->owner->notifConditionToSend()) {
            return;
        }

        /** @var NotifListener[] $listeners */
        $listeners = NotifListener::find()->where(['event' => $this->event])->all();

        $title = $this->owner->notifTitle();
        $description = $this->owner->notifDescription();
        $ownerSmsCondition = $this->owner->notifSmsConditionToSend();
        $smsDelay = $this->owner->notifSmsDelayToSend() ?: 0;
        $ownerEmailCondition = $this->owner->notifEmailConditionToSend();
        $emailDelay = $this->owner->notifEmailDelayToSend() ?: 0;
        $ownerTicketCondition = $this->owner->notifTicketConditionToSend();
        $ticketDelay = $this->owner->notifTicketDelayToSend() ?: 0;

        foreach ($listeners as $listener) {
            $users = $listener->userType === NotifListener::USER_DYNAMIC ? $this->owner->notifUsers() : $listener->users;

            foreach ($users as $user) {
                $notif = new Notif([
                    'time' => time(),
                    'title' => $title,
                    'user_id' => $user,
                    'description' => $description,
                    'send_sms' => $ownerSmsCondition && NotifSetting::canUserEvent($user, $listener->event, NotifSetting::TYPE_SMS, $listener->sms),
                    'send_sms_delay' => $smsDelay,
                    'send_email' => $ownerEmailCondition && NotifSetting::canUserEvent($user, $listener->event, NotifSetting::TYPE_EMAIL, $listener->email),
                    'send_email_delay' => $emailDelay,
                    'send_ticket' => $ownerTicketCondition && NotifSetting::canUserEvent($user, $listener->event, NotifSetting::TYPE_TICKET, $listener->ticket),
                    'send_ticket_delay' => $ticketDelay,
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
