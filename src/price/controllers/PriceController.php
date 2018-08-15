<?php

class PriceController extends BasicPriceController
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
            'ajaxOnly + products,price',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'view', 'create', 'update', 'delete'],
                'roles' => [Users::ROLE_ADMIN],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PriceSearch();
        $searchModel->unsetAttributes();

        if (Yii::app()->request->getQuery('PriceSearch')) {
            $searchModel->attributes = Yii::app()->request->getQuery('PriceSearch');
        }

        $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::app()->request->getQuery('PriceSearch'))
        ]);
    }

    /**
     * @param $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->getPage();

        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * @throws CException
     */
    public function actionCreate()
    {
        $this->getPage();

        $model = new Price();

        if (Yii::app()->request->getPost('Price')) {
            $model->attributes = Yii::app()->request->getPost('Price');
            $model->price_file = CUploadedFile::getInstance($model, 'price_file');
            if ($model->save()) {
                $model->price_file
                    ->saveAs(
                        Helpers::getPublicPath(Yii::app()->config->get('IMPORT.PATH_UPLOAD')) . $model->price_file
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
            $this->redirect(
                Yii::app()->request->getPost('returnUrl')
                    ? Yii::app()->request->getPost('returnUrl')
                    : ['index']
            );
        }
    }

    /**
     * @param integer $id
     * @return Price|CActiveRecord
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        if (!$model = Price::model()->findByPk($id)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Price $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::app()->request->getPost('ajax') === 'price-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
