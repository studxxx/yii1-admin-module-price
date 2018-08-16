<?php

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
                'actions' => ['index', 'view', 'create', 'update', 'template', 'admin', 'delete'],
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
        $model = new PriceSupplier;

         $this->performAjaxValidation($model);

        if (Yii::app()->request->getPost('PriceSupplier')) {
            $model->attributes = Yii::app()->request->getPost('PriceSupplier');
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @todo loadMultiForm
     * @param integer $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $template = new TemplateForm();

        if (!empty($model->template)) {
            $template->attributes = $model->template;
        }

        if (Yii::app()->request->getPost('PriceSupplier')) {
            $model->attributes = Yii::app()->request->getPost('PriceSupplier');
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if (Yii::app()->request->getPost('TemplateForm')) {
            $template->attributes = Yii::app()->request->getPost('TemplateForm');
            if ($template->validate()) {
                $model->template = JSON::encode($template);
                $model->save();

                Yii::app()->end();
            } else {
                echo 'Error data';
                Yii::app()->end();
            }
        }

        $margin = new MarginForm();

        if (isset($_POST['MarginForm'])) {
            // Делаем переиндексацию и валидацию данных
            $result = [];
            foreach ($_POST['MarginForm'] as $data) {
                $margin->attributes = $data;

                if (!$margin->validate()) {
                    echo 'Error data';
                    Yii::app()->end();
                }
                if (!empty($data['condition']) && !empty($data['value'])) {
                    $result[] = $data;
                }
            }
            $model->margin = $result;

            if ($model->save()) {
                echo 1;
                Yii::app()->end();
            }
        }

        $this->render('update', [
            'model' => $model,
            'template' => $template,
            'margin' => $margin,
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
     * @param PriceSupplier $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'suppliers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
