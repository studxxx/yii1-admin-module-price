<?php
/* @var CActiveDataProvider $dataProvider */


$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'grid-tecdoc-data',
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'article_nr',
        'supplier_id' => [
            'name' => 'supplier_id',
            'value' => '$data->suppliers->brand',
        ],
        'description',
    ]
]);