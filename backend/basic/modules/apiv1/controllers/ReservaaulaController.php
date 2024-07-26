<?php

namespace app\modules\apiv1\controllers;

use Yii;
use app\modules\apiv1\models\ReservaAula;
use yii\web\NotFoundHttpException;
use yii\rest\ActiveController;

class ReservaaulaController extends BaseController
{
    public $modelClass = 'app\modules\apiv1\models\ReservaAula';

    public function actionMateriasReservadas($id)
    {
        $reservaAula = $this->findModel($id);

        $materiasReservadas = [];
        foreach ($reservaAula->horarioMaterias as $horarioMateria) {
            $materiasReservadas[] = $horarioMateria->materia;
        }

        return $this->asJson($materiasReservadas);
    }

    protected function findModel($id)
    {
        if (($model = ReservaAula::find()
            ->with('horarioMaterias.materia')
            ->where(['id' => $id])
            ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new ReservaAula();
        $model->load(Yii::$app->request->post(), '');
        
        // Verificar que no exista una reserva en el mismo horario
     //  $conflict = ReservaAula::find()
       //     ->where(['id_aula' => $model->id_aula])
        //    ->andWhere(['<=', 'fh_desde', $model->fh_hasta])
        //    ->andWhere(['>=', 'fh_hasta', $model->fh_desde])
          //  ->exists();

       // if ($conflict) {
       //     throw new \yii\web\ConflictHttpException('El aula ya está reservada en este horario.');
       // }

        if ($model->save()) {
            return $model;
        } else {
            return $model->errors;
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post(), '');
        
        // Verificar que no exista una reserva en el mismo horario
      //  $conflict = ReservaAula::find()
      //      ->where(['id_aula' => $model->id_aula])
      //      ->andWhere(['<>', 'id', $id])
      //      ->andWhere(['<=', 'fh_desde', $model->fh_hasta])
      //      ->andWhere(['>=', 'fh_hasta', $model->fh_desde])
      //      ->exists();

       // if ($conflict) {
        //    throw new \yii\web\ConflictHttpException('El aula ya está reservada en este horario.');
       // }

        if ($model->save()) {
            return $model;
        } else {
            return $model->errors;
        }
    }
}
