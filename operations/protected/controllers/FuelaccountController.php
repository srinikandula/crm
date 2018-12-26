<?php

class FuelaccountController extends Controller {

    public function accessRules() {
        return $this->addActions(array('deletefuelcard'));
    }
    
    public function actionCreate() {
        $model['c'] = new Customer;
        if (Yii::app()->request->isPostRequest) {
            $model['c']->attributes = $_POST['Customer'];
            if (($_POST['Customer_reg_type']==1 && $_POST['Customer']['idprefix']!="") || ($_POST['Customer_reg_type']==0 && $_POST['Customer']['mobile']!="")) {
                //$model['fc']->save();
                if($_POST['Customer_reg_type']){
                    $exp=explode(",",$_POST['Customer']['idprefix']);
                    $input['mobile'] = $exp[0];   
                }else{
                    $input['mobile'] = $_POST['Customer']['mobile'];
                }
                //echo '<pre>';print_r($_POST);
                $input['id_franchise'] = $_POST['Customer']['id_franchise'];
                $input['fullname'] = $_POST['Customer_reg_type']==1?$exp[2]:$_POST['Customer']['fullname'];
                
                $input['card_customer_no'] = $_POST['Customer']['card_customer_no'];
                $input['card_username'] = $_POST['Customer']['card_username'];
                $input['card_password'] = $_POST['Customer']['card_password'];
                $input['card_status'] = $_POST['Customer']['card_status'];
                $input['card_cashback_percent'] = $_POST['Customer']['card_cashback_percent'];
				$input['date_fuel_card_applied'] = $_POST['Customer']['date_fuel_card_applied'];
                //print_r($input);
                //exit;
                $this->createCustomer($input);
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                $this->redirect('index');
            }
        }

        $model['cust_list'] = Customer::model()->findAll('applied_fuel_card=0');
        $this->render('create', array('model' => $model));
    }

    public function createCustomer($data) {

        $findCObj = Customer::model()->find('mobile="' . $data['mobile'] . '"');
        if (!is_object($findCObj)) {
            //exit("in if".'mobile="'.$data[contactPhone].'" and type!="G"');
            $custObj = new Customer;
            $custObj->islead = 0;
            $custObj->id_franchise = $data['id_franchise'];
            $custObj->type = 'T';
            $custObj->fullname = $data['fullname'];
            $custObj->date_created = new CDbExpression('NOW()');
            $custObj->mobile = $data['mobile'];
            $custObj->status = 1;
            $custObj->approved = 1;
            $custObj->card_customer_no = $data['card_customer_no'];
            $custObj->card_username = $data['card_username'];
            $custObj->card_password = $data['card_password'];
            $custObj->card_status = $data['card_status'];
            $custObj->card_cashback_percent = $data['card_cashback_percent'];
			$custObj->date_fuel_card_applied = $data['date_fuel_card_applied'];
            $custObj->applied_fuel_card=1;
            $custObj->save(false);

            $idprefix = Library::getIdPrefix(array('id' => $custObj->id_customer, 'type' => 'T'));
            Customer::model()->updateAll(array('idprefix' => $idprefix), 'id_customer="' . $custObj->id_customer . '"');
            $custLeadObj = new Customerlead;
            $custLeadObj->id_customer = $custObj->id_customer;
            $custLeadObj->lead_source = '';
            $custLeadObj->lead_status = 'Initiated';
            $custLeadObj->save(false);
            //exit(" in if");
        } else {
            Customer::model()->updateAll(array('applied_fuel_card'=>1,'card_status' => $data['card_status'], 'card_customer_no' => $data['card_customer_no'], 'card_username' => $data['card_username'], 'card_password' => $data['card_password'], 'card_cashback_percent' => $data['card_cashback_percent'], 'mobile' => $data[mobile], 'fullname' => $data['fullname']), 'id_customer="' . $findCObj->id_customer . '"');
        }
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['Customer'])) {
            //echo '<pre>';print_r($_POST);EXIT;
            $model['c']->attributes = $_POST['Customer'];
            
            if ($model['c']->save(false)) {
                foreach($_POST['Truck'] as $k=>$v){
                    $id_fuel_card=(int)$v['id_fuel_card'];
                    if($id_fuel_card && $v['card_no']!=""){
                            Fuelcard::model()->updateAll(array('card_no'=>$v['card_no'],'vehicle_no'=>$v['vehicle_no'],'issue_date'=>$v['issue_date'],'expiry_date'=>$v['expiry_date'],'status'=>$v['status']),'id_fuel_card='.$id_fuel_card);
                    }else if(!$id_fuel_card){
                        $fCard=new Fuelcard;
                        $fCard->id_customer=$id;
                        $fCard->card_no=$v['card_no'];
                        $fCard->vehicle_no=$v['vehicle_no'];
                        $fCard->issue_date=$v['issue_date'];
                        $fCard->expiry_date=$v['expiry_date'];
                        $fCard->status=$v['status'];
                        $fCard->save(false);
                    }
                }
                //$model['c']->save();
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            //$arrayRowId=$this->findRelated($arrayRowId);

            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_fuel_card_account', $arrayRowId);

            if (CActiveRecord::model('Fuelcardaccount')->deleteAll($criteria)) {
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
    
    public function actiondeletefuelcard() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            //$arrayRowId=$this->findRelated($arrayRowId);

            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_fuel_card', $arrayRowId);

            if (CActiveRecord::model('Fuelcard')->updateAll(array('trash'=>1),$criteria)) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
            }
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        echo CJSON::encode(array('status'=>1));
        Yii::app()->end();
    }

    public function actionIndex() {
        /* $json='{"multicast_id":7157904352897436247,"success":17,"failure":0,"canonical_ids":7,"results":[{"registration_id":"APA91bHYBoP1ZXqKLAyttETCYU9BLtEa1rp8QMlJyJoxCUaekWwIovihDVTggScWgEZB_KaOgyfevAPxkVDFPdqudOlMhu81roNIauoVNIHtm4rhjRHxjVm4TtggW86MdPqb-rn7Pf7D","message_id":"0:1492190800455905%3e156f7af9fd7ecd"},{"registration_id":"APA91bFWY4QsC4MWb6bfpbW5TLrmD0Xczt8AjhyPqtX4c0PbNzU8XFw0zTmZqAkSAP2QW0T1oBlAflESK2Mx76icNDpWrQl_9Ij0UUA3FbiS0DQOClU4HaFWSMWWHJXbmWAGjK3R7s_7","message_id":"0:1492190800458429%3e156f7af9fd7ecd"},{"registration_id":"APA91bFWY4QsC4MWb6bfpbW5TLrmD0Xczt8AjhyPqtX4c0PbNzU8XFw0zTmZqAkSAP2QW0T1oBlAflESK2Mx76icNDpWrQl_9Ij0UUA3FbiS0DQOClU4HaFWSMWWHJXbmWAGjK3R7s_7","message_id":"0:1492190800458480%3e156f7af9fd7ecd"},{"registration_id":"APA91bHimRLjS7v_BCsQSs3MmTGCVj0ataLWhRyzI6MQTbLoRCnN9YoeQgWng3PLxEzexD_EX_e_u6sw_9weUrLNu18BXun8k1F1c5Tur5ixlbM6xAzG7n-Wcu8jifS9-VmI_HrBIPRv","message_id":"0:1492190800455001%3e156f7af9fd7ecd"},{"message_id":"0:1492190800456970%3e156f7a!f9fd7ecd"},{"message_id":"0:1492190800458427%3e156f7af9fd7ecd"},{"message_id":"0:1492190800459855%3e156f7af9fd7ecd"},{"registration_id":"APA91bEW8eEXyOcBEMil-N6je8apSuqhKzf3xLglETZZlrSjRIpGoTni2rXGkdAJasOkK51BImipnfJi53Yq-dI7T1ZW86vF9PX2B2e6vS7-TF5i3A2e_kdMmmI3xf45JzhnJygT6MoC","message_id":"0:1492190800457881%3e156f7af9fd7ecd"},{"registration_id":"APA91bGJN4KT_M1yFMvvEEj-dVUJJ5nmCs01mtam5wfQZQVQwdOy2su_Z6bfi6qEdeAjZyyUJlkUVeI5QRNAIEEuj34ZrYcsJk24sVnZFcr1Y3DYx10JCYlmKUqyHj1nj-AbTYHR9hBo","message_id":"0:1492190800460653%3e156f7af9fd7ecd"},{"registration_id":"APA91bHimRLjS7v_BCsQSs3MmTGCVj0ataLWhRyzI6MQTbLoRCnN9YoeQgWng3PLxEzexD_EX_e_u6sw_9weUrLNu18BXun8k1F1c5Tur5ixlbM6xAzG7n-Wcu8jifS9-VmI_HrBIPRv","message_id":"0:1492190800455786%3e156f7af9fd7ecd"},{"message_id":"0:1492190800459853%3e156f7af9fd7ecd"},{"message_id":"0:1492190800456851%3e156f7af9fd7ecd"},{"message_id":"0:1492190800456968%3e156f7af9fd7ecd"},{"message_id":"0:1492190800460702%3e156f7af9fd7ecd"},{"message_id":"0:!1492190800457883%3e156f7af9fd7ecd"},{"message_id":"0:149219080!0455788%3e156f7af9fd7ecd"},{"message_id":"0:1492190800455907%3e156f7af9fd7ecd"}]}';
          Gpslogindevice::updateDuplicates($json);exit; */
        $model = new Customer('searchFuelAccount');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];
        //echo '<pre>';print_r($model);exit;
        $this->render('index', array('model' => $model, 'dataSet' => $model->searchFuelAccount()));
    }

    public function loadModel($id) {
        $model['c'] = Customer::model()->findByPk($id);
        $model['t'] = Fuelcard::model()->findAll('trash=0 and id_customer='.$id);
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
}