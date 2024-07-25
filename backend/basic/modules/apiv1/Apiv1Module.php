<?php

namespace app\modules\apiv1;

/**
 * apiv1 module definition class
 */
class Apiv1Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\apiv1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->urlManager->addRules([
            'GET apiv1/reservaaula/materias-reservadas/<id:\d+>' => 'apiv1/reservaaula/materias-reservadas',
        ], false);
      
    }
}
