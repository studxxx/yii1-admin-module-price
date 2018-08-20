<?php
/**
 * @author vstudynytskyy <stud181177@gmail.com>
 */

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'grid-possible-variants',
    'dataProvider' => $possible_variants,
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