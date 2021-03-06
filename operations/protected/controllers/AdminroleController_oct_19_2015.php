<?php
class AdminroleController extends Controller
{
        public function getAdminPermissions()
        {
            $rows=AdminPermissions::model()->findAll('id_admin_role=1');
            $modules=array();
            foreach($rows as $row)
            {
                
                $modules[$row->module_name][]=array('file'=>array("title"=>$row->title,"file_name"=>$row->file_name,"listall"=>$row->listall,"view"=>$row->view,"add"=>$row->add,"edit"=>$row->edit,"trash"=>$row->trash,"file_sort_order"=>$row->file_sort_order),'menu_type'=>$row->menu_type,"module_sort_order"=>$row->module_sort_order);
            }
            return $modules;
            /*echo '<pre>';
            print_r($modules);
            echo '</pre>';*/
        }
        
	public function actionCreate()
	{
		$model=new AdminRole;
		if (Yii::app()->request->isPostRequest)
		{
                    $model->attributes=$_POST['AdminRole'];
                    if($model->validate())
                    {
                        $model->save(false);
                        
                        foreach($_POST['permissions'] as $k=>$v)
                        {
                            foreach($v as $subK=>$subV)
                            {
                                if($subV['view']=='' && $subV['add']=='' && $subV['edit']=='' && $subV['trash']=='')
                                {
                                    continue;
                                }
                                $insert=new AdminPermissions;
                                $insert->id_admin_role=$model->id_admin_role;
                                $insert->title=$subV['title'];
                                $insert->module_name=$k;
                                $insert->file_name=$subK;
                                $insert->view=$subV['view']!=''?'Y':'N';
                                $insert->add=$subV['add']!=''?'Y':'N';
                                $insert->edit=$subV['edit']!=''?'Y':'N';
                                $insert->trash=$subV['trash']!=''?'Y':'N';
                                $insert->file_sort_order=$subV['file_sort_order'];
                                $insert->module_sort_order=$subV['module_sort_order'];
                                $insert->menu_type=$subV['menu_type'];
								$insert->status=1;
                                $insert->save(false);
                                //print_r($insert->getAttributes());
                            }
                        }
                        Yii::app()->user->setFlash('success',Yii::t('common','message_create_success'));
                        $this->redirect('index');
                    }
                }
		$this->render('create',array('model'=>$model,'permissions'=>$this->getAdminPermissions()));
	}

	public function actionUpdate($id)
	{
            
            $modelArray=$this->loadModel($id);
            $model=$modelArray[0];
            if (Yii::app()->request->isPostRequest)
            {
                    //echo '<pre>';print_r($_POST);echo '</pre>';exit;
                    AdminPermissions::model()->deleteAll('id_admin_role='.$id);
                    
                    foreach($_POST['permissions'] as $k=>$v)
                    {
                        foreach($v as $subK=>$subV)
                        {
                            if($subV['view']=='' && $subV['add']=='' && $subV['edit']=='' && $subV['trash']=='')
                            {
                                continue;
                            }
                            $insert=new AdminPermissions;
                            $insert->id_admin_role=$id;
                            $insert->title=$subV['title'];
                            $insert->module_name=$k;
                            $insert->file_name=$subK;
                            $insert->view=$subV['view']!=''?'Y':'N';
                            $insert->listall=$subV['listall']!=''?'Y':'N';
                            $insert->add=$subV['add']!=''?'Y':'N';
                            $insert->edit=$subV['edit']!=''?'Y':'N';
                            $insert->trash=$subV['trash']!=''?'Y':'N';
                            $insert->file_sort_order=$subV['file_sort_order'];
                            $insert->module_sort_order=$subV['module_sort_order'];
                            $insert->menu_type=$subV['menu_type'];
							$insert->status=1;
                            $insert->save(false);
                            //print_r($insert->getAttributes());
                        }
                    }
                   // exit;
                    $model->attributes=$_POST['AdminRole'];
                    if($model->validate())
                    {
                        $model->save(false);
                        Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                        $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                    }
            }
            
            
            $this->render('update',array('model'=>$model,'permissions'=>$this->getAdminPermissions(),'result'=>$modelArray[1]));
	}
	
	public function actionDelete()//($id)
	{
            $arrayRowId=  is_array(Yii::app()->request->getParam('id'))?Yii::app()->request->getParam('id'):array(Yii::app()->request->getParam('id'));
            if(sizeof($arrayRowId)>0)
            {
                $criteria=new CDbCriteria;
                $criteria->addInCondition('id_admin_role', $arrayRowId);
                                
                if(CActiveRecord::model('AdminRole')->deleteAll($criteria) && CActiveRecord::model('AdminPermissions')->deleteAll($criteria))
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
		$model=new AdminRole('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admin']))
                    $model->attributes=$_GET['Admin'];

		$this->render('index',array('model'=>$model,'dataSet'=>$model->search()));
	}


	public function loadModel($id)
	{
		$model=AdminRole::model()->findByPk($id);
                $rows=  AdminPermissions::model()->findAll('id_admin_role='.$id);
                $result=array();
                foreach($rows as $row)
                {
                    $result[$row->module_name][$row->file_name]['view']=$row->view;
                    $result[$row->module_name][$row->file_name]['listall']=$row->listall;
                    $result[$row->module_name][$row->file_name]['add']=$row->add;
                    $result[$row->module_name][$row->file_name]['edit']=$row->edit;
                    $result[$row->module_name][$row->file_name]['trash']=$row->trash;
                }
                /*echo '<pre>';
                print_r($result);
                exit;*/
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return array($model,$result);
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
