<?php

namespace app\modules\auth\controllers;

use yii\filters\Cors;
use yii\rest\ActiveController;

class BaseController extends ActiveController
{
    public static function allowedDomains()
    {
        return [$_SERVER["REMOTE_ADDR"], 'http://127.0.0.1:8080']; //todo: Agregar la url como variable en el .env
    }

    function behaviors()
    {
        $behaviors = parent::behaviors();

        return array_merge($behaviors, [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['POST', 'GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Allow-Headers' => ['authorization', 'X-Requested-With', 'content-type'],
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Total-Count']
                ]
            ]
        ]);
    }
    public function beforeAction($action){
        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }
}