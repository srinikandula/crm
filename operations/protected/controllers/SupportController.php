<?php

class SupportController extends Controller {
    
    public function accessRules()
    {
        return $this->addActions(array('verify'));
    }

    public function actionUpdate($id) {
        $rows = $this->loadModel($id);
        //echo Yii::app()->user->id.'<pre>';print_r($_SESSION);print_r($model);exit;
        $model=new Ordercustomersupport();
        if (isset($_POST['Ordercustomersupport'])) {
            //echo '<pre>';print_r($_GET);print_r($_POST);exit;
            $model->attributes = $_POST['Ordercustomersupport'];
            $model->id_admin=Yii::app()->user->id;
            $model->id_customer=(int)$_GET['idc'];
            $model->id_order=(int)$_GET['id'];
            $model->details=$_POST['Ordercustomersupport']['details'];
            $model->status=$_POST['Ordercustomersupport']['status'];
            if ($model->validate()){
                $model->save(false);
                $obj=Customer::model()->find('id_customer='.(int)$_GET['idc']);    
                if($obj->enable_sms_email_ads)
                {    
                    $data = array('id' => '7', 'replace' => array('%customer_name%' => $obj->fullname,'%order_id%' => (int)$_GET['id'],'%message%' => $_POST['Ordercustomersupport']['details']), 'mail' => array("to" => array($obj->email => $obj->fullname)));
                    Mail::send($data);
                }
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
        }
        
        $this->render('update', array('model'=>$model,'rows' => $rows));
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
        $model = new Ordercustomersupport('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Ordercustomersupport']))
            $model->attributes = $_GET['Ordercustomersupport'];

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
        $model['p']=Yii::app()->db->createCommand("select s.id_order_customer_support,s.date_created,s.id_customer,s.id_admin,s.details,s.status,s.type_of_issue,(select concat(first_name,' ',last_name) from {{admin}} a where a.id_admin=s.id_admin ) as admin,(select concat(fullname,' _ ',type) from {{customer}} c where c.id_customer=s.id_customer ) as customer from {{order_customer_support}} s where s.id_order='".(int)$_GET['id']."' and s.id_customer='".(int)$_GET['idc']."' order by s.date_created desc")->queryAll();
        
        $model['c']=Yii::app()->db->createCommand("select s.id_order_customer_support,s.date_created,s.id_customer,s.id_admin,s.details,s.status,s.type_of_issue,(select concat(first_name,' ',last_name) from {{admin}} a where a.id_admin=s.id_admin ) as admin,(select concat(fullname,' _ ',type) from {{customer}} c where c.id_customer=s.id_customer ) as customer from {{order_customer_support}} s where s.id_order='".(int)$_GET['id']."' and s.id_customer!='".(int)$_GET['idc']."' order by s.date_created desc")->queryAll();
        
        $model['0'] = Order::model()->find(array("condition" => "id_order=" . (int)$_GET['id']));
        //$model['oh'] = Orderhistory::model()->find(array("condition" => "id_order=" . $id));
        $model['oh'] = new Orderhistory();
        
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
