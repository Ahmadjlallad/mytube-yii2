<?php

/** @var \yii\web\View $this */

/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap4\Alert;

AppAsset::register($this);
$this->beginContent('@backend/views/layouts/base.php')
?>


<main role="main" class="d-flex">
    <?= $this->render('_sidebar') ?>
    <div class="content-wrapper p-3">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>

