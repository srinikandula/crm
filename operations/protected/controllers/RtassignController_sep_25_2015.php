<?php

class RtassignController extends Controller
{   
        public $admins;
        public $adminOptions;
        public function actionUpdate($id)
	{
            //echo '<pre>';print_r($_POST);exit;
            $aid=(int)$_GET['aid'];
            $id=(int)$_GET['id'];
            $json=array();
            $json['status']=0;
            if (Yii::app()->request->getIsAjaxRequest() && $aid && $id ) {
                Loadtruckrequest::model()->updateAll(array('id_admin_assigned'=>$aid),'id_load_truck_request='.$id);
                $json['status']=1;
            }
            echo CJSON::encode($json);
            Yii::app()->end();
	}

        
	public function actionIndex()
	{
            
            $criteria=new CDbCriteria;
            $criteria->select='t.*,ar.role as admin_role';
            $criteria->join='inner join {{admin_role}} ar on t.id_admin_role=ar.id_admin_role';
            $adminRows=Admin::model()->findAll($criteria);   
            $admins=array();
            foreach($adminRows as $adminRow){
                $admins[$adminRow->id_admin]=array('name'=>$adminRow->first_name." ".$adminRow->last_name,'role'=>$adminRow->admin_role);
            }            
            $this->admins=$admins;
            
            $adminOptions=array();
            foreach($adminRows as $adminRow){
                $adminOptions[$adminRow->admin_role][$adminRow->id_admin]=$adminRow->first_name." ".$adminRow->last_name;
            }
            $this->adminOptions=$adminOptions;
            //echo '<pre>';print_r($this->adminOptions);exit;
            $model=new Loadtruckrequest('Assignment');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Loadtruckrequest']))
                    $model->attributes=$_GET['Loadtruckrequest'];
            //exit;
            $this->render('index',array('model'=>$model));
	}


	public function loadModel($id)
	{
		$model=Trucktype::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        protected function grid($data, $row, $dataColumn) {
            switch ($dataColumn->name) {
                case 'id_admin_created';
                    
                    $return=$this->admins[$data['id_admin_created']]['name'];
                    break;
                case 'id_admin_assigned';
                    //echo '<pre>';print_r($this->adminOptions);exit;
                    $select='<select name="select" onchange="fnchange(this,'.$data['id_load_truck_request'].')">';
                    foreach($this->adminOptions as $role=>$admins){
                        $select.='<optgroup label="'.$role.'">';
                        foreach($admins as $id=>$name){
                            $selected=$data['id_admin_assigned']==$id?"selected":"";
                            $select.='<option value="'.$id.'" '.$selected.' >'.$name.'</option>';    
                        }
                        $select.='</optgroup>';
                    }
                    $select.='</select>';
  
                    $return=$select;//$this->admins[$data['id_admin_assigned']]['name'];
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