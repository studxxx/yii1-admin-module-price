<?php

/**
 * @property SupplierService $service
 */
class PriceSupplierController extends BasicPriceController
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index', 'view', 'create', 'update', 'template', 'admin', 'delete', 'rangeRow', 'templateRow'
                ],
                'roles' => [Users::ROLE_MANAGER],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PriceSupplierSearch();
        $searchModel->unsetAttributes();

        if (Yii::app()->request->getQuery('PriceSupplierSearch')) {
            $searchModel->attributes = $_GET['PriceSupplierSearch'];
        }

        $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::app()->request->getQuery('PriceSupplierSearch')),
            'currencies' => CHtml::listData(PriceCurrency::model()->findAll(), 'id', 'name'),
        ]);
    }

    /**
     * @param integer $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new PriceSupplierForm();

//         $this->performAjaxValidation($form);

        if ($form->load($_POST) && $form->validate()) {
            try {
                $supplier = $this->service->create($form);
                $this->redirect(['view', 'id' => $supplier->id]);
            } catch (CException $e) {
                // @todo flash and log error
            }
        }

        $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param $id
     * @throws CHttpException
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function actionUpdate($id)
    {
        $supplier = $this->loadModel($id);

        $form = new PriceSupplierForm($supplier);

        if ($form->load($_POST) && $form->validate()) {
            try {
                $this->service->edit($id, $form);
                $this->redirect(['view', 'id' => $supplier->id]);
            } catch (CException $e) {
                // @todo flash
            }
        }

        $this->render('update', [
            'model' => $form,
            'supplier' => $supplier,
        ]);
    }

    /**
     * @param integer $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!Yii::app()->request->getQuery('ajax')) {
            $this->redirect(
                Yii::app()->request->getPost('returnUrl')
                    ? Yii::app()->request->getPost('returnUrl')
                    : ['index']
            );
        }
    }

    /**
     * @param integer $id
     * @return CActiveRecord|PriceSupplier
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        if (!$model = PriceSupplier::model()->findByPk($id)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PriceSupplierForm $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'supplier-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @param integer $total
     * @throws CException
     */
    public function actionRangeRow($total)
    {
        Yii::import('bootstrap.widgets.TbActiveForm');
        $this->renderPartial('_form_range', [
            'form' => new TbActiveForm(),
            'index' => $total,
            'range' => new PriceRangeForm(),
        ]);
    }

    /**
     * @param integer $total
     * @throws CException
     */
    public function actionTemplateRow($total)
    {
        Yii::import('bootstrap.widgets.TbActiveForm');
        $this->renderPartial('_form_template', [
            'form' => new TbActiveForm(),
            'index' => $total,
            'template' => new PriceTemplateForm(),
        ]);
    }

    protected function getService()
    {
        $suppliers = new PriceSupplierRepository();
        $currencies = new PriceCurrencyRepository();
        $transaction = new TransactionManager();
        return new SupplierService($suppliers, $currencies, $transaction);
    }
}
