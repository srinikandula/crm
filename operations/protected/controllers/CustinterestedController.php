<?php

class CustinterestedController extends Controller {
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
            Selltruckinterested::model()->updateAll(array($field=>$val),'id_sell_truck_interested="'.$id.'"');
            $json['status']=1;
        }
        echo CJSON::encode($json);
	Yii::app()->end();
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        //echo '<pre>';print_r($model);exit;
        if (isset($_POST['Orderstatus'])) {
            $model->attributes = $_POST['Orderstatus'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_sell_truck_interested', $arrayRowId);
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
        $model = new Selltruckinterested('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Selltruckinterested']))
            $model->attributes = $_GET['Selltruckinterested'];

        $this->render('index', array('model' => $model,'dataSet'=>$model->search()));
    }

    public function loadModel($id) {
        $model = Orderstatus::model()->findByPk($id);
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
                    $return="<a href='".$this->createUrl('custinterested',array('Selltruckinterested[id_sell_truck]'=>$data['id_sell_truck']))."'</a>".$data['interested']."</a>";
                    break;
                
                case 'id_truck_type':
                    $return=$this->truckType[$data['id_truck_type']];
                    break;
                
                case 'truck_front_pic':
                    $return="";    
                    $return='<div  id="image-name-display-id">Front Pic<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->truck_front_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Front Tyre Left Pic<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_front_left_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Front Tyre Right Pic<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_front_right_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Back Tyre Left Pic<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_back_left_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Back Tyre Right Pic<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->tyres_back_right_pic.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Other Pic 1<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->other_pic_1.'"></div></div>';
                    
                    $return.='<div  id="image-name-display-id">Other Pic 2<div class="logo-img"><img   src="'.Library::getTruckSellUploadLink().$data->other_pic_2.'"></div></div>';
                    
                    break;
                
                case 'status';
                    //echo "here";
                    $one=$data['status']==1?"selected":"";
                    $zero=$data['status']==0?"selected":"";
                    $select='<select name="status" id="status" onchange="fnchange(this,'.$data['id_sell_truck_interested'].')"><option value="1" '.$one.' >Processing</option><option value="0"  '.$zero.'  >Cancelled</option></select>';
  
                    $return=$select;//$this->admins[$data['id_admin_assigned']]['name'];
                    break;
            }
            return $return;
        }

}
