<?php

namespace hesabro\notif\models;

use hesabro\notif\Module;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotifSearch extends Notif
{
    public function rules()
    {
        return [
            ['user_id', 'integer'],
            ['title', 'string'],
            ['seen', 'boolean'],
            ['user_id', 'exist', 'targetClass' => Module::getInstance()->user, 'targetAttribute' => ['user_id' => 'id']]
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

        $query->andFilterWhere([
            'title' => $this->title,
            'user_id' => $this->user_id,
            'seen' => $this->seen,
        ]);

        return $dataProvider;
    }
}
