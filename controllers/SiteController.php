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

    public function actionCreateUser($arr)
    {
        $user = new User();
        $user->email=$arr['email'];
//        var_dump($email);die;
        $user->hash=md5($arr['email']);
        $user->tmp_hash=md5($arr['tmp_hash']);
        if($user->save()){
            $model = new LoginForm();
            $model->email = $arr['email'];
            $model->login();
            return $this->render('/users/update', compact(['user']));
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
                $tmp_hash = md5(date('Y.m.d H:s').$model->email);
                $message = 'Для входа нажмите <a href="'.$tmp_hash.'">Войти</a>';
                Yii::$app->mailer->compose()
                ->setFrom(['test@iro.51' => 'test'])
                    ->setTo($model->email)
                    ->setSubject('Вход на сайт')
                    ->setTextBody($message)
                    ->setHtmlBody($message)
                    ->send();

                $arr=[
                    'email'=>$model->email,
                    'tmp_hash'=>$tmp_hash
                ];

//                $this->actionCreateUser($arr);
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
