<?php
/**
 * @var $this GoodsController
 * @var $model Goods
 * @var $tecdoc
 */

$this->breadcrumbs = [
    ConsoleModule::t('ITEM_UPDATE'),
];

$this->menu = [
    array('label' => 'Журнал товаров', 'url' => array('index')),
    array('label' => 'Добавить новый товар', 'url' => array('create')),
    array('label' => 'Просмотр данных товара', 'url' => array('view', 'id' => $model->id)),
];
?>

<?php echo $this->renderPartial('_form', [
    'model' => $model
]); ?>

<div class="row-fluid">
    <div class="span5">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => 'Items',
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
        ]);

        $this->widget('bootstrap.widgets.TbDetailView', [
            'data' => $model,
            'attributes' => [
                'id',
                'sku',
                'made' => [
                    'name' => 'made',
//                    'value' => $model->items2brand->title,
                ],
                'price',
                [
                    'name' => ConsoleModule::t('MODEL_SHOW_PRICE'),
                    'value' => $model->getPrice(),
                ],
                'count',
                'sid' => [
                    'name' => 'sid',
                    'value' => $model->suppliers
                        ->name,
                ],

                'created' => [
                    'name' => 'created',
                    'value' => date('Y-m-d, H:i:s', $model->created),
                ],
                'updated' => [
                    'name' => 'updated',
                    'value' => date('Y-m-d, H:i:s', $model->updated),
                ],
            ],
        ]);
        $this->endWidget(); ?>
    </div>
    <div class="span7">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => 'Items',
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
        ]); ?>
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'template' => '{items}',
            'id' => 'grid-tecdoc-data',
            'dataProvider' => $tecdoc,
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

        $this->endWidget();
        ?>
    </div>
</div>