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
    const EXIT_SUCCESS = 0;
    const EXIT_BEANSTALKD_ERROR = 10;
    const EXIT_BEANSTALKD_WORKER_ERROR = 11;

    /**
     * @var null|Pheanstalk
     */
    private $_pheanstalk = null;
    /**
     * @var bool|false|true
     */
    private $_pheanstalk_connection = false;
    /**
     * @var int
     */
    private $_worker_error_job_limit = 0;

    /**
     * WorkerController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param array $config
     */
    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->_pheanstalk = new Pheanstalk(
            Yii::$app->params['beanstalkd']['host'],
            Yii::$app->params['beanstalkd']['port']
        );

        $this->_pheanstalk_connection = $this->_pheanstalk->getConnection()->isServiceListening();

        $this->_worker_error_job_limit = Yii::$app->params['beanstalkd']['errorJobsLimit'];
    }

    /**
     * Worker to Send Mailer Contact. Implementation using pda/pheanstalkd library.
     */
    public function actionMailerContact()
    {
        $failedJob = 0;

        // jika koneksi ke beanstalkd server gagal, exit
        if ($this->_pheanstalk_connection == false) {
            $this->writeOutput("No beanstalkd service running. Please check and try again!", "ERROR");
            exit(self::EXIT_BEANSTALKD_ERROR);
        }

        $this->writeOutput("Worker Mailer Contact started!", "INFO");

        // fokus membaca job di tube 'contact_tube'
        $bean = $this->_pheanstalk
            ->watch('contact_tube') // name of tube
            ->ignore('default'); // ignore this tube

        // proses tube!
        while ($job = $bean->reserve()) {

            // ambil job
            $jobData = $job->getData();

            if ($jobData == null) {
                // jika data null, hapus job!
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
                    $this->writeOutput("Email Contact from ({$mailData['senderName']} [{$mailData['senderEmail']}]) sent!", "INFO");
                } else {
                    $this->writeOutput("Fail to Send Email Contact from ({$mailData['senderName']} [{$mailData['senderEmail']}])!", "ERROR");
                    $failedJob++;
                }

                // hapus job dari tube setelah proses selesai
                $this->_pheanstalk->delete($job);

                // jika worker gagal memproses job lebih dari limit (default 5x), maka lakukan exit worker!
                // dan biarkan supervisord me-restart worker!
                if ($failedJob > $this->_worker_error_job_limit) {
                    exit(self::EXIT_BEANSTALKD_WORKER_ERROR);
                }
            }
            // relax dulu untuk 5 detik
            sleep(5);
        }
    }

    /**
     * Write debug output to screen
     * @param $message
     * @param $type
     */
    private function writeOutput($message, $type) {
        echo "[" . $type . "] " . date('Y-m-d H:i:s') . " - {$message}" . PHP_EOL;
    }
}