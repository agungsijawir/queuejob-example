<?php

namespace app\jobs;
use Yii;
use yii\base\Object;
use yii\queue\RetryableJob;

/**
 * Class NotificationJob.
 */
class NotificationJob extends Object implements RetryableJob
{
    public $username;

    public $email;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        echo "[INFO] Executing Job for {$this->username} / email {$this->email}" . PHP_EOL;

        $mailData = [
            'recipientMail' => $this->email,
            'recipientName' => $this->username,
            'senderName' => Yii::$app->name . ' No-Reply',
            'senderEmail' => Yii::$app->params['adminEmail'],
            'subjectMail' => 'Notification Account for user ' . $this->username,
            'titleMail' => 'Notification Account for user ' . $this->username
        ];

        $mailerInstance = Yii::$app->mailer->compose('user/notification', $mailData) // @app/mail/user/signup
        ->setTo($mailData['recipientMail'])
            ->setFrom([$mailData['senderEmail'] => $mailData['senderName']])
            ->setSubject($mailData['subjectMail'])
            ->setTextBody('')
            ->send();

        if ($mailerInstance) {
            echo "[INFO] Email sent for {$this->username} / email {$this->email} !" . PHP_EOL;
        } else {
            echo "[ERROR] Failed to send email for {$this->username} / email {$this->email} !" . PHP_EOL;
        }
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}


