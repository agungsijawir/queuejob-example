<?php

namespace app\controllers;

use app\models\ProfileForm;
use app\models\SignupForm;
use app\models\User;
use app\jobs\SignupMailJob;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('signup-info', "An activation mail has been sent to <code>{$model->email}</code>.");
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @param $code
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionActivation($code)
    {
        $model = User::findIdentityByAccessToken($code);

        if ($model) {
            $model->status = User::STATUS_ACTIVE;
            $model->update(false);

            Yii::$app->session->setFlash('success', "Your account has been activated successfully. Please login using your account.");
            return $this->redirect(['site/login']);
        } else {
            throw new NotFoundHttpException('Invalid activation code given or activation code was expired!');
        }
    }

    /**
     * Perform re-send activation mail.
     * @param $username
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionResendActivation($username)
    {
        $user = User::findByUsername($username);

        $queueSignup = Yii::$app->queueNotification;
        $resultJob = $queueSignup->push(new SignupMailJob([
            'username' => $user->username,
            'email' => $user->email,
            'auth_key' => $user->auth_key
        ]));

        if ($resultJob || $user) {
            Yii::$app->session->setFlash('success', "Activation mail has been sent to {$user->email}! " .
                "Please check your spam folder if not found on INBOX.");
            return $this->redirect(['site/index']);
        } else {
            throw new NotFoundHttpException('Invalid username');
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @return string|Response
     */
    public function actionProfile()
    {
        $model = new ProfileForm();
        $model->email = Yii::$app->user->identity->email;
        $model->username = Yii::$app->user->identity->username;

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->update()) {
                Yii::$app->session->setFlash('signup-info', "Profile updated.");
                return $this->goHome();
            }
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}
