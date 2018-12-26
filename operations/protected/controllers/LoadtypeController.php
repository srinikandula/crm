<?php

class LoadtypeController extends Controller
{


	public function actionCreate()
	{
		$model=new Loadtype;
		if(Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['Loadtype'];
			if($model->save())
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Loadtype']))
		{
			$model->attributes=$_POST['Loadtype'];
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
                $arrayRowId=$this->findRelated($arrayRowId);
                
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('id_load_type', $arrayRowId);
                                
                if(CActiveRecord::model('Loadtype')->deleteAll($criteria))
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
	
        public function findRelated($input)
        {
            $criteria=new CDbCriteria;
            $criteria->condition='t.id_load_type IN ( '.implode(",",$input).' )';
            $states=State::model()->findAll($criteria); 
            if(sizeof($states)>0)
            {
                $items="";
                $input=array_flip($input);
                foreach($states as $state):
                    $items.=$prefix.strip_tags($state->name);
                    $prefix=",";
                    unset($input[$state->id_load_type]);
                endforeach;
                $input=array_flip($input);

                Yii::app()->user->setFlash('alert',Yii::t('countries','warning_country', array('{details}'=>$items)));
            }
            return $input; 
        }
        
	public function actionIndex()
	{
               
                                
            $model=new Loadtype('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Loadtype']))
                    $model->attributes=$_GET['Loadtype'];

            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=Loadtype::model()->findByPk($id);
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
