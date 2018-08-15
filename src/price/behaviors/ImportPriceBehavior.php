<?php
/**
 * @author vstudynytskyy <stud181177@gmail.com>
 */
Yii::import('app.components.gearman.WorkerJobInterface');

/**
 * Class ImportPriceBehavior
 * @property Excel $documentHandler
 * @property TemplateForm $template
 * @property Import $import
 * @property string $path
 * @property string $pathExport
 * @property array $args
 * @property int $supplier
 * @property string $priceFile
 * @property array $documentMap
 * @property array $delivery
 */
class ImportPriceBehavior extends CBehavior implements WorkerJobInterface
{
    /**
     * @var array
     */
    public $job;

    /**
     * @var
     */
    private $_excel;
    /**
     * @var ImportCollection
     */
    private $items;
    private $_worksheet;

    /**
     * @var Logs
     */
    public $log;
    /**
     * @var bool
     */
    public $displayLog = false;

    private $_startTime;

    /**
     * Інсталювати конфігурації компонента
     */
    public function install()
    {
        Yii::app()->config->add([
            [
                'param' => 'IMPORT.PATH_UPLOAD',
                'label' => 'Path to upload price',
                'value' => 'uploads/prices',
                'type' => 'string',
                'default' => 'uploads',
            ],
            [
                'param' => 'IMPORT.PATH_UPLOAD_IMPORT',
                'label' => 'Path for imported price',
                'value' => 'uploads/imported',
                'type' => 'string',
                'default' => 'import',
            ],
            [
                'param' => 'IMPORT.CHUNK_SIZE',
                'label' => 'Chunk size document',
                'value' => '2000',
                'type' => 'integer',
                'default' => '2000',
            ],
            [
                'param' => 'IMPORT.DISPLAY_LOG',
                'label' => 'Display log in console App',
                'value' => '0',
                'type' => 'integer',
                'default' => '1',
            ],
            [
                'param' => 'IMPORT.DELIVERY',
                'label' => 'Delivery',
                'value' => 21,
                'type' => 'integer',
                'default' => 21,
            ],
        ]);
    }

    /**
     * Видалити конфігурації компонента
     */
    public function uninstall()
    {
        Yii::app()->config->delete([
            'IMPORT.PATH_UPLOAD',
            'IMPORT.CHUNK_SIZE',
            'IMPORT.DISPLAY_LOG',
            'IMPORT.DELIVERY',
        ]);
    }

    /**
     * @param $job
     * @throws CException
     */
    public function perform($job)
    {
        $this->args = $job;
        $this->init();

        // Заносим данные в массив $this->items
        if ($this->getPriceExtension() == 'csv') {
            $this->processCsv();
        } else {
            $this->process();
        }

        if (empty($this->items)) {
            throw new CException('No import Data', 500);
        }

        $output_file = $this->CsvExport();

        if (empty($output_file)) {
            throw new CException('Data not saved', 500);
        }

        // Отмечаем что файл загружен
        $this->import->status = 1;
        $this->import->output_file = $output_file;
        $this->import->updated = time();
        $this->import->save(false);

        $this->log->message('import_complete', [
            '{outputfile}' => $output_file,
            '{supplier}' => $this->import->supplier,
        ]);

        $time = microtime(true) - $this->_startTime;

        $this->clearImport();

        $this->log->message('memory', []);

        $this->log->message('end', [
            '{microtime}' => $time,
        ]);

        $this->createTask();
    }

    /**
     * @return string
     * @throws CException
     * @throws Exception
     */
    public function CsvExport()
    {
        Yii::import('ext.ECSVExport');

        // Данные для экспорта и его настройки
        $csv = new ECSVExport($this->items, true, false, '|');

        $output = $csv->toCSV();

        $fileName = $this->supplier . '-' . time() . '.csv';

        file_put_contents($this->pathExport . $fileName, $output);

        return $fileName;
    }

    /**
     * Читаємо прайс Excel форматі
     * @throws Exception
     */
    public function process()
    {
        // Старт
        $this->log->message('start_read', [
            '{supplier}' => $this->supplier,
        ]);

        // Підключаємо бібліотеку для зчитування даних в документі
        // Читаємо усе крім пустих строк і пишемо згідно $this->documentMap
        $this->documentHandler = new DocumentHandler();
        $this->documentHandler->file = $this->priceFile;
        $this->documentHandler->skipRows = $this->template->step;
        $this->documentHandler->chunkSize = Yii::app()->config->get('IMPORT.CHUNK_SIZE');
        $this->documentHandler->emptyRowsForEnd = $this->template->empty_rows_for_end;
        $this->documentHandler->requiredFields = explode('+', $this->template->validate_row);
        $this->documentHandler->template = $this->documentMap;

        /**
         * @var $data CList
         */
        $data = $this->documentHandler->documentRead();
        $data->readOnly = true;

        foreach ($data as $item) {
            if (!$this->validateRow($item)) {
                echo 'Row skipped', PHP_EOL;
            } elseif ($special = $this->validateSpecialRow($item)) {
                $specialFieldName = $special;
            } else {
                //тут сетим каталог до тех пор пока не изменится или производитель или др. данніе
                if (!empty($specialFieldName)) {
                    $item[$specialFieldName] = $this->getSpecialColumn($specialFieldName);
                }

                // Після того як сформувалась строка даних
                // створюємо маркер
                $marker = implode('_', [
                    $item['made'],
                    $item['sku'],
                    $this->supplier
                ]);

                $item['marker'] = md5($marker);

                $item['delivery'] = !empty($item['delivery'])
                    ? $this->delivery + $item['delivery']
                    : $this->delivery;

                $item['sid'] = $this->supplier;
                $item['visible'] = 1;
                $item['exist'] = 1;
                $item['search'] = preg_replace("/[^a-zA-Z0-9]/", "", $item['sku']);

                $note = [];

                if (!empty($item['description'])) {
                    $note = array_merge($note, ['description' => $item['description']]);
                }

                if (!empty($item['skuOE'])) {
                    $note = array_merge($note, ['skuOE' => $item['skuOE']]);
                }

                if (!empty($note)) {
                    $item['note'] = CJSON::encode($note);
                }

                $result = [
                    'search' => null,
                    'made' => null,
                    'sku' => null,
                    'name' => null,
                    'price' => null,
                    'exist' => null,
                    'count' => null,
                    'sid' => null,
                    'visible' => null,
                    'marker' => null,
                    'note' => null,
                    'delivery' => null,
                ];

                array_walk($result, function (&$v, $i, $data) {
                    if (!empty($data[$i])) {
                        $v = $data[$i];
                    }
                }, $item);

                $this->items[] = $result;

                $this->log->message('row_line', [
                    '{marker}' => $result['marker'],
                    '{brand}' => $result['made'],
                    '{sku}' => $result['sku'],
                    '{name}' => $result['name'],
                ]);
            }
        }
    }

    public function init()
    {
        $this->setLog();
        $this->_startTime = microtime(true);
        $this->setDisplayLog(Yii::app()->config->get('IMPORT.DISPLAY_LOG'));

        $this->import = $this->loadImport();

        $this->path = Yii::app()->config->get('IMPORT.PATH_UPLOAD');
        $this->priceFile = $this->path . $this->import->name;

        if (!$this->hasPriceFile()) {
            throw new CException('No file price for import', 404);
        }

        $this->log->message('import_file', [
            '{filename}' => $this->import->name
        ]);

        $this->supplier = $this->import->supplier;
        $this->template = $this->loadTemplate($this->supplier);

        $this->pathExport = Helpers::getPublicPath(
            Yii::app()->config->get('IMPORT.PATH_UPLOAD_IMPORT')
        );
    }

    /**
     * Координати розташування колонок
     * для читання документу
     *
     * @return array
     */
    public function getDocumentMap()
    {
        return array_diff_key($this->template->attributes, array_flip([
            'step',
            'special_row',
            'validate_row',
            'empty_rows_for_end',
        ]));
    }

    /**
     * Читаємо прайс в Csv форматі
     */
    public function processCsv()
    {

    }

    /**
     * Инициализируем библиотеку PHPExcel
     * @return Excel|mixed
     */
    public function getDocumentHandler()
    {
        if ($this->documentHandler instanceof Excel) {
            return $this->documentHandler;
        }

        $this->documentHandler = new Excel();

        return $this->documentHandler;
    }

    /**
     * @param $documentHandler
     */
    public function setDocumentHandler($documentHandler)
    {
        $this->documentHandler = $documentHandler;
    }

    /**
     * Это строки которые несут данные о товаре, но не явсляются строкой товара
     * format - name:value:f1-empty+f2-not_empty
     * name - str назва поля
     * value - number cell
     * f1,f2 - name field in form - поля для валідації
     *
     * @param $item
     * @return bool
     */
    protected function validateSpecialRow($item)
    {
        if (empty($this->getTemplate()->special_row)) {
            return false;
        }

        $special_row = explode(':', $this->getTemplate()->special_row);
        list($newFieldName, $fieldName, $validateFields) = $special_row;
        $fields = explode('+', $validateFields);

        foreach ($fields as $field) {
            $params = explode('-', $field);
            list($validateField, $condition) = $params;

            if (!$this->hasField($validateField, $condition)) {
                return false;
            }
        }

        $this->setSpecialColumn($newFieldName, $item[$fieldName]);

        return $newFieldName;
    }

    private function clearImport()
    {
        $this->job = null;
        $this->_excel = null;
        $this->args = null;
        $this->_startTime = null;
        $this->items = [];
    }

    public function setLog()
    {
        $this->log = new Logs();
    }

    public function getLog($name, $params)
    {
        $this->log->message($name, $params);
    }

    /**
     * @param Import $import
     */
    public function setImport($import)
    {
        $this->import = $import;
    }

    /**
     * Завантажити дані для імпорту
     *
     * @return static
     */
    protected function loadImport()
    {
        return Import::model()->findByPk($this->args->id);
    }

    /**
     * Установити шлях до файлу
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = Helpers::getPublicPath($path);
    }

    /**
     * @return string
     */
    public function hasPath()
    {
        return false == empty($this->_path);
    }

    /**
     * @param string $pathExport
     */
    public function setPathExport($pathExport)
    {
        $this->pathExport = $pathExport;
    }

    /**
     * @param string $priceFile
     */
    public function setPriceFile($priceFile)
    {
        $this->priceFile = $priceFile;
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
     */
    public function getPriceExtension()
    {
        $file = new SplFileInfo($this->priceFile);
        return $file->getExtension();
    }

    /**
     * @return mixed
     */
    public function getWorksheet()
    {
        return $this->_worksheet;
    }

    /**
     * @param mixed $worksheet
     */
    public function setWorksheet($worksheet)
    {
        $this->_worksheet = $worksheet;
    }

    /**
     * @param int $col
     * @param int $row
     * @return mixed
     */
    public function getCellByColumnAndRow($col, $row)
    {
        return $this->getWorksheet()
            ->getCellByColumnAndRow($col, $row)
            ->getValue();
    }

    /**
     *
     * @param $i int лічильник строк
     */
    protected function setRow($i)
    {
        $this->setMade(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->made, $i)
        );

        $this->setSku(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->sku, $i)
        );

        $this->setSkuOE(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->skuOE, $i)
        );

        $this->setName(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->name, $i)
        );

        $this->setDescription(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->description, $i)
        );

        /* Установлюємо ціну від поставщика */
        $this->setPrice(
            $this->getCellByColumnAndRow((int)$this->getTemplate()->price, $i)
        );

        /* Установлюємо що товар наявний */
        $this->setExist(1);

        /* Установлюємо дані кількіть товару */
        $this->setCount(
            $this->getCellByColumnAndRow(
                (int)$this->getTemplate()
                    ->count,
                $i
            )
        );
    }

    /**
     * @param $display_log bool
     */
    protected function setDisplayLog($display_log)
    {
        $this->log->display = $display_log;
    }

    const IS_EMPTY = 'empty';
    const NOT_EMPTY = 'not_empty';

    /**
     * @var TemplateForm
     */
    public $template;

    /**
     * @param $name
     * @return bool
     */
    public function checkField($name)
    {
        return false == empty($name) || $name === 0 || $name === '0';
    }

    public function hasField($name, $condition = self::NOT_EMPTY)
    {
        switch ($condition) {
            case self::IS_EMPTY:
                return empty($this->$name);
            case self::NOT_EMPTY:
                return false == empty($this->$name);
            default:
                return false;
        }
    }

    /**
     * Перевірити корректність отриманих даних
     * sku+made+price
     *
     * @param $item
     * @return bool
     */
    protected function validateRow($item)
    {
        $fields = explode('+', $this->getTemplate()->validate_row);

        $result = array_filter($fields, function ($field) use ($item) {
            return !empty($item[$field]);
        });

        return count($fields) == count($result);
    }

    /**
     * Данные шаблона прайса
     * @return TemplateForm
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Завантажити шаблон документа
     * @param $id
     * @return TemplateForm
     * @throws CDbException
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
     * Отримати час доставки в днях
     *
     * @return array
     */
    public function getDelivery()
    {
        return !empty($this->template->delivery)
            ? $this->template->delivery
            : Yii::app()->config->get('IMPORT.DELIVERY');
    }



    // @todo Если надо сделать составные поля, то для начала определяем признак по которому они будут объединяться
    // в форме. Определить чем будут объединяться полученные значения. Сетим данные в поле.
//                      if($field !== '')
//                    {
//                        //
//                        $fields = explode('+',$field);
//                        if(count($fields) > 1)
//                        {
//                            $splitFields=[];
//                            foreach($fields as $f)
//                            {
//                                $splitFields[] = $objWorksheet->getCellByColumnAndRow((int)$f, $i)->getValue();
//                            }
//                            $value = implode('-->',$splitFields);
//                        }
//                        else
//                        {
//                            // Получаем значения полей согласно шаблона
//                            $value = $objWorksheet->getCellByColumnAndRow((int)$field, $i)->getValue();
//                        }
//                        $item[$key] = htmlspecialchars(trim($value));
//                    }else{
//                        $item[$key] = '';
//                    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = trim($code);
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $this->checkField($sku)
            ? call_user_func([$this, 'clearChars'], $sku)
            : '';
    }

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
    public function getSkuOE()
    {
        return $this->skuOE;
    }

    /**
     * @param string $sku
     */
    public function setSkuOE($sku)
    {
        $this->skuOE = $this->checkField($sku)
            ? call_user_func([$this, 'clearChars'], $sku)
            : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $this->checkField($name)
            ? call_user_func([$this, 'clearChars'], $name)
            : '';
    }

    /**
     * @return string
     */
    public function getMade()
    {
        return $this->made;
    }

    /**
     * @param int $made
     */
    public function setMade($made)
    {
        $this->made = $this->checkField($made)
            ? call_user_func([$this, 'clearChars'], $made)
            : '';
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = call_user_func([$this, 'clearChars'], $model);
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param string $intro
     */
    public function setIntro($intro)
    {
        $this->intro = trim($intro);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $this->checkField($description)
            ? call_user_func([$this, 'clearChars'], $description)
            : '';
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
        $this->price = $this->checkField($price)
            ? call_user_func([$this, 'priceConverter'], $price)
            : '';
    }

    public function priceConverter($price)
    {
        $price = call_user_func([$this, 'clearChars'], $price);
        return Helpers::stringToFloat($price);
    }

    /**
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param string $storage
     */
    public function setStorage($storage)
    {
        $this->storage = trim($storage);
    }

    /**
     * @var string
     */
    private $code;      // Код товару
    /**
     * @var string
     */
    private $sku;  // Артикул
    /**
     * @var string
     */
    private $skuOE;  // Артикул
    /**
     * @var string
     */
    private $name; // Назва товару
    /**
     * @var string
     */
    private $made; // Виробник
    /**
     * @var string
     */
    private $model;// Модель
    /**
     * @var string
     */
    private $intro;// Короткі х-ки товару
    /**
     * @var string
     */
    private $description;// Повні х-ки товару
    /**
     * @var string
     */
    private $price;
    /**
     * @var string
     */
    private $storage;// наявність склад 1, наявність склад 2, наявність склад 3
    /**
     * @var string
     */
    private $exist;
    /**
     * @var string
     */
    private $count;
    /**
     * @var string
     */
    private $specialColumn;
    /**
     * @var string
     */
    private $marker;

    /**
     * @return string
     */
    public function getExist()
    {
        return $this->exist;
    }

    /**
     * @param string $exist
     */
    public function setExist($exist)
    {
        $this->exist = trim($exist);
    }

    /**
     * @return string
     */
    public function getMarker()
    {
        return $this->marker;
    }

    /**
     * @param string $marker
     */
    public function setMarker($marker)
    {
        $this->marker = $marker;
    }

    /**
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param string $count
     */
    public function setCount($count)
    {
        $this->count = $this->checkField($count) ? trim($count) : null;
    }

    /**
     * @param int $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getSpecialColumn($name)
    {
        return $this->specialColumn[$name];
    }

    /**
     * Дані які не є в загальних строках таблиці
     * @param $name
     * @param $value
     */
    public function setSpecialColumn($name, $value)
    {
        $this->specialColumn[$name] = $value;
    }

    public function getRow()
    {
        return [
            'made' => $this->getMade(),
            'sku' => $this->getSku(),
//            'skuOE' => $this->getSkuOE(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'exist' => $this->getExist(),
            'count' => $this->getCount(),
            'supplier' => $this->getSupplier(),
            'visible' => 1,
            'marker' => $this->getMarker(),
            'description' => $this->getDescription(),
        ];
    }

//    public function saveToDb()
//    {
//        $brand_id = $this->saveBrandToDb();
//
//
//        $model = Goods::model()->findByAttributes([
//            'marker'=>$this->getMarker(),
//        ]);
//
//        $data = $this->getRow();
//
//        if($model === null){
//            $model = new Goods();
//        } else {
//            unset($data['made']);
//            unset($data['sku']);
//            unset($data['skuOE']);
//            unset($data['name']);
//            unset($data['marker']);
//            unset($data['description']);
//        }
//
//        $model->attributes = $this->getRow();
//        $model->brand   = $brand_id;
//        $model->sid     = $this->getSupplier();
//        $model->save(false);
//    }
//
//    public function saveBrandToDb()
//    {
//        $brands = Catalog::model()
//            ->getNodeByName('catalog_manufacturers');
//
//        $model = Catalog::model()->findByAttributes([
//            'name'=>$this->getMade(),
//        ], 'left_key > :left_key AND right_key < :right_key', [
//            ':left_key' => $brands->left_key,
//            ':right_key' => $brands->right_key,
//        ]);
//
//        if($model === null)
//        {
//            $model = new Catalog();
//            $model->idNode = $brands->id;
//            $model->name = $this->getMade();
//            $model->title = $this->getMade();
//            if($model->save(false)){
//                $this->log->message('add_new_brand',[
//                    '{id}'      => $model->id,
//                    '{brand}'   => $model->title,
//                ]);
//            }
//        }
//        return $model->id;
//    }

    /**
     * @return array
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param array $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getExcel()
    {
        return $this->_excel;
    }

    /**
     * @param mixed $excel
     */
    public function setExcel($excel)
    {
        $this->_excel = $excel;
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
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->_startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        $this->_startTime = $startTime;
    }

    public function createTask()
    {
        $gearman = new CDWorker();
        $gearman->setHost(Yii::app()->params['gearman']['host']);
        $gearman->setPort(Yii::app()->params['gearman']['port']);
        $gearman->setPerformer(Yii::app()->params['gearman']['worker']);
        $gearman->client();
        $gearman->task(CJSON::encode([
            'performer' => 'ext.behaviors.price.UpdatePriceDbBehavior',
            'data' => $this->import->id,
        ]));
    }
}