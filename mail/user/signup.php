<?php
/**
 * Email view for sign-up
 *
 * @var string $titleMail
 * @var string $content
 * @var string $senderName
 * @var string $senderEmail
 * @var string $subjectMail
 * @var string $bodyMail
 */
use yii\helpers\Html;

?>
<h2><?= $titleMail; ?></h2>

Selamat datang, <?= $recipientName; ?>! Silakan klik tautan berikut <?= $activationLink; ?> untuk melakukan aktivasi akun Anda.

<p>Regards,</p>
<p>My Company</p>