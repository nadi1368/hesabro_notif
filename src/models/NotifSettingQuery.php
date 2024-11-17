<?php

namespace hesabro\notif\models;

use yii\db\ActiveQuery;
use yii\db\Expression;

class NotifSettingQuery extends ActiveQuery
{
    public function whereUser(string|int $userId)
    {
        $this->andWhere(['user_id' => $userId]);

        return $this;
    }
}