<?php

/** @var \yii\web\View $this */

/** @var string $content */

use yii\bootstrap4\Alert;

$this->beginContent('@frontend/views/layouts/base.php')
?>


<main role="main" class="d-flex">
    <?= $this->render('_sidebar') ?>
    <div class="content-wrapper p-3">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endContent() ?>

