<?php
namespace app\models;

use app\jobs\SignupMailJob;
use Yii;
use yii\base\Model;
use yii\queue\Queue;

/**
 * Class SignupForm
 *
 * @package app\models
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $result = $user->save();

            if ($result) {
                /**
                 * @var Queue $queueSignup
                 */
                $queueSignup = Yii::$app->queueNotification;
                $resultJob = $queueSignup->push(new SignupMailJob([
                    'username' => $user->username,
                    'email' => $user->email,
                    'auth_key' => $user->auth_key
                ]));
            }

            return $user;
        }
        return null;
    }
}