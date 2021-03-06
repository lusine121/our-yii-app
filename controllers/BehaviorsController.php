<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 30.06.2015
 * Time: 5:48
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\MyBehaviors;

class BehaviorsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'actions' => ['register', 'login', 'activate-account'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?'] //hyur  e user@
                    ],

                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'actions' => ['profile'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@']
                    ],

                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],

                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'actions' => ['index', 'search', 'send-email', 'reset-password']
                    ],

                ]
            ],

            'removeUnderscore' => [
                'class' => MyBehaviors::className(),
                'controller' => Yii::$app->controller->id,
                'action' => Yii::$app->controller->action->id,
                'removeUnderscore' => Yii::$app->request->get('search')
            ]
        ];

    }
}