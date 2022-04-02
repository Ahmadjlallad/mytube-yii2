<?php

use yii\bootstrap4\Nav; ?>
<aside class="shadow">
    <?= Nav::widget([
        'options' => [
            'class' => 'd-flex flex-column nav-pills'
        ],
        'items' => [
            [
                'label' => 'Dashboard',
                'url' => ['/site/'],
                'active' => $this->context->route == 'site/index'
            ],
            [
                'label' => 'Videos',
                'url' => ['/video'],
                'active' => $this->context->route == 'video/index'
            ]
        ],

    ]); ?>
</aside>