<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Admin;
use yii\filters\AccessControl;
use app\models\LoginSendSmsForm;
use app\models\AccountLoginForm;
use app\models\PhoneLoginForm;
use app\models\UpdatePasswordForm;
use app\models\VerifyPasswordForm;
use app\models\ChangePhoneForm;
use yii\web\NotFoundHttpException;
use app\models\SendChangePhoneSmsForm;

/**
 * 操作用户相关的控制器
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'send-sms'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'send-sms'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['get', 'post'],
                    'logout' => ['get'],
                    'send-sms' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 个人资料的操作
     */
    public function actionProfile() {
        /* @var $model Admin */
        $model = Admin::findOne(Yii::$app->user->id);

        if (!$model) {
            throw new NotFoundHttpException('未找到该管理员。');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '个人资料修改成功.');
            } else {
                Yii::$app->session->setFlash('success', '个人资料修改失败.');
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    /**
     * 用户登录的操作
     */
    public function actionLogin() {
        $this->layout = 'base';

        return $this->render('login');
    }

    /**
     * 用户注销登录的操作
     */
    public function actionLogout() {
        if (Yii::$app->user->logout()) {
            return $this->redirect(['user/login']);
        } else {
            return $this->goBack();
        }
    }

    /**
     * 通过ajax发送登录的验证码的操作
     * @return array
     */
    public function actionSendLoginSms() {
        $model = new LoginSendSmsForm();
        $model->phone = Yii::$app->request->post('phone');

        if ($model->sendSms()) {
            return Json::encode(['status' => 'ok']);
        } else {
            $message = $model->getFirstError('phone');
            return Json::encode(['status' => 'err', 'message' => $message]);
        }
    }

    /**
     * 通过ajax进行手机号登录的操作
     * @return array
     */
    public function actionPhoneLogin() {
        $model = new PhoneLoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return $this->redirect(['site/index']);
        } else {
            $message = $model->getFirstError('code');
            return Json::encode(['status' => 'err', 'message' => $message]);
        }
    }

    /**
     * 通过ajax进行账户登录的操作
     * @return array
     */
    public function actionAccountLogin() {
        $model = new AccountLoginForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return $this->redirect(['site/index']);
        } else {
            $message = $model->getFirstError('password');
            return Json::encode(['status' => 'err', 'message' => $message]);
        }
    }

    /**
     * 修改密码的操作
     * @return array
     */
    public function actionUpdatePassword() {
        $model = new UpdatePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->updatePassword()) {
            Yii::$app->session->setFlash('success', '密码修改成功。');
            return $this->refresh();
        }

        return $this->render('update-password', [
            'model' => $model
        ]);
    }

    /**
     * 通过ajax发送修改手机号的验证码的操作
     * @return array
     */
    public function actionSendUpdatePhoneSms() {
        $model = new SendChangePhoneSmsForm();
        $model->phone = Yii::$app->request->post('phone');

        if ($model->sendSms()) {
            return Json::encode(['status' => 'ok']);
        } else {
            $message = $model->getFirstError('phone');
            return Json::encode(['status' => 'err', 'message' => $message]);
        }
    }

    /**
     * 修改手机号的操作
     * @param string $step
     * @return array
     */
    public function actionUpdatePhone($step = '1') {
        $params = ['step' => $step];

        if ($step === '1') {
            $verifyPasswordForm = new VerifyPasswordForm();

            if ($verifyPasswordForm->load(Yii::$app->request->post()) && $verifyPasswordForm->validate()) {
                Yii::$app->session['passwordVerified'] = true;

                return $this->redirect(['user/update-phone', 'step' => '2']);
            }

            /** @var $admin Admin */
            $admin = Yii::$app->user->identity;

            $params['phone'] = $admin->phone;
            $params['verifyPasswordForm'] = $verifyPasswordForm;
        } elseif ($step === '2' && Yii::$app->session->has('passwordVerified') && Yii::$app->session['passwordVerified']) {
            $changeMobileForm = new ChangePhoneForm();

            if ($changeMobileForm->load(Yii::$app->request->post()) && $changeMobileForm->change()) {
                Yii::$app->session->setFlash('success', '手机更换成功！');
                return $this->redirect(['user/update-phone']);
            }

            $params['changeMobileForm'] = $changeMobileForm;
        } else {
            return $this->redirect(['user/update-phone']);
        }

        return $this->render('update-phone', $params);
    }
}