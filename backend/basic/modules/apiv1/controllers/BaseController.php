<?php

namespace app\modules\apiv1\controllers;

use yii\rest\ActiveController;
use yii\filters\Cors;


class BaseController extends ActiveController
{
    public static function allowedDomains()
    {
        return [$_SERVER["REMOTE_ADDR"], 'http://127.0.0.1:8080']; //todo: Agregar la url como variable en el .env
    }

    function behaviors()
    {
        $behaviors = parent::behaviors();

        array_merge($behaviors, [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                   'Origin' => static::allowedDomains(),
                     'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    
                    'Access-Control-Allow-Headers' => ['authorization', 'X-Requested-With', 'content-type'],
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Total-Count']
                ]
            ]
        ]);
        return $behaviors;
    }
}