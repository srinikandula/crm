<?php

class ProjectController extends Controller
{


	public function actionCreate()
	{
		$model=new Project;
		//if(isset($_POST['Project']))
		if(Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
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
                 $criteria->addInCondition('id_project', $arrayRowId);
                                
                if(CActiveRecord::model('Project')->deleteAll($criteria))
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
               
                                
            $model=new Project('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Project']))
                    $model->attributes=$_GET['Project'];

            $this->render('index',array('model'=>$model));
	}


	public function loadModel($id)
	{
		$model=Project::model()->findByPk($id);
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
        
        protected function grid($data, $row, $dataColumn) {
            switch ($dataColumn->name) {
                case 'id_customer':
                        if($data->project_by_admin==1){
                            $return='Admin';
                        }else{
                            $row=$this->getCompany($data->id_customer);
                            $return=$row->company;
                        }
                    break;
                case 'id_supplier':
                        if($data->id_supplier){
                            $row=$this->getCompany($data->id_supplier);
                            $return=$row->company;
                        }

				case 'id_project_bidding':
				$row=Yii::app()->db->createCommand("select count(*) as count from {{project_bidding}} where id_project='".$data->id_project."'")->queryRow();
				$return=$row['count'];
				break;
            }
            return $return;
        }
        
        public function getCompany($id){
           return Customer::model()->find('id_customer="'.$id.'"');
        }
}
