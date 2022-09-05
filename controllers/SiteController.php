<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends AppController
{


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateUser($email)
    {
        $user = new User();
        $user->email=$email;
//        var_dump($email);die;
        $user->hash=md5($email);
        if($user->save()){
            $model = new LoginForm();
            $model->email = $email;
            $model->login();
            return $this->render('/users/update', compact(['model']));
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                return $this->redirect('/users/update');
            }else{
                $this->actionCreateUser($model->email);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
