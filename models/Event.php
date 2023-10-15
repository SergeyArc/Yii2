<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Event extends ActiveRecord
{
    public static function tableName()
    {
        return 'event';
    }

    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'Поле является обязательным'],
            [['title'], 'string', 'max' => 100],
            [['date'], 'date'],
            [['description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'date' => 'Дата проведения',
            'description' => 'Описание мероприятия',
        ];
    }

    public function getOrganizers()
    {
        return $this->hasMany(Organizer::class, ['id' => 'organizer_id'])
            ->viaTable('event_organizer', ['event_id' => 'id']);
    }

    public function allEvents()
    {
        return new ActiveDataProvider([
            'query' => self::find()->joinWith('organizers'),
        ]);
    }
}
