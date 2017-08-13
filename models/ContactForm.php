<?php

namespace app\models;

use Pheanstalk\Pheanstalk;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            $emailDetails = [
                'recipientMail' => $email,
                'senderName' => $this->name,
                'senderEmail' => $this->email,
                'subjectMail' => $this->subject,
                'bodyMail' => $this->body
            ];

            $beanstalkInstance = new Pheanstalk(Yii::$app->params['beanstalkd']['host'], Yii::$app->params['beanstalkd']['port']);
            $sendJob = $beanstalkInstance->useTube('contact_tube')->put(json_encode($emailDetails));

            return $sendJob;
        }
        return false;
    }
}
