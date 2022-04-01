<?php

use yii\bootstrap4\Nav;

echo Nav::widget([
    'options' => [
        'class' => 'd-flex nav-polls flex-column'
    ],
    'items' => [
        [
            'label' => 'Dashboard',
            'url' => ['/site/index']
        ],
        [
            'label' => 'Videos',
            'url' => ['/videos/index']
        ]
    ],
]);
