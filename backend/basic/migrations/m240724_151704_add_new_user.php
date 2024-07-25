<?php

use yii\db\Migration;
use app\models\User;
/**
 * Class m240724_151704_add_new_user
 */
class m240724_151704_add_new_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = "pao";
        $user->nombre = "pao";
        $user->email = "admin@curza.edu.ar";
        $user->passwd = "1";
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        User::deleteAll(['username'=> "pao"]);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240724_151704_add_new_user cannot be reverted.\n";

        return false;
    }
    */
}
