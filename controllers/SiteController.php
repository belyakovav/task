<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
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
        $tmp_hash = md5(date('Y.m.d H:s').$email);

        $user = User::findOne(['email'=>$email]);

        if(!$user){
                $user = new User();
                $user->email=$email;
            }

        $user->tmp_hash=$tmp_hash;

        if($user->save()){

            $message = 'Для входа нажмите <a href=http://localhost/site/login?hash='.$tmp_hash.'>Войти</a>';
                Yii::$app->mailer->compose()
                    ->setFrom(['test@iro.51' => 'test'])
                    ->setTo($email)
                    ->setSubject('Вход на сайт')
                    ->setTextBody($message)
                    ->setHtmlBody($message)
                    ->send();
            return $this->redirect(Url::to(['site/index']));
            }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if(!empty($_GET['hash'])){
            $user_in = User::findOne(['tmp_hash'=>$_GET['hash']]);
            if(!empty($user_in)){
                $user_in->hash = md5($user_in->email);
                $user_in->save();
                $auth = new LoginForm();
                $auth->email = $user_in->email;
                $auth->login();
                return $this->redirect(Url::to(['/users/update']));
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                return $this->redirect(Url::to(['/users/update']));
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
