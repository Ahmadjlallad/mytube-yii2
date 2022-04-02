<?php
/**
 * @var $dataProvider ActiveDataProvider
 */

use yii\data\ActiveDataProvider;

?>
<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_video_item',
    'layout' => '<div class="d-flex flex-wrap">{items}<div> {pager}',
    'itemOptions' => [
        'tag' => false,
    ]
])

?>