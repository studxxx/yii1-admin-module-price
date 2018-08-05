<?php

/**
 * Class ImportController
 */
class ImportController extends PriceController
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            'ajaxOnly + products,price', // we only allow deletion via POST request
        ];
    }

    public function actions()
    {
        return [
            'products' => [
                'class' => 'app.actions.WorkerAction',
                'performer' => 'ext.behaviors.products.ImportProductsBehavior',
                'worker' => Yii::app()->params['gearman']['worker'],
            ],
            'price' => [
                'class' => 'app.actions.WorkerAction',
                'performer' => 'ext.behaviors.price.ImportGearman',
                'worker' => Yii::app()->params['gearman']['worker'],
            ]
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index', 'view', 'create', 'update', 'delete',
                    'products', 'items', 'price'
                ],
                'roles' => [Users::ROLE_ADMIN],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    /**
     * @param integer $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->getPage();

        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionCreate()
    {
        $this->getPage();

        $model = new Import;

        if (isset($_POST['Import'])) {
            $model->attributes = $_POST['Import'];
            $model->name = CUploadedFile::getInstance($model, 'name');
            if ($model->save()) {
                $model->name
                    ->saveAs(
                        Helpers::getPublicPath(Yii::app()->config->get('IMPORT.PATH_UPLOAD')) . $model->name
                    );
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @throws CDbException
     * @throws CException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl'])
                ? $_POST['returnUrl']
                : array('index'));
        }
    }

    public function actionIndex()
    {
        $model = new Import('search');
        $model->unsetAttributes();
        if (isset($_GET['Import'])) {
            $model->attributes = $_GET['Import'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return Import|CActiveRecord
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Import::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Import $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'import-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
