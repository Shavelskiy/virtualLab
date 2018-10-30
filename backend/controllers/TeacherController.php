<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\Student;
use common\models\Teacher;
use common\models\Group;
use backend\models\TeacherForm;
use backend\models\SignupForm;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['teacher']
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Teacher::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $teacher = $this->findTeacher($id);
        $teacherGroups = Group::getTeacherGroups($id);
        $groupStudents = [];
        foreach ($teacherGroups as $group) {
            $groupStudents[$group->name]  = Student::getTeacherStudents($group->id, $id);
        }

        return $this->render('view', [
            'model' => $teacher,
            'groupStudents' => $groupStudents
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $signUpForm = new SignupForm();
        $teacher = new Teacher();

        if (($signUpForm->load(Yii::$app->request->post())) && $signUpForm->validate()) {
                if (($teacher->load(Yii::$app->request->post())) && $teacher->validate()) {
                    $user = $signUpForm->signup();
                    $teacher->user_id = $user->id;
                    $teacher->save();

                    $auth = Yii::$app->authManager;
                    $teacherRole = $auth->getRole('teacher');
                    $auth->assign($teacherRole, $user->id);
                }
                return $this->redirect(['view', 'id' => $teacher->id]);
        }

        return $this->render('create', [
            'signUpForm' => $signUpForm,
            'teacher' => $teacher
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $teacher = $this->findTeacher($id);
        $model = new TeacherForm();
        $model->loadTeacher($teacher);

        if ($model->load(Yii::$app->request->post())) {
            $model->update();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $teacher = $this->findTeacher($id);
        $teacher->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Teacher|null
     * @throws NotFoundHttpException
     */
    protected function findTeacher($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Такой страницы не существует');
    }
}
