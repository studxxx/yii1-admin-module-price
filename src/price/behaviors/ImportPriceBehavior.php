<?php

Yii::import('vendor.studxxx.yii1-gearman.src.gearman.components.WorkerJobInterface');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.models.*');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.models.forms.*');
Yii::import('vendor.studxxx.yii1-admin-module-price.src.price.components.repositories.*');

/**
 * Class ImportPriceBehavior
 * @property PriceRepository $prices
 *
 *
 * @property DocumentHandler $documentHandler
 * @property TemplateForm $template
 * @property Price $price
 * @property string $path
 * @property string $pathExport
 * @property array $args
 * @property PriceSupplier $supplier
 * @property string $priceFile
 * @property array $documentMap
 * @property array $delivery
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

        /** @var $data CList
         */
        $data = $this->documentHandler->documentRead();
        $data->readOnly = true;

        foreach ($data as $item) {
            $form = new DocumentForm($this->supplier->templates);
            $form->defineAttribute('supplier_id', $this->supplier->id);
            $form->addRule('supplier_id', 'numerical', ['integerOnly' => true]);
            $form->defineAttribute('visible', PriceProduct::SHOW);
            $form->addRule('visible', 'numerical', ['integerOnly' => true]);
            $form->defineAttribute('exist', PriceProduct::EXIST_AVAILABLE);
            $form->addRule('exist', 'numerical', ['integerOnly' => true]);
            $form->defineAttribute('delivery', Yii::app()->config->get('IMPORT.DELIVERY'));
            $form->addRule('delivery', 'numerical', ['integerOnly' => true]);

            if (!$form->load($item, '')) {
                throw new CException('Row data not loaded');
            }

            $form->defineAttribute('token', implode('_', [$item['brand'], $item['sku'], $this->supplier->id]));
            $form->addRule('token', 'filter', ['filter' => 'md5']);
            $form->addRule('token', 'length', ['max' => 32]);

            $form->defineAttribute('search', $item['sku']);
            $form->addRule('search', 'filter', ['filter' => ['DocumentForm', 'filterOnlySymbol']]);
            $form->addRule('search', 'length', ['max' => 32]);

            if (!empty($item['delivery'])) {
                $form->defineAttribute('delivery', Yii::app()->config->get('IMPORT.DELIVERY') + $item['delivery']);
            }

            if ($form->validate()) {

                $row = $form->attributes;
                $this->items[] = $row;
                if ($this->hasEventHandler('onDocumentRowRead')) {
                    $this->onDocumentRowRead(new CEvent($row));
                }
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
     * Данные шаблона прайса
     * @return TemplateForm
     * @deprecated
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     * @deprecated
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Завантажити шаблон документа
     * @param $id
     * @return TemplateForm
     * @throws CDbException
     * @deprecated
     */
    public function loadTemplate($id)
    {
        $model = Suppliers::model()->findByPk($id);

        if ($model === null) {
            throw new CDbException('No data supplier', 404);
        }
        $template = new TemplateForm();
        $template->attributes = CJSON::decode($model->template);

        return $template;
    }

    /**
     * @param string $sku
     * @deprecated
     */
    public function setSku($sku)
    {
        $this->sku = $this->checkField($sku)
            ? call_user_func([$this, 'clearChars'], $sku)
            : '';
    }

    /**
     * @param $text
     * @return string
     * @deprecated
     */
    private function clearChars($text)
    {
        $text = trim($text);
        $text = trim($text, "'");
        $text = trim($text, "->");
        return trim($text);
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
     * @param $price
     * @return float|string
     * @deprecated
     */
    public function priceConverter($price)
    {
        $price = call_user_func([$this, 'clearChars'], $price);
        return Helpers::stringToFloat($price);
    }

    /**
     * @param int $supplier
     * @deprecated
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @deprecated
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    protected function getPrices()
    {
        return new PriceRepository();
    }
}
