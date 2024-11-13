<?php

namespace hesabro\notif\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class NotifSearch extends MGNotification
{
    public $fromDate;
    public $toDate;


    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [['fromDate', 'toDate'], 'string'],
        ]);
    }

    public function attributes(): array
    {
        return parent::attributes() + ['fromDate', 'toDate'];
    }

    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'fromDate' => 'از تاریخ',
            'toDate' => 'تا تاریخ'
        ];
    }

    public function search($params)
    {
        $query = parent::find();

        $query->orderBy(['time' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->fromDate) {
            $query->andWhere([
                '>=',
                'time',
                strtotime(Yii::$app->jdf::Convert_jalali_to_gregorian($this->fromDate))
            ]);
        }

        if ($this->toDate) {
            $query->andWhere([
                '<=',
                'time',
                strtotime(Yii::$app->jdf::Convert_jalali_to_gregorian($this->toDate))
            ]);
        }

        if ($this->user_id) {
            $query->andFilterWhere(['user_id' => (int)$this->user_id]);
        }

        $query->andFilterWhere([
            'slave_id' => (int) \Yii::$app->client->id,
            'type' => $this->type ? (int)$this->type : $this->type,
        ]);

        return $dataProvider;
    }
}
