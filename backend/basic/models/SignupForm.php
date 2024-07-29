<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $nombre;
    public $apellido;
    public $passwd;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'passwd'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['nombre', 'apellido'], 'string', 'max' => 50],
            [['passwd'], 'string', 'min' => 3],
            [['username'], 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->nombre = $this->nombre;
        $user->apellido = $this->apellido;
        $user->passwd = $this->passwd;

        return $user->save() ? $user : null;
    }
}
