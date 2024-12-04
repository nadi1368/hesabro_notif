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
            [['title', 'event', 'userType', 'group'], 'string'],
            ['userType', 'in', 'range' => [self::USER_DYNAMIC, self::USER_STATIC]],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($queryParams, ?string $group = null): ActiveDataProvider
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
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['event' => $this->event]);
        $query->andFilterWhere(['group' => $this->group ?: $group]);

        return $dataProvider;
    }
}