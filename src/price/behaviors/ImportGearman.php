<?php

/**
 * Class ImportGearman
 * @author studxxx
 * @property mixed $args
 * @property Import $import
 * @property ImportPriceBehavior $priceImport
 * @property UpdatePriceDbBehavior $priceUpdateDb
 * @property RebuildPriceBehavior $priceLookupTecdoc
 */
class ImportGearman extends CBehavior implements WorkerJobInterface
{
    /**
     * Переімпортувати прайс з вихідного файлу
     * @var bool
     */
    public $reimportPrice = false;

    public function perform($job)
    {
        $this->args = $job;

        $this->import = Import::model()->findByPk($this->args->id);

        if (!$this->checkOutputFile()) {
            $this->attachBehavior('priceImport', 'ext.behaviors.price.ImportPriceBehavior');
            $this->priceImport->perform($this->args);
            $this->detachBehavior('priceImport');
        }

        $this->attachBehavior('priceUpdateDb', 'ext.behaviors.price.UpdatePriceDbBehavior');
        $this->priceUpdateDb->perform($this->args);
        $this->detachBehavior('priceUpdateDb');


        $this->attachBehavior('priceLookupTecdoc', 'ext.behaviors.price.RebuildPriceBehavior');
        $this->priceLookupTecdoc->perform($this->args);
        $this->detachBehavior('priceLookupTecdoc');
    }

    /**
     * Перевірити наявність зімпортованого файлу
     * @return bool
     */
    protected function checkOutputFile()
    {
        $path = Helpers::getPublicPath(
            Yii::app()->config
                ->get('IMPORT.PATH_UPLOAD_IMPORT')
        );

        return Helpers::checkfile($path . $this->getImport()->output_file) && !$this->reimportPrice;
    }

    /**
     * Відпр
     * @param array $params
     */
    public function task(array $params)
    {
        $gearman = new CDWorker();
        $gearman->setHost(Yii::app()->params['gearman']['host']);
        $gearman->setPort(Yii::app()->params['gearman']['port']);
        $gearman->client();
        $gearman->task(CJSON::encode($params), Yii::app()->params['gearman']['worker']);
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param mixed $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @return Import
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * @param Import $import
     */
    public function setImport($import)
    {
        $this->import = $import;
    }
}