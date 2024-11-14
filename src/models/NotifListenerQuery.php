<?php

namespace hesabro\notif\models;

use yii\db\ActiveQuery;

class NotifListenerQuery extends ActiveQuery
{
    public function whereUserType(string $userType)
    {
        $this->andWhere("JSON_EXTRACT(". NotifListener::tableName() .".additional_data, '$.userType') = JSON_QUOTE('$userType')");

        return $this;
    }

    public function andFilterUserType(?string $userType)
    {
        if ($userType) {
            $this->whereUserType($userType);
        }

        return $this;
    }
}