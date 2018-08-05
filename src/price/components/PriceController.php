<?php

class PriceController extends CController
{
    /** @var string */
    public $layout = '//layouts/console-column2';
    /** @var array */
    public $breadcrumbs = [];
    /** @var array */
    public $menu = [];

    public function init()
    {
        try {
            $this->getPage();
        } catch (CException $e) {
            throw new CHttpException(500, $e->getMessage());
        }
    }

    /**
     * @param null $packages
     * @throws CException
     */
    protected function getPage($packages = [])
    {
        /** @var CClientScript $cs */
        $cs = Yii::app()->clientScript;
        foreach ($packages as $package) {
            $cs->registerPackage($package);
        }
        $cs->registerScript('init', "App.init();", CClientScript::POS_END);
    }
}
