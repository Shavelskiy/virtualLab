<?php

namespace frontend\controllers;

use common\models\Lab;
use common\models\LabResults;
use common\models\Student;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotAcceptableHttpException;

class ProfileController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['student'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->can('viewAdminPage')) {
            throw new ForbiddenHttpException('У вас нет доступа к этой странице');
        }

        $student = Student::find()->andWhere(['user_id' => Yii::$app->user->id])->one();

        $studentLabs = [];

        foreach (Lab::find()->all() as $lab) {
            $finish = false;

            /** @var LabResults $studentLab */
            if ($studentLab = $student->labs->{'lab' . $lab->id}) {
                $attempts = $studentLab->attempts;
                if ($studentLab->success) {
                    $finish = true;
                    $status = 'Выполнено';
                } else {
                    $status = 'В процессе';
                }
            } else {
                $status = 'Не начато';
                $attempts = 0;
            }

            $studentLabs[] = [
                'lab_id' => $lab->id,
                'status' => $status,
                'date_create' => $finish ? date('d.m.Y H:i', $studentLab->created_at) : null,
                'href' => $finish ? $studentLab->file_path : null,
                'attempts' => $attempts
            ];
        }

        return $this->render('index', [
            'model' => $student,
            'studentLabs' => $studentLabs
        ]);
    }
}
