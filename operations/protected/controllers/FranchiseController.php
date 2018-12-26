<?php

class FranchiseController extends Controller
{
        public function actionCreate()
	{
            $model=new Franchise;
            //if(isset($_POST['Franchise']))
            if(Yii::app()->request->isPostRequest)
            {
                $model->attributes=$_POST['Franchise'];
                if($model->save()){
                    Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                    $this->redirect('index');
                }
            }
            $this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Franchise']))
		{
			$model->attributes=$_POST['Franchise'];
			if($model->save())
				 Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
		}
		$this->render('update',array('model'=>$model));
	}

    public function actionDelete()//($id)
	{
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
                //$arrayRowId=$this->findRelated($arrayRowId);
                
                $criteria=new CDbCriteria;
                $criteria->addInCondition('id_franchise', $arrayRowId);
                                
                if(CActiveRecord::model('Franchise')->deleteAll($criteria))
                {
                    Yii::app()->user->setFlash('success',Yii::t('common','message_delete_success'));
                }else
                {
                    Yii::app()->user->setFlash('error',Yii::t('common','message_delete_fail'));
                }
            }else
            {
                Yii::app()->user->setFlash('alert',Yii::t('common','message_checkboxValidation_alert'));
                Yii::app()->user->setFlash('error',Yii::t('common','message_delete_fail'));
            }
        
            if(!isset($_GET['ajax']))
                $this->redirect(base64_decode (Yii::app()->request->getParam('backurl')));
	}
	
        public function actionIndex()
	{
            $model=new Franchise('search');
            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Franchise']))
                $model->attributes=$_GET['Franchise'];
            //echo '<pre>';print_r($model);exit;
            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=Franchise::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='country-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
