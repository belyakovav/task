<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static function tableName ()
    {
        return 'user';
    }

    public function rules()
    {
      return [
              [['email','hash'], 'safe'],
              ['username', 'trim']
      ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findByUserHash($email)
    {
        return static::findOne(['hash'=>md5($email)]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
