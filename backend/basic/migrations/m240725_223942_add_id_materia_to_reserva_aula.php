<?php

use yii\db\Migration;

/**
 * Class m240725_223942_add_id_materia_to_reserva_aula
 */
class m240725_223942_add_id_materia_to_reserva_aula extends Migration
{
  /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add the column id_materia to reserva_aula table
        $this->addColumn('{{%reserva_aula}}', 'id_materia', $this->integer()->notNull());

        // Create an index for the new column
        $this->createIndex(
            '{{%idx-reserva_aula-id_materia}}',
            '{{%reserva_aula}}',
            'id_materia'
        );

        // Add foreign key for the new column
        $this->addForeignKey(
            '{{%fk-reserva_aula-id_materia}}',
            '{{%reserva_aula}}',
            'id_materia',
            '{{%materia}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key for table `{{%materia}}`
        $this->dropForeignKey(
            '{{%fk-reserva_aula-id_materia}}',
            '{{%reserva_aula}}'
        );

        // Drop index for column `id_materia`
        $this->dropIndex(
            '{{%idx-reserva_aula-id_materia}}',
            '{{%reserva_aula}}'
        );

        // Drop the column id_materia
        $this->dropColumn('{{%reserva_aula}}', 'id_materia');
    }
}
