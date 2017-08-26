<?php
namespace app\models;

use app\jobs\NotificationJob;
use Yii;
use yii\base\Model;
use yii\queue\Queue;

/**
 * Class ProfileForm
 *
 * @package app\models
 */
class ProfileForm extends Model
{
    public $username;
    public $email;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->username = Yii::$app->user->identity->username;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }
    /**
     * Update user profile
     *
     * @return User|null the saved model or null if saving fails
     */
    public function update()
    {
        if ($this->validate()) {
            $user = User::findByUsername($this->username);
            $user->email = $this->email;
            $result = $user->save();

            if ($result) {
                /**
                 * @var Queue $queueProfileUpdated
                 */
                $queueProfileUpdated = Yii::$app->queueNotification;
                $resultJob = $queueProfileUpdated->push(new NotificationJob([
                    'username' => $user->username,
                    'email' => $user->email
                ]));
            }

            return $user;
        }
        return null;
    }
}