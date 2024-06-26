<?php

namespace app\modules\apiv1\controllers;

use yii\rest\ActiveController;
use yii\filters\Cors;

class BaseController extends ActiveController
{
    public static function allowedDomains()
    {
        return [$_SERVER['REMOTE_ADDR'], 'http://127.0.0.1:8080']; // Agregar la URL como variable en el .env
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Eliminar el filtro de autenticación si es necesario
        if (isset($behaviors['authenticator'])) {
            unset($behaviors['authenticator']);
        }

        // Agregar el filtro CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => static::allowedDomains(),
                'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE', 'GET', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600, // Cache (seconds)
                'Access-Control-Allow-Headers' => ['authorization', 'X-Requested-With', 'content-type'],
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Total-Count']
            ],
        ];

        // Re-agregar el filtro de autenticación si es necesario
        if (isset($behaviors['authenticator'])) {
            $behaviors['authenticator'] = $behaviors['authenticator'];
        }

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        // Manejar las solicitudes OPTIONS
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
        ];
        return $actions;
    }
}
