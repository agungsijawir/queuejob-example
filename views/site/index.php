<?php

/* @var $this yii\web\View */

use app\models\User;
use yii\bootstrap\Alert;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?php if (Yii::$app->session->hasFlash('signup-info')): ?>
        <?= Alert::widget([
            'body' => Yii::$app->session->getFlash('signup-info'),
            'options' => [
                'class' => 'alert alert-info'
            ]
        ]); ?>
    <?php endif; ?>

    <div class="jumbotron">



        <?php if (Yii::$app->user->isGuest): ?>
            <h1>Welcome!</h1>
            <p class="lead">... and thank you for visiting our website. To continue, please login or signup!</p>

            <p><?= Html::a('Login to get started!', ['site/login'], ['class' => 'btn btn-lg btn-success']); ?></p>
        <?php else: ?>
            <h1>Congratulations!</h1>
            <?php if (Yii::$app->user->identity->status == User::STATUS_NOT_ACTIVATED): ?>
                <p>
                    You have created your account on this site. Please check your email (<code><?= Yii::$app->user->identity->email; ?></code>)
                    for instruction on how to activate your account.
                </p>
                <p>
                    <!-- I know this is bad using Username (too vulgar), but this is a demo only :p -->
                    Not receiving activation mail? <?= Html::a('Click here', ['site/resend-activation', 'username' => Yii::$app->user->identity->username]); ?> to re-send!
                </p>
            <?php else: ?>
                <p>Your account has been <span class="label label-success">activated</span>! Thank you for register on our site!</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->status == User::STATUS_ACTIVE): ?>
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
    <?php endif; ?>
</div>
