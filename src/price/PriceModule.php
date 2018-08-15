<?php

class PriceModule extends WebModule
{
    const VERSION = '0.0.1';

    public static $moduleClass = __CLASS__;

    public function getCategory()
    {
        return static::t('T_CONSOLE');
    }

    public function getName()
    {
        return static::t('T_CONSOLE');
    }

    public function getDescription()
    {
        return static::t('T_CONSOLE_MODULE_DESCRIPTION');
    }

    public function getVersion()
    {
        return static::VERSION;
    }

    public function getAuthor()
    {
        return 'studxxx';
    }

    public function getAuthorEmail()
    {
        return 'stud181177@gmail.com';
    }

    public function getUrl()
    {
        return 'http://kardan.co.ua';
    }

    public function getIcon()
    {
        return "icon-upload-alt";
    }

    public function getNavigation()
    {
        return [
            [
                'label' => '<i class="icon-upload-alt"></i> ' . static::t('T_PRICES') . '<span class="arrow"></span>',
                'url' => 'javascript:void(0);',
                'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
                'items' => [
                    [
                        'label' => static::t('MENU_LIST'),
                        'url' => ['/price/import/index'],
                        'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
                        'active' => Yii::app()->controller->id === 'price'
                            && Yii::app()->controller->action->id !== 'create'
                    ],
                    [
                        'label' => static::t('MENU_ADD'),
                        'url' => ['/price/import/create'],
                        'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
                    ]
                ],
                'itemOptions' => ['class' => 'has-sub',],
                'submenuOptions' => ['class' => 'sub'],
            ],
        ];
    }

    public function getAdminPageLink()
    {
        return [
            'label' => '<i class="icon-upload-alt"></i> ' . static::t('T_PRICES') . '<span class="arrow"></span>',
            'url' => ['/console/price/index'],
            'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
        ];
    }

    public function init()
    {
        $import = [
            'price.models.*',
            'price.helpers.*',
            'price.behaviors.*',
            'price.components.*',
        ];

        foreach (Yii::app()->getModules() as $module => $data) {
            $import[] = "app.modules.{$module}.models.*";
        }

        $this->setImport($import);
    }

    public function beforeControllerAction($controller, $action)
    {
        return parent::beforeControllerAction($controller, $action);
    }

    public function install()
    {
        Yii::app()->config->add([
            [
                'param' => 'PRICE.PER_PAGE',
                'label' => 'Записей на странице',
                'value' => '100',
                'type' => 'integer',
                'default' => '10',
            ],
            [
                'param' => 'PRICE.WORKER',
                'label' => 'Воркер для обробки прайсів',
                'value' => 'price.worker',
                'type' => 'string',
                'default' => 'yii.worker',
            ],
            [
                'param' => 'PRICE.PATH_UPLOAD_IMPORT',
                'label' => 'Директорія для завантаження прайсів',
                'value' => 'prices',
                'type' => 'string',
                'default' => 'prices',
            ],
            [
                'param' => 'PRICE.PATH_UPLOAD',
                'label' => 'Path to upload price',
                'value' => 'uploads/prices',
                'type' => 'string',
                'default' => 'uploads',
            ],
            [
                'param' => 'PRICE.PATH_UPLOAD_IMPORT',
                'label' => 'Path for imported price',
                'value' => 'uploads/imported',
                'type' => 'string',
                'default' => 'import',
            ],
            [
                'param' => 'PRICE.CHUNK_SIZE',
                'label' => 'Chunk size document',
                'value' => 2000,
                'type' => 'integer',
                'default' => 2000,
            ],
            [
                'param' => 'PRICE.DISPLAY_LOG',
                'label' => 'Display log in console App',
                'value' => 0,
                'type' => 'integer',
                'default' => 1,
            ],
            [
                'param' => 'PRICE.DELIVERY',
                'label' => 'Delivery',
                'value' => 21,
                'type' => 'integer',
                'default' => 21,
            ],
        ]);


        FileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.imported'), 755);
        FileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.prises'), 755);
    }

    public function uninstall()
    {
        Yii::app()->config->delete([
            'PRICE.PRE_PAGE',
            'PRICE.WORKER',
            'PRICE.PATH_UPLOAD_IMPORT'
        ]);

        FileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.imported'));
        FileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.prises'));
    }

    /**
     * @param $str
     * @param $params
     * @param $dic
     * @return string
     */
    public static function t($str = '', $params = [], $dic = 'main')
    {
        return Yii::t(static::$moduleClass, $str) == $str
            ? Yii::t(static::$moduleClass . '.' . $dic, $str, $params)
            : Yii::t(static::$moduleClass, $str, $params);
    }
}
