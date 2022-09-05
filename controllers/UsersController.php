<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;



class UsersController extends AppController
{

    public function actionUpdate()
    {
        Yii::$app->getView()->params['title'] = "Личный кабинет пользователя ".Yii::$app->user->identity->username ;
        $model = User::findOne(Yii::$app->user->identity->id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->refresh();
            }
        }

        return $this->render('update',compact(['model']));
    }

}
