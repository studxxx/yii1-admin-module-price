<?php

Yii::import('vendor.studxxx.yii1-gearman.src.gearman.components.WorkerJobInterface');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.models.*');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.models.forms.*');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.components.repositories.*');

/**
 * Class ImportPriceBehavior
 * @property PriceRepository $prices
 *
 * @property DocumentHandler $documentHandler
 * @property Price $price
 * @property string $path
 * @property string $pathExport
 * @property array $args
 * @property PriceSupplier $supplier
 * @property string $priceFile
 * @property array $documentMap
 * @property CLogger $logger
 */
class ImportPriceBehavior extends CBehavior implements WorkerJobInterface
{
    /** @var array */
    private $items;
    public $events = [];

    public static function classFromDotPath($class)
    {
        if (is_array($class)) {
            $class = $class['class'];
        }

        $pos = strrpos($class, '.');
        if ($pos === false) {
            return $class;
        }

        return substr($class, $pos + 1);
    }

    public static function hasDotPath($path)
    {
        return !!strrpos($path, '.');
    }

    /**
     * @param $job
     * @throws CException
     * @throws InvalidConfigException
     * @throws PHPExcel_Exception
     * @throws ReflectionException
     */
    public function perform($job)
    {
        foreach ($this->events as $name => $handlers) {
            foreach ($handlers as $handler) {
                if ($this->hasDotPath($handler[0])) {
                    Yii::import($handler[0]);
                    $handler[0] = self::classFromDotPath($handler[0]);
                }
                $this->attachEventHandler($name, $handler);
            }
        }

        $this->price = $this->prices->get($job['id']);
        $this->supplier = $this->price->supplier;

        if (!$this->hasPriceFile()) {
            throw new CException('No file price for import', 404);
        }

        /**
         * @var $data CList
         */
        $data = $this->documentHandler->documentRead();
        $this->getDocumentHandler(); // Clear object
        $data->readOnly = true;
        $rows = $data->toArray();

        foreach ($rows as $key => $item) {
            $this->initDb();
            set_time_limit(1800);
            $form = new DocumentForm($this->supplier->templates, [
                'supplier_id' => $this->supplier->id,
                'visible' => PriceProduct::SHOW,
                'exist' => PriceProduct::EXIST_AVAILABLE,
                'delivery' => Yii::app()->config->get('IMPORT.DELIVERY'),
            ]);

            if (!$form->load($item, '')) {
                throw new CException('Row data not loaded');
            }

            $form->defineAttribute('token', implode('_', [$item['brand'], $item['sku'], $this->supplier->id]));
            $form->defineAttribute('search', $item['sku']);

            if (!empty($item['delivery'])) {
                $form->defineAttribute('delivery', Yii::app()->config->get('IMPORT.DELIVERY') + $item['delivery']);
            }

            if ($form->validate()) {

                $row = $form->attributes;
                $this->items[] = $row;
                $output = implode(', ', array_map(function ($v, $k) {
                    return is_array($v)
                        ? "{$k}[]=" . implode("&{$k}[]=", $v)
                        : "$k=$v";
                }, $row, array_keys($row)));

                $this->logger->log($output);

                if ($this->hasEventHandler('onDocumentRowRead')) {
                    $this->onDocumentRowRead(new CEvent($row));
                }
            }
            try {
//                $data->removeAt($key);
                unset($rows[$key]);
            } catch (Exception $e) {
                Yii::log("Can't remove item from product list.", CLogger::LEVEL_ERROR);
            }
        }

        $this->price->status = empty($this->items)
            ? Price::STATUS_ERROR
            : Price::STATUS_IMPORTED;
        $this->price->csv_file = $this->saveTo('csv');
        $this->prices->save($this->price);
    }

    /**
     * @param CEvent $event
     * @throws CException
     */
    public function onDocumentRowRead(CEvent $event)
    {
        $this->raiseEvent('onDocumentRowRead', $event);
    }

    /**
     * @param string $type
     * @return string
     * @throws CException
     */
    public function saveTo($type = 'csv')
    {
        return 'save data into csv in developing';
        Yii::import('ext.ECSVExport');

        // Данные для экспорта и его настройки
        $csv = new ECSVExport($this->items, true, false, '|');

        $output = $csv->toCSV();

        $fileName = $this->supplier->id . '-' . time() . '.csv';

        if (!file_put_contents($this->pathExport . $fileName, $output)) {
            $this->price->status = Price::STATUS_ERROR;
        }

        return $fileName;
    }

    /**
     * Координати розташування колонок
     * для читання документу
     *
     * @return array
     */
    public function getDocumentMap()
    {
        $map = [];
        /** @var PriceTemplate $template */
        foreach ($this->supplier->templates as $template) {
            $map[$template->field_name] = $template->coordinate;
        }
        return $map;
    }

    /**
     * @return DocumentHandler
     */
    public function getDocumentHandler()
    {
        $documentHandler = new DocumentHandler();
        $documentHandler->file = $this->priceFile;
        $documentHandler->skipRows = 0;//$this->supplier->templateParams->step;
        $documentHandler->chunkSize = Yii::app()->config->get('IMPORT.CHUNK_SIZE');
        $documentHandler->emptyRowsForEnd = 50;//$this->supplier->templateParams->empty_rows_for_end;
        $documentHandler->template = $this->documentMap;

        return $documentHandler;
    }

    /**
     * Установити шлях до файлу
     *
     * @return string
     */
    public function getPath()
    {
        return Helpers::getPublicPath(Yii::app()->config->get('IMPORT.PATH_UPLOAD'));
    }

    /**
     * @return string
     */
    public function getPathExport()
    {
        return Helpers::getPublicPath(Yii::app()->config->get('IMPORT.PATH_UPLOAD_IMPORT'));
    }

    /**
     * @return string
     */
    public function getPriceFile()
    {
        return $this->path . $this->price->price_file;
    }

    /**
     * @return string
     */
    public function hasPriceFile()
    {
        return Helpers::checkfile($this->priceFile);
    }

    /**
     * @return string
     * @deprecated
     */
    public function getPriceExtension()
    {
        $file = new SplFileInfo($this->priceFile);
        return $file->getExtension();
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param int $supplier
     * @deprecated
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    protected function getPrices()
    {
        return new PriceRepository();
    }

    public function getLogger()
    {
        return Yii::getLogger();
    }

    /**
     * @throws CDbException
     * @throws CException
     */
    protected function initDb()
    {
        //run a default command to check for the database connection
        //if it fails then reconnect to the database
        Yii::app()->db->setActive(false);
        try {
            echo "*******************************************\n";
            echo "\ntrying to do a dummy command on the database\n";
            echo "*******************************************\n";
            Yii::app()->db->createCommand('select 1')->execute();
        } catch (exception $e) {
            echo "*******************************************\n";
            echo "got exception -- " . $e->getMessage() . "\n";
            echo "*******************************************\n";
            Yii::app()->db->setActive(false);
            Yii::app()->db->setActive(true);
            //try reconnecting again....if this fails, then we know that the mysql server won't work
            Yii::app()->db->createCommand('select 1')->execute();
        }
    }

}
