<?php
/* @var $this PriceProductController */
/* @var $model PriceProduct */
/* @var CActiveDataProvider $tecdoc */

$this->pageTitle = ConsoleModule::t('BUTTON_VIEW_ITEM');

$this->breadcrumbs = [
    PriceModule::t('BUTTON_MANAGE_ITEMS') => ['index'],
    $this->pageTitle,
];

?>
<div class="row-fluid">
    <div class="span6">
        <?php
        $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => 'Дані товару',
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'hideDownButton' => true,
            'hideRemoveButton' => true,
            'portletMenu' => [
                ['label' => 'Журнал товаров', 'url' => ['index']],
                [
                    'label' => '<i class="icon-plus"></i>',
                    'url' => ['create'],
                    'linkOptions' => [
                        'class' => 'btn btn-mini tooltip-bottom',
                        'title' => PriceModule::t('T_BUTTON_ADD_PRODUCT'),
                        'data-toggle' => "tooltip",
                        'id' => 'btn-add-product'
                    ]
                ],
                [
                    'label' => '<i class="icon-pencil"></i>',
                    'url' => ['update', 'id' => $model->id],
                    'linkOptions' => [
                        'class' => 'btn btn-mini tooltip-bottom',
                        'title' => PriceModule::t('T_BUTTON_EDIT_PRODUCT'),
                        'data-toggle' => "tooltip",
                        'id' => 'btn-edit-product'
                    ]
                ],
                [
                    'label' => '<i class="icon-trash"></i>',
                    'url' => '#',
                    'linkOptions' => [
                        'class' => 'btn btn-mini tooltip-bottom',
                        'title' => PriceModule::t('T_BUTTON_REMOVE_PRODUCT'),
                        'submit' => [
                            'delete',
                            'id' => $model->id
                        ],
                        'confirm' => PriceModule::t('T_CONFIRM_DELETE_PRODUCT'),
                        'data-toggle' => "tooltip",
                        'id' => 'btn-remove-product'
                    ]
                ],
            ],
        ]);

        $this->widget('bootstrap.widgets.TbDetailView', [
            'data' => $model,
            'attributes' => [
                'id',
                'name',
                'sku',
                'brand',
                'price',
                'final_price',
//                [
//                    'name' => ConsoleModule::t('MODEL_SHOW_PRICE'),
//                    'value' => $model->getPrice(),
//                ],
                'exist' => [
                    'name' => 'exist',
                    'value' => PriceProductHelper::getExist($model),
                    'type' => 'raw',
                ],
                'visible' => [
                    'name' => 'visible',
                    'value' => PriceProductHelper::getVisible($model),
                    'type' => 'raw',
                ],
                'count',
                'supplier_id' => [
                    'name' => 'supplier_id',
                    'value' => $model->suppliers->name,
                ],
                'created_at' => [
                    'name' => 'created_at',
                    'value' => DateHelper::datetime($model, 'created_at'),
                ],
                'updated_at' => [
                    'name' => 'updated_at',
                    'value' => DateHelper::datetime($model, 'updated_at'),
                ],
                'description',
            ],
        ]);

        $this->endWidget();
        ?>
    </div>
    <div class="span6">

    </div>
</div>

<!--<h2>Дані Tecdoc</h2>-->
<!---->
<?php //$this->renderPartial('view/_tecdoc', [
//    'dataProvider' => $tecdoc,
//]) ?>
<!---->
<!--<h2>Можливі варіанти</h2>-->
<!---->
<?php //$this->renderPartial('view/_possible_variants', [
//    'possible_variants' => $possible_variants
//]) ?>
