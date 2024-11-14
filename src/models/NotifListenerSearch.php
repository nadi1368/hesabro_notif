<?php

namespace hesabro\notif\models;

use hesabro\notif\Module;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotifListenerSearch extends NotifListener
{
    public function rules()
    {
        return [
            [['title', 'event', 'userType'], 'string'],
            ['userType', 'in', 'range' => [self::USER_DYNAMIC, self::USER_STATIC]],
            ['event', 'in', 'range' => Module::getInstance()->eventsKey],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($queryParams): ActiveDataProvider
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($queryParams);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterUserType($this->userType);
        $query->andFilterWhere([
            'title' => $this->title,
            'event' => $this->event,
        ]);

        return $dataProvider;
    }
}