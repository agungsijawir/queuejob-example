<?php

namespace app\commands;

use Pheanstalk\Pheanstalk;
use Yii;
use yii\base\Module;
use yii\console\Controller;

/**
 * Class WorkerController
 *
 * @package app\commands
 */
class WorkerController extends Controller
{
    private $_pheanstalk = null;
    private $_pheanstalk_connection = false;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->_pheanstalk = new Pheanstalk(
            Yii::$app->params['beanstalkd']['host'],
            Yii::$app->params['beanstalkd']['port']
        );

        $this->_pheanstalk_connection = $this->_pheanstalk->getConnection()->isServiceListening();
    }

    /**
     * Worker to Send Mailer Contact
     */
    public function actionMailerContact()
    {
        echo "[INFO] Worker Mailer Contact started!" . PHP_EOL;

        do {
            $job = $this->_pheanstalk
                ->watch('contact_tube')
                ->ignore('default')
                ->reserve();

            // getting job
            $jobData = $job->getData();

            if ($jobData == null) {
                // null data, delete the job!
                $this->_pheanstalk->delete($job);
            } else {
                $mailData = json_decode($jobData, true, 512, JSON_OBJECT_AS_ARRAY);

                $mailerInstance = Yii::$app->mailer->compose()
                    ->setTo($mailData['recipientMail'])
                    ->setFrom([$mailData['senderEmail'] => $mailData['senderName']])
                    ->setSubject($mailData['subjectMail'])
                    ->setTextBody($mailData['bodyMail'])
                    ->send();

                if ($mailerInstance) {
                    echo "[INFO] Email Contact from ({$mailData['senderName']} [{$mailData['senderEmail']}]) sent!" . PHP_EOL;
                } else {
                    echo "[WARNING] Fail to Sent Email Contact from ({$mailData['senderName']} [{$mailData['senderEmail']}]) sent!" . PHP_EOL;
                }

                // delete the job after send
                $this->_pheanstalk->delete($job);
            }
            // relax for 5 seconds
            sleep(5);
        } while ($this->_pheanstalk_connection);
    }
}