<?php

class TemplateController extends Controller
{


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Emailtemplate']))
		{
                    //echo '<pre>';print_r($_POST);echo '</pre>';
			$model->attributes=$_POST['Emailtemplate'];
			if($model->save())
				 Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                    //exit;
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
                 $criteria->addInCondition('id_country', $arrayRowId);
                                
                if(CActiveRecord::model('Emailtemplate')->deleteAll($criteria))
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
            $criteria->condition='t.id_country IN ( '.implode(",",$input).' )';
            $states=State::model()->findAll($criteria); 
            if(sizeof($states)>0)
            {
                $items="";
                $input=array_flip($input);
                foreach($states as $state):
                    $items.=$prefix.strip_tags($state->name);
                    $prefix=",";
                    unset($input[$state->id_country]);
                endforeach;
                $input=array_flip($input);

                Yii::app()->user->setFlash('alert',Yii::t('countries','warning_country', array('{details}'=>$items)));
            }
            return $input; 
        }
        
	public function actionIndex()
	{
            $model=new Emailtemplate('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Emailtemplate']))
                    $model->attributes=$_GET['Emailtemplate'];

            $this->render('index',array('model'=>$model));
	}

	public function loadModel($id)
	{
		$model=Emailtemplate::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'type':
                    if($data->type=='B'){
                        $return ="Both";    
                    }else if($data->type=='M'){
                        $return ="Mobile";    
                    }else if($data->type=='E'){
                        $return ="Email";    
                    }
                    
                    //$return.=$in;
                break;
        }
        return $return;
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
