<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style type="text/css">
        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: transparent;
            border-spacing: 0;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        table > caption + thead > tr:first-child > td,
        table > caption + thead > tr:first-child > th,
        table > colgroup + thead > tr:first-child > td,
        table > colgroup + thead > tr:first-child > th,
        table > thead:first-child > tr:first-child > td,
        table > thead:first-child > tr:first-child > th {
            border-top: 0;
        }

        table > thead > tr > td,
        table > thead > tr > th {
            border-bottom-width: 2px;
        }

        table > tbody > tr > td,
        table > tbody > tr > th,
        table > tfoot > tr > td,
        table > tfoot > tr > th,
        table > thead > tr > td,
        table > thead > tr > th {
            border: 1px solid #ddd;
        }

        table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }

        table > tbody > tr > td,
        table > tbody > tr > th,
        table > tfoot > tr > td,
        table > tfoot > tr > th,
        table > thead > tr > td,
        table > thead > tr > th {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }

        table > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        th {
            text-align: left;
        }

        td, th {
            padding: 0;
        }

        colgroup {
            display: table-column-group;
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
