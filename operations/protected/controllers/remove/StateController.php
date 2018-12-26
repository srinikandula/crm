<?php

class StateController extends Controller
{


	public function actionCreate()
	{
		$model=new State;
		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['State'];
			if($model->save())
				Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
				$this->redirect('index');
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes=$_POST['State'];
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
                 $criteria->addInCondition('id_state', $arrayRowId);
                                
                if(CActiveRecord::model('State')->deleteAll($criteria))
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
            $criteria->condition='t.id_state IN ( '.implode(",",$input).' )';
            $regions=RegionList::model()->findAll($criteria); 
            if(sizeof($regions)>0)
            {
                $items="";
                $input=array_flip($input);
                foreach($regions as $region):
                    $items.=$prefix.strip_tags($region->id_state);
                    $prefix=",";
                    unset($input[$region->id_state]);
                endforeach;
                $input=array_flip($input);

               //Yii::app()->user->setFlash('alert','Cannot delete "'.$items.'" as some of the products are associated with it.');
				//Yii::t('states','warning_state', array('{details}'=>$items))."<br/>";
				Yii::app()->user->setFlash('alert',Yii::t('states','warning_state', array('{details}'=>$items)));
            }
            return $input; 
        }

	public function actionIndex()
	{
		$model=new State('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['State']))
			$model->attributes=$_GET['State'];

		$this->render('index',array('model'=>$model));
	}


	public function loadModel($id)
	{
		$model=State::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='state-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
