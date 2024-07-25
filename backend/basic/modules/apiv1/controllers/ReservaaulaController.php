<?php

namespace app\modules\apiv1\controllers;
use Yii;
use app\modules\apiv1\controllers\BaseController;
use yii\web\NotFoundHttpException;
use app\modules\apiv1\models\ReservaAula;
/**
 * Default controller for the `apiv1` module
 */
class ReservaaulaController extends BaseController
{
      public $modelClass='app\modules\apiv1\models\ReservaAula';

   

      public function actionMateriasReservadas($id)
    {
        $reservaAula = $this->findModel($id);

        // Obtén todas las materias reservadas para esa aula en un horario específico
        $materiasReservadas = [];
        foreach ($reservaAula->horarioMaterias as $horarioMateria) {
                 // Accede a la materia a través de la relación
                  $materia = $horarioMateria->getMateria()->one();
                  if ($materia) {
                        $materiasReservadas[] = $materia;
                  }
        }

        // Devuelve los datos como JSON
        return $this->asJson($materiasReservadas);
    }

    protected function findModel($id)
    {
        if (($model = ReservaAula::find()
            ->with('horarioMaterias.materia')  // Eager load the related Materia
            ->where(['id' => $id])
            ->one()) !== null) {
            return $model;
        }
    
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
