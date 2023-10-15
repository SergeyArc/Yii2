<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Organizer extends ActiveRecord
{
    public static function tableName()
    {
        return 'organizer';
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'required', 'message' => 'Поле является обязательным'],
            [['name'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
        ];
    }

    public function getEvents()
    {
        return $this->hasMany(Event::class, ['id' => 'event_id'])
            ->viaTable('event_organizer', ['organizer_id' => 'id']);
    }

    public function organizersDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => self::find(),
        ]);
    }

    public static function findOrganizer(int $id): ?Organizer
    {
        return self::findOne($id);
    }

    public static function allOrganizers(): array
    {
        return self::find()->all();
    }

    public static function findById($id): array
    {
        return self::findAll($id);
    }
}
