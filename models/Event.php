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
            ['date', 'default', 'value' => null],
            [['date'], 'date', 'format' => 'php:d.m.Y'],
            [['description'], 'string'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date = $this->date ? \Yii::$app->formatter->asDate($this->date, 'php:Y-m-d') : null;

            return true;
        }

        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->date = $this->date ? \Yii::$app->formatter->asDate($this->date, 'php:d.m.Y') : '';
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

    public function eventsDataProvider()
    {
        return new ActiveDataProvider([
            'query' => self::find()->joinWith('organizers'),
        ]);
    }

    public static function findEvent(int $id): ?Event
    {
        return self::findOne($id);
    }

    public function linkOrganizers(array $postOrganizers): void
    {
        foreach ($postOrganizers as $i => $organizer) {
            $postOrganizers[$i] = (int) $organizer;
        }

        $organizers = Organizer::findById($postOrganizers);
        foreach ($organizers as $organizer) {
            $this->link('organizers', $organizer);
        }
    }
}
