<?php

class TrucksforsaleController extends Controller {
    
    public $truckType;
    public function accessRules() {
        return $this->addActions(array('updateField'));
    }
    
    public function actionCreate() {
        $model = new Orderstatus;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Orderstatus'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
        }
        //echo '<pre>';print_r($model);exit;
        $this->render('create', array('model' => $model));
    }
    
    public function actionupdateField() {
        $field=$_POST['field'];
        $id=(int)$_POST['id'];
        $val=$_POST['val'];
        $json['status']=0;
        //echo '<pre>';print_r($_POST);exit;
        if ($val!="" && $field!="" && $id!=0 &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Selltruck::model()->updateAll(array($field=>$val),'id_sell_truck="'.$id.'"');
            $json['status']=1;
        }
        echo CJSON::encode($json);
	Yii::app()->end();
    }

    /*public function actionUpdate($id) {
        $model = $this->loadModel($id);
        //echo '<pre>';print_r($model);exit;
        if (isset($_POST['Orderstatus'])) {
            $model->attributes = $_POST['Orderstatus'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
        }
        $this->render('update', array('model' => $model));
    }*/

	    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        //echo '<pre>';print_r($model);exit;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Selltruck'];
            
            if ($model->validate()){
                $model->date_modified=new CDbExpression('NOW()');
                //echo '<pre>';print_r($model->attributes);print_r($_POST);print_r($_FILES);exit;
                $uploadFields=array('truck_front_pic','truck_back_pic','tyres_front_left_pic','tyres_front_right_pic','tyres_back_left_pic','tyres_back_right_pic','other_pic_1','other_pic_2');    
                foreach($uploadFields as $uploadField){
                    $data = $_FILES[$uploadField];
                    $data['input']['prefix'] = $uploadField.'_' . $id . '_';
                    $data['input']['path'] = Library::getTruckSellUploadPath();
                    $data['input']['prev_file'] = $_POST['prev_'.$uploadField];
                    $upload = Library::fileUpload($data);
                    $model->$uploadField = $upload['file'];
                }    
                $model->save(false);//exit;
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_sell_truck', $arrayRowId);

            //delete profile images
            $custObjs = Selltruck::model()->findAll($criteria);
            foreach ($custObjs as $custObj) {
                unlink(Library::getTruckSellUploadPath() . $custObj->truck_front_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->truck_back_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->tyres_front_left_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->tyres_front_right_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->tyres_back_left_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->tyres_back_right_pic);
                unlink(Library::getTruckSellUploadPath() . $custObj->other_pic_1);
                unlink(Library::getTruckSellUploadPath() . $custObj->other_pic_2);
            }

            CActiveRecord::model('Selltruck')->deleteAll($criteria);
            CActiveRecord::model('Selltruckinterested')->deleteAll($criteria);
            
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        $ttrows=Trucktype::model()->findAll('status!=0');
        foreach($ttrows as $ttrow){
            $this->truckType[$ttrow->id_truck_type]=$ttrow->title;
        }
        $model = new Selltruck('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Selltruck']))
            $model->attributes = $_GET['Selltruck'];

        $this->render('index', array('model' => $model,'dataSet'=>$model->search()));
    }

    public function loadModel($id) {
        $model = Selltruck::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    protected function grid($data, $row, $dataColumn) {
            switch ($dataColumn->name) {
                case 'interested':
                    $return="<a href='".$this->createUrl('custinterested/index',array('Selltruckinterested[id_sell_truck]'=>$data['id_sell_truck']))."'</a>".$data['interested']."</a>";
                    break;
                case 'id_truck_type':
                        $return=$this->truckType[$data['id_truck_type']];
                    break;
                case 'truck_front_pic':
                    $return="";    
                    $return='<div  id="image-name-display-id">Front<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->truck_front_pic.'"></div></div>';
                    $return.='<div  id="image-name-display-id">Back<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->truck_back_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Tyre Left<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_front_left_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Tyre Right<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_front_right_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Tyre Left<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_back_left_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Tyre Right<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_back_right_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Other Pic 1<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->other_pic_1.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Other Pic 2<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->other_pic_2.'"></div></div>';
                    
                    break;
                
                case 'isactive';
                    //echo "here";
                    $one=$data['isactive']==1?"selected":"";
                    $zero=$data['isactive']==0?"selected":"";
                    $two=$data['isactive']==2?"selected":"";
                    $select='<select name="isactive" id="isactive" onchange="fnchange(this,'.$data['id_sell_truck'].')"><option value="1" '.$one.' >Active</option><option value="0"  '.$zero.'  >Inactive</option><option value="2"  '.$two.'  >Sold</option></select>';
  
                    $return=$select;//$this->admins[$data['id_admin_assigned']]['name'];
                    break;
            }
            return $return;
        }

}
