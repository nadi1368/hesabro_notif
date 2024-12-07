<?php

namespace hesabro\notif\models;

use Yii;
use yii\mongodb\ActiveQuery;

/**
 * This is the ActiveQuery class for [[MGNotification]].
 *
 * @see MGNotification
 */
class NotifQuery extends ActiveQuery
{
    public function own(): NotifQuery
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

    public function unseen(): NotifQuery
    {
        return $this->andWhere(['seen' => false]);
    }
}