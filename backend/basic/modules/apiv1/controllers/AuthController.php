<?php

namespace app\modules\apiv1\controllers;

use app\modules\apiv1\controllers\BaseController;

use Yii;
use app\models\User;
use app\models\LoginForm;
use app\models\SignupForm;
/**
 * Default controller for the `apiv1` module
 */
class AuthController extends BaseController
{
      public $modelClass='app\modules\apiv1\models\User';


      public function actionLogin()
      {
          $model = new LoginForm();
          if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
              return [
                  'success' => true,
                  'user' => [
                      'id' => Yii::$app->user->identity->id,
                      'username' => Yii::$app->user->identity->username,
                  ],
              ];
          } else {
              Yii::$app->response->statusCode = 401;
              return [
                  'success' => false,
                  'errors' => $model->errors,
              ];
          }
      }
  
      public function actionSignup()
      {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->signup()) {
            return [
                'success' => true,
                'message' => 'Nuevo usuario registrado exitosamente.',
            ];
        } else {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'errors' => $model->errors,
            ];
        }
      }
  
      public function actionLogout()
      {
          Yii::$app->user->logout();
          return [
              'success' => true,
              'message' => 'Salida exitosa !',
          ];
      }
  

}
