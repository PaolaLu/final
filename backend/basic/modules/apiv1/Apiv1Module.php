<?php

namespace app\modules\apiv1;

use yii\filters\auth\HttpBearerAuth;

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

        \Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

}
