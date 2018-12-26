<?php

class TollgateController extends Controller
{


	public function actionCreate()
	{
		$model=new Tollgateinfo;
		//if(isset($_POST['Tollgateinfo']))
		if(Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['Tollgateinfo'];
                        $src = Library::getGPDetails($_POST['Tollgateinfo']['source_city']);
            $src1 = Library::getGPDetails($_POST['Tollgateinfo']['destination_city']);
            $model->source_address = trim($src['address']);
            $model->destination_address = trim($src1['address']);
            $model->source_state = trim($src['state']);
            $model->destination_state = trim($src1['state']);
            $model->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
            $model->destination_city = trim($src1['city']) == "" ? trim($src1['input']) : trim($src1['city']);
            $model->source_lat = trim($src['lat']);
            $model->destination_lat = trim($src1['lat']);
            $model->source_lng = trim($src['lng']);
            $model->destination_lng = trim($src1['lng']);
			if($model->validate()){
				$model->save(false);
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
			}
		}
		$this->render('create',array('model'=>$model));
	}

	 public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Tollgateinfo'];
            $src = Library::getGPDetails($_POST['Tollgateinfo']['source_city']);
            $src1 = Library::getGPDetails($_POST['Tollgateinfo']['destination_city']);
            $model->source_address = trim($src['address']);
            $model->destination_address = trim($src1['address']);
            $model->source_state = trim($src['state']);
            $model->destination_state = trim($src1['state']);
            $model->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
            $model->destination_city = trim($src1['city']) == "" ? trim($src1['input']) : trim($src1['city']);
            $model->source_lat = trim($src['lat']);
            $model->destination_lat = trim($src1['lat']);
            $model->source_lng = trim($src['lng']);
            $model->destination_lng = trim($src1['lng']);
            
            if ($model->validate()){
				$model->save(false);
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
			}
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete()
	{
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
                $arrayRowId=$this->findRelated($arrayRowId);
                
                $criteria=new CDbCriteria;
                 $criteria->addInCondition('id_toll_gate_info', $arrayRowId);
                                
                if(CActiveRecord::model('Tollgateinfo')->deleteAll($criteria))
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
            $criteria->condition='t.id_toll_gate_info IN ( '.implode(",",$input).' )';
            $states=Tollgateinfo::model()->findAll($criteria); 
            
            return $input; 
        }

        
    
	public function actionIndex()
	{
               
                                
            $model=new Tollgateinfo('search');
            $model->unsetAttributes(); 
        if(isset($_GET['Tollgateinfo']))
                    $model->attributes=$_GET['Tollgateinfo'];

            $this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
            
		$model=Tollgateinfo::model()->findByPk($id);
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
