<?php

/** @var \yii\web\View $this */

/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap4\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('_header') ?>
    <div class="wrap h-100 d-flex flex-column ">

        <main role="main" class="d-flex">
            <?= $this->render('_sidebar') ?>
            <div class="content-wrap p-3">
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
