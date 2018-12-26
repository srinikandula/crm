<?php

class NotificationController extends Controller {
    
    public function accessRules()
    {
        return $this->addActions(array('verify'));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['Country'])) {
            $model->attributes = $_POST['Country'];
            if ($model->save())
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_notification', $arrayRowId);

            if (CActiveRecord::model('Notification')->deleteAll($criteria)) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
            }
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }

        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    

    public function actionIndex() {
		$r=Admin::model()->getFieldTeamDeviceID();
        echo '<pre>';print_r($r);exit;
        $model = new Notification('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Notification']))
            $model->attributes = $_GET['Notification'];

        $this->render('index', array('model' => $model));
    }
    
    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'verified':
                $yes=$data->verified==1?"selected":"";
                $no=$data->verified==0?"selected":"";
                $return ='<select name="verified" id="verified" onchange="fnchange('.$data->id_notification.',this.value)"><option '.$yes.' value="1">Yes</option><option value="0" '.$no.' >No</option>';
                break;
            
            case 'type':
                if($data->type=='L'){
                    $return = 'Load';
                }else if($data->type=='T'){
                    $return = 'Truck';
                }else if($data->type=='C'){
                    $return = 'Commission Agent';
                }else if($data->type=='G'){
                    $return = 'Guest';
                }
                break;
            case 'action':
                    $val=unserialize($data->url);
                   //echo '<pre>'.$data->url; print_r($val);echo '</pre>';//exit;
                    switch ($data->code){
                        case 'AT':
                        case 'MT':
                            $return='<a target="_blank" href="'.$this->createUrl('truck/update',array('id'=>$val['id'],'cid'=>$data->id_customer)).'">'.$data->action.'</a>';
                            break;
                        
                        case 'AL':
                        case 'ML':
                            $return='<a target="_blank" href="'.$this->createUrl('load/update',array('id'=>$val['id'],'cid'=>$data->id_customer)).'">'.$data->action.'</a>';
                            break;
                        
                        case 'AP':
                        case 'MP':    
                            $customer=array("L"=>"loadowner","C"=>"cagent","G"=>"guest","T"=>"truckowner");
                            $return='<a target="_blank" href="'.$this->createUrl($customer[$data->type].'/update',array('id'=>$val['id'])).'">'.$data->action.'</a>';
                            break;
                        
                    }
                break;
            
        }
        return $return;
    }

    public function loadModel($id) {
        $model = Country::model()->findByPk($id);
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
    
    public function actionVerify() {
	//echo '<pre>';print_r($_POST);echo '</pre>';
        //exit($_GET['term']);

	if (isset($_POST['id']) && isset($_POST['val'])) {
            Yii::app()->db->createCommand("update {{notification}} set verified='".(int)$_POST['val']."' where id_notification='".(int)$_POST['id']."'")->query();    
        }
        //echo CJSON::encode($res);
	Yii::app()->end();
    }
}
