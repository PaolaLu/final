<?php

use yii\db\Migration;
use app\models\User;

/**
 * Class m240709_205125_add_new_users
 */
class m240709_205125_add_new_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = "admin";
        $user->nombre = "Administrador";
        $user->email = "admin@curza.edu.ar";
        $user->passwd = "clave1";
        $user->save();

        $user1 = new User();
        $user1->username = "aulas";
        $user1->nombre = "Administrador Aulas";
        $user1->email = "aulas@curza.edu.ar";
        $user1->passwd = "aulas1";
        $user1->save();

        $user2 = new User();
        $user2->username = "invitado";
        $user2->nombre = "invitado Aulas";
        $user2->email = "invitado@curza.edu.ar";
        $user2->passwd = "invitado1";
        $user2->save();

        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        User::deleteAll(['username'=> "admin"]);
        User::deleteAll(['username'=> "aulas"]);
        User::deleteAll(['username'=> "invitado"]);
        return true;
    }

  
}
