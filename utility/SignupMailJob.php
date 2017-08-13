<?php
/**
 * Created by PhpStorm.
 * User: agung
 * Date: 8/13/17
 * Time: 9:11 PM
 */

namespace app\utility;


use Yii;
use yii\base\Object;
use yii\helpers\Html;
use yii\queue\Job;
use yii\queue\Queue;

class SignupMailJob extends Object implements Job
{
    /**
     * @var string Username Recipient
     */
    public $username;
    /**
     * @var string Email Recipient
     */
    public $email;
    /**
     * @var string Auth Key for Activation Code
     */
    public $auth_key;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        echo "[INFO] Executing Job for {$this->username} / email {$this->email}" . PHP_EOL;

        $mailData = [
            'recipientMail' => $this->email,
            'recipientName' => $this->username,
            'senderName' => Yii::$app->name . ' No-Reply',
            'senderEmail' => Yii::$app->params['adminEmail'],
            'subjectMail' => 'Activation Account for user ' . $this->username,
            'titleMail' => 'Activation Account for user ' . $this->username,
            'activationLink' => Html::a('Activation Link', ['site/activation', 'code' => $this->auth_key]),
        ];

        $mailerInstance = Yii::$app->mailer->compose('user/signup', $mailData) // @app/mail/user/signup
            ->setTo($mailData['recipientMail'])
            ->setFrom([$mailData['senderEmail'] => $mailData['senderName']])
            ->setSubject($mailData['subjectMail'])
            ->setTextBody($mailData['activationLink'])
            ->send();

        if ($mailerInstance) {
            echo "[INFO] Email sent for {$this->username} / email {$this->email} !" . PHP_EOL;
        } else {
            echo "[ERROR] Failed to send email for {$this->username} / email {$this->email} !" . PHP_EOL;
        }
    }
}