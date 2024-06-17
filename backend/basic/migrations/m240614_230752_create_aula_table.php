<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%aula}}`.
 */
class m240614_230752_create_aula_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%aula}}', [
            'id' => $this->primaryKey(),
            'descripcion' => $this->string(128)->notNull(),
            'ubicacion' => $this->string(128)->notNull(),
            'cant_pcs' => $this->integer()->defaultValue(0),
            'cant_proyector' => $this->integer()->defaultValue(0),
            'es_climatizada' => $this->boolean()->defaultValue(false),
            'aforo' => $this->integer()->defaultValue(0),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%aula}}');
    }
}
