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

<p>Seseorang mengirimkan / mengisi form Contact! Berikut detilnya:</p>

<div>
    <table>
        <colgroup>
            <col class="col-xs-1">
            <col class="col-xs-7">
        </colgroup>
        <thead>
        <tr>
            <th width="30%">Key</th>
            <th width="70%">Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row"><code>Name</code></th>
            <td><?= Html::encode($senderName); ?></td>
        </tr>
        <tr>
            <th scope="row"><code>Sender Email</code></th>
            <td><?= Html::encode($senderEmail); ?></td>
        </tr>
        <tr>
            <th scope="row"><code>Subject</code></th>
            <td><?= Html::encode($subjectMail); ?></td>
        </tr>
        <tr>
            <th scope="row"><code>Body Contact</code></th>
            <td><?= Html::encode($bodyMail); ?></td>
        </tr>
        </tbody>
    </table>
</div>

<p>Regards,</p>
<p>My Company</p>