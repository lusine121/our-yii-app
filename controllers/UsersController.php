<?php

namespace app\controllers;

use Yii;
use app\models\Users;
//use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\SendEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AccountActivation;
use app\models\Profile;


/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends BehaviorsController
{

    //public function behaviors()
    //{
        //return [

            //'verbs' => [
                //'class' => VerbFilter::className(),
                //'actions' => [
                    //'delete' => ['POST'],
                //],
            //],
        //];
    //}

    /**
     * Lists all Users models.
     * @return mixed
     */

    public function actionIndex()
    {
        $hello = 'Привет МИР!!!';
        return $this->render('index', [
            'hello' => $hello,
        ]);
    }


    public function actionSearch($search = null)
    {
        if($search):
            Yii::$app->session->setFlash(
                'success',
                'Результат поиска'
            );
        else:
            Yii::$app->session->setFlash(
                'error',
                'Не заполнена форма поиска'
            );
        endif;

        return $this->render('search', [
            'search' => $search,
        ]);
    }



    public function actionProfile()
    {
        $model = ($model = Profile::findOne(Yii::$app->user->id)) ? $model : new Profile();
        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if ($model->updateProfile()):
                Yii::$app->session->setFlash('success', 'Профиль изменен');
				return $this->goHome();
            else:
                Yii::$app->session->setFlash('error', 'Профиль не изменен');
                Yii::error('Ошибка записи. Профиль не изменен');
                return $this->refresh();
            endif;
        endif;
        return $this->render('profile',[
                'model' => $model
            ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionRegister()
    {
        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new RegisterForm(['scenario' => 'emailActivation']) : new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if ($user = $model->register()):
                if ($user->status === Users::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($user)):
                        return $this->goHome();
                    endif;
                else:
                    if ($model->sendActivationEmail($user)):
                        Yii::$app->session->setFlash('success', 'Письмо с активацией отправлено на емайл <strong>' . Html::encode($user->email) . '</strong> (проверьте папку спам).');
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'register',
            [
                'model' => $model
            ]
        );

    }



    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    public function actionActivateAccount($key)
    {
        try {
            $user = new AccountActivation($key);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно. 
            <strong>' . Html::encode($user->username) . '</strong> вы теперь с  Yii!!!');
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
        endif;

        return $this->redirect(Url::to(['/site/login']));
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;



        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->goBack();
        endif;
        return $this->render('/site/login', [
            'model' => $model
        ]);
    }


    public function actionSendEmail()
    {

        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail()):
                    Yii::$app->session->setFlash('warning', 'Проверьте емейл. ');
                    return $this->render('/users/sendEmail', [
                        'model' => $model
                    ]);
                else:
                    Yii::$app->session->setFlash('error', 'Нельзя сбросить пароль. ');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }


//    public function actionResetPassword($key)
//    {
//
//
//        if (!is_null($key)):
//            try {
//
//                $model = new ResetPasswordForm(); ////создаем новый обект ResetPasswordForm.Перед созданием запуститься конструктор в модели ResetPasswordForm и проверит ключ,если в ключе ошибка будет вызвано исключение InvalidParamException
//
//            } catch (InvalidParamException $e) { //если есть исключение InvalidParamException
//
//                throw new BadRequestHttpException($e->getMessage()); //вызываем исключение BadRequestHttpException 'плохой запрос' с сообщением исключения InvalidParamException из конструктора
//
//            }
//
//
//            if ($model->load(Yii::$app->request->post())) {
//                if ($model->validate() && $model->resetPassword()) {
//
//                    Yii::$app->session->setFlesh('warning', 'Пароль изменен.'); //выводим сообщение
//
//                    return $this->redirect(['/login']); //переходим на страницу входа пользователя
//
//                }
//
//            }
//
//            return $this->render('resetPassword', [
//                'model' => $model,
//            ]);
//        endif;
//
//    }

    public function actionResetPassword()
    {

        try {
            $model = new resetPasswordForm();
        } catch (InvalidParamException $e) {
            throw new \yii\web\BadRequestHttpException($e->getMessage());
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            \Yii::$app->session->setFlash('success', 'Password Changed!');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}







