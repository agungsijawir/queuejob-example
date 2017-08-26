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

Selamat datang, <?= $recipientName; ?>! Profile anda telah diubah pada <?= date('Y-m-d H:i:s'); ?>.
Jika ini tidak dilakukan oleh Anda, segera hubungi contact support tim kami.

<p>Regards,</p>
<p>My Company</p>