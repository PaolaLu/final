<?php

namespace app\modules\auth\controllers;
use Yii;
use app\modules\auth\controllers\BaseController;
use app\models\User;

/**
 * Default controller for the `auth` module
 */
class LoginController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request=Yii::$app->request;

        $user=User::findByUsername($request->post('username'));

        if($user){
            if($user->validatePassword($request->post('passwd'))){
                return [
                    "username"=>$user->username,
                    "accessToken"=>$user->accessToken,
                    "nombre"=>$user->nombre,
                    "apellido"=>$user->apellido,

                ];
            }
         }
         Yii::$app->response->statusCode=402;
         return;
}
}