<?php

class OrderController extends Controller {

    public function accessRules() {
        return $this->addActions(array('updateStatus', 'updateAmount', 'modifyTruck', 'payAmount','addOrder'));
    }
    
    public function actionpayAmount($id) {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_POST);exit;
            $model = new Orderpaymenthistory();
            $data = array();
            $msg = "";
            $ord = Order::model()->find('id_order=' . $id);
            if (is_numeric($_POST['Orderpaymenthistory']['amount_'.$_GET['type']])) {
                $data['id_order'] = $id;
                $data['id_customer'] = (int)$_GET['cid'];
                $data['amount'] = $_POST['Orderpaymenthistory']['amount_'.$_GET['type']];
                $data['comment'] = $_POST['Orderpaymenthistory']['comment_'.$_GET['type']];
                $model->attributes = $data;
                $model->save();
            } else {
                $msg = "Invalid amount!!";
            }

            
            $orderHistory = "";
            $grandTotal = $ord->payable_amount;
            $comment='Order Amount';//$_GET['type']=='P'?'Payable Amount':'Receivable Amount';
            $orderHistory.='<tr>   <td>&nbsp;</td>
				   <td>'.$comment.'</td>
				   <td>' . $ord->amount . '</td>
			</tr>';
            foreach (Orderpaymenthistory::model()->findAll(array('condition' => 'id_customer='.(int)$_GET["cid"].' and id_order=' . $id, 'order' => 'id_order_payment_history desc')) as $history):
                $orderHistory.='<tr>
                                   <td>' . $history->date_created . '</td>
				   <td>' . $history->comment . '</td>
				   <td>' . $history->amount . '</td>
			</tr>';
                $grandTotal-=$history->amount;
            endforeach;
            $orderHistory.='<tr><td>&nbsp;</td>
				   <td>Pending Amount</td>
				   <td>' . $grandTotal . '</td>
			</tr>';
            $status=1;
            if ($msg != "") {
                $status=0;
                $orderHistory.='<tr><td colspan="3" style="color:red"><b>Invalid Amount</b></td></tr>';
            }
            //Order::model()->updateAll(array('pending_amount' => $grandTotal), 'id_order=' . $id);
            Yii::app()->user->setFlash('success', "Updated Successfully!!");
            echo $_GET['type']."---".$status."---".$orderHistory;
        }
    }

    /*public function actionpayAmount($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            //echo '<pre>';print_r($_POST);exit;
            $model = new Orderpaymenthistory();
            $data = array();
            $msg = "";
            $ord = Order::model()->find('id_order=' . $id);
            if (is_numeric($_POST['Orderpaymenthistory']['amount']) && $ord->pending_amount>=$_POST['Orderpaymenthistory']['amount'] ) {
                $data['id_order'] = $id;
                $data['amount'] = $_POST['Orderpaymenthistory']['amount'];
                $data['comment'] = $_POST['Orderpaymenthistory']['comment'];
                $model->attributes = $data;
                $model->save();
            } else {
                $msg = "Invalid amount!!";
            }

            
            $orderHistory = "";
            $grandTotal = $ord->payable_amount;
            $orderHistory.='<tr>
				   <td>Original Amount</td>
				   <td>' . $ord->amount . '</td>
			</tr>';
            foreach (Orderpaymenthistory::model()->findAll(array('condition' => 'id_order=' . $id, 'order' => 'id_order_payment_history desc')) as $history):
                $orderHistory.='<tr>
				   <td>' . $history->comment . '</td>
				   <td>' . $history->amount . '</td>
			</tr>';
                $grandTotal-=$history->amount;
            endforeach;
            $orderHistory.='<tr>
				   <td>Pending Amount</td>
				   <td>' . $grandTotal . '</td>
			</tr>';
            $status=1;
            if ($msg != "") {
                $status=0;
                $orderHistory.='<tr><td colspan="2" style="color:red"><b>Invalid Amount</b></td></tr>';
            }
            Order::model()->updateAll(array('pending_amount' => $grandTotal), 'id_order=' . $id);
            Yii::app()->user->setFlash('success', "Updated Successfully!!");
            echo $status."---".$orderHistory;
        }
    }*/

    public function actionmodifyTruck() {
        //echo '<pre>';print_r($_POST);
        //exit('hello');
        if (isset($_POST['truck_reg_no'])) {
            $qtxt = "SELECT id_truck,truck_reg_no FROM {{truck}} WHERE  truck_reg_no  LIKE :truck_no";
            $command = Yii::app()->db->createCommand($qtxt);
            $command->bindValue(":truck_no", $_POST['truck_reg_no'], PDO::PARAM_STR);
            $res = $command->queryRow();
            //echo '<pre>';print_r($res);exit;
            /*if ($res['id_truck']) {
                Yii::app()->db->createCommand('update {{order}} set id_truck="' . $res['id_truck'] . '" ,truck_reg_no="' . $res['truck_reg_no'] . '" where id_order=' . (int) $_POST['id'])->query();
                $output['success'] = 1;
            } else {
                $output['success'] = 0;
            }*/
            if ($res['id_truck']) { //registered truck
                Yii::app()->db->createCommand('update {{order}} set id_truck="' . $res['id_truck'] . '" ,truck_reg_no="' . $res['truck_reg_no'] . '" where id_order=' . (int) $_POST['id_order'])->query();
                $output['success'] = 1;
            } else { //unregistered truck
                Yii::app()->db->createCommand('update {{order}} set id_truck="0" ,truck_reg_no="' . trim($_POST['truck_reg_no']) . '" where id_order=' . (int) $_POST['id_order'])->query();

                $output['success'] = 1;
            }    
            
        }
        echo CJSON::encode($output);
        Yii::app()->end();
    }

    public function actionupdateAmount($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            //echo '<pre>';print_r($_POST);exit;
            $model = new Orderamount();
            $data = array();
            //$data=$_POST['OrderHistory'];
            $msg = "";
            if (is_numeric($_POST['Orderamount']['amount'])) {
                $data['id_order'] = $id;
                $data['amount_prefix'] = $_POST['Orderamount']['amount_prefix'];
                $data['amount'] = $_POST['Orderamount']['amount'];
                $data['comment'] = $_POST['Orderamount']['comment'];
                $model->attributes = $data;
                $model->save();
            } else {
                $msg = "Invalid amount!!";
            }

            $ord = Order::model()->find('id_order=' . $id);
            $orderHistory = "";
            $grandTotal = $ord->amount;
            $pending_amount = $ord->pending_amount;
            $orderHistory.='<tr>
                       <td>Original Amount</td>
                       <td>' . $ord->amount . '</td>
                </tr>';
            foreach (Orderamount::model()->findAll(array('condition' => 'id_order=' . $id, 'order' => 'id_order_amount desc')) as $history):
                $orderHistory.='<tr>
                       <td>' . $history->comment . '</td>
                       <td>' . $history->amount_prefix . $history->amount . '</td>
                </tr>';
                if ($history->amount_prefix == '+') {
                    $grandTotal+=$history->amount;
                    $pending_amount+=$history->amount;
                } else if ($history->amount_prefix == '-') {
                    $grandTotal-=$history->amount;
                    $pending_amount-=$history->amount;
                }
            endforeach;
            $orderHistory.='<tr>
                       <td>Grand Total</td>
                       <td>' . $grandTotal . '</td>
                </tr>';
            $status=1;
            if ($msg != "") {
                $status=0;
                $orderHistory.='<tr><td colspan="2" style="color:red"><b>Invalid Amount</b></td></tr>';
            }
            Order::model()->updateAll(array('pending_amount' => $pending_amount, 'payable_amount' => $grandTotal), 'id_order=' . $id);
            Yii::app()->user->setFlash('success', "Updated Successfully!!");
            echo $status."---".$orderHistory;
        }
    }

    public function actionUpdateStatus($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            //echo '<pre>';print_r($_POST);exit;
            $exp = explode("#", $_POST['Orderhistory']['title']);
            //print_r($exp);
            //exit;
            Order::model()->updateAll(array('id_order_status' => $exp[0], 'order_status_name' => $exp[1]), 'id_order="' . $id . '"');
            $model = new Orderhistory();
            $data = array();
            //$data=$_POST['OrderHistory'];
            $data['id_order'] = $id;
            //$data['date_created']=new CDbExpression('NOW()');
            $data['id_order_status'] = $exp[0];
            $data['title'] = $exp[1];
            $data['notified_by_customer'] = $_POST['Orderhistory']['notified_by_customer'];
            $data['message'] = $_POST['Orderhistory']['message'];

            $model->attributes = $data;
            $model->save();
            //echo '<pre>';print_r($data);echo '</pre>';exit;
            $orderHistory = "";
            foreach (Orderhistory::model()->findAll(array('condition' => 'id_order=' . $id, 'order' => 'date_created desc')) as $history):
                $notifyCustomer = $history->notified_by_customer == '1' ? 'Yes' : 'No';
                $orderHistory.='<tr>
                       <td>' . $history->date_created . '</td>
                       <td>' . $history->title . '</td>
                       <td>' . $history->message . '</td>
                       <td>' . $notifyCustomer . '</td>
                </tr>';
            endforeach;
            if ($_POST['Orderhistory']['notified_by_customer']) {
                $orderRow = Order::model()->find('id_order=' . $id);

                $data['from'] = Yii::app()->config->getData('CONFIG_STORE_SUPPORT_EMAIL_ADDRESS');
                $data['to'] = $orderRow->customer_email;
                $data['subject'] = 'EasyGaadi.com Order Status Updated to ' . $exp[1] . '!!';
                $data['message'] = $_POST['Orderhistory']['message'];

                Library::sendMail($data);
            }
            echo $orderHistory;
        }
    }

    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $row = Customer::model()->find('id_customer=' . $id);
            if (!$row->approved) {
                $data['from'] = Yii::app()->config->getData('CONFIG_STORE_SUPPORT_EMAIL_ADDRESS');
                $data['to'] = $row->email;
                $data['subject'] = 'EasyGaadi.com Account Approved!!';
                $data['message'] = 'Congratulations,Your account got verified and approved by our team!!';
                Library::sendMail($data);
                $approved = 1;
                $row->approved = 1;
                $row->save(false);
            }
        }

        if ($approved) {
            Yii::app()->user->setFlash('success', 'Selected Guest approved successfully!!');
        }
        $this->redirect('index');
    }
    
    public function actionAddorder(){
                $TruckroutepriceRow=Yii::app()->db->createCommand("select trp.*,tr.id_truck,tr.truck_reg_no,tr.description,tr.id_customer,tr.id_truck_type,tr.tracking_available,tr.insurance_available,tr.commission_type,tr.commission,tr.status,tr.booked,tr.approved,tc.*,(select title from {{load_type}} lt where trp.id_load_type=lt.id_load_type ) as load_type,(select title from {{goods_type}} gt where trp.id_goods_type=gt.id_goods_type ) as goods_type from {{truck_route_price}} trp,{{truck}} tr,{{customer}} tc where trp.id_truck=tr.id_truck and tr.id_customer=tc.id_customer and trp.id_truck_route_price='".(int)$_GET['id']."'")->queryRow();
        
                
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_POST);exit;
            $error=array();

            if($_GET['id']!=""){
               if($_POST['order_by']!=""){
                   $exp=explode(",",$_POST['order_by']);
                   $row=Yii::app()->db->createCommand('select * from {{customer}} where mobile ="'.$exp[1].'" and email="'.$exp[2].'"')->queryRow();
                   if($row['id_customer']){
                       $order=new Order;
                       $order->source_address=$TruckroutepriceRow['source_address'];
                       $order->destination_address=$TruckroutepriceRow['destination_address'];
                       $order->source_city=$TruckroutepriceRow['source_city'];
                       $order->source_state=$TruckroutepriceRow['source_state'];
                       $order->destination_city=$TruckroutepriceRow['destination_city'];
                       $order->destination_state=$TruckroutepriceRow['destination_state'];
                       $order->source_lat=$TruckroutepriceRow['source_lat'];
                       $order->source_lng=$TruckroutepriceRow['source_lng'];
                       $order->destination_lat=$TruckroutepriceRow['destination_lat'];
                       $order->destination_lng=$TruckroutepriceRow['destination_lng'];
                       $order->booking_type='T';
                      $order->date_ordered=new CDbExpression('NOW()');
                      $order->customer_type='L';
                      $order->id_customer_ordered=$row['id_customer'];
                      $order->id_customer=$TruckroutepriceRow['id_customer'];
                      $order->customer_fullname=$TruckroutepriceRow['fullname'];
                      $order->customer_mobile=$TruckroutepriceRow['mobile'];
                      $order->customer_email=$TruckroutepriceRow['email'];
                      $order->customer_company=$TruckroutepriceRow['company'];
                      $order->customer_address=$TruckroutepriceRow['address'];
                      $order->customer_city=$TruckroutepriceRow['city'];
                      $order->customer_state=$TruckroutepriceRow['state'];
                      $order->orderperson_fullname=$row['fullname'];
                      $order->orderperson_mobile=$row['mobile'];
                      $order->orderperson_email=$row['email'];
                      $order->orderperson_company=$row['company'];
                      $order->orderperson_address=$row['address'];
                      $order->orderperson_city=$row['city'];
                      $order->orderperson_state=$row['state'];
                      $order->id_truck=$TruckroutepriceRow['id_truck'];
                      $order->truck_reg_no=$TruckroutepriceRow['truck_reg_no'];
                      
                      $Trucktype=Trucktype::model()->find('id_truck_type="'.$TruckroutepriceRow['id_truck_type'].'"');
                      $order->truck_type=$Trucktype->title;
                      $order->tracking_available=$TruckroutepriceRow['tracking_available'];
                      $order->date_available=$TruckroutepriceRow['date_available'];
                      $order->amount=$_GET['p'];//$TruckroutepriceRow['price'];
                      
                      $ppamount=$_GET['p'];//$TruckroutepriceRow['price'];
                      if($_POST['Commission']['Person']['0']['commission']!=""){
                        $ppamount+=$_POST['Commission']['Person']['0']['commission'];
                      }
                      if($_POST['Commission']['Person']['1']['commission']!=""){
                        $ppamount-=$_POST['Commission']['Person']['1']['commission'];
                      }
                      $order->payable_amount=$ppamount;
                      $order->pending_amount=$ppamount;
                      
                      $order->id_truck_type=$TruckroutepriceRow['id_truck_type'];
                      $order->insurance_available=$TruckroutepriceRow['insurance_available'];
                      $order->id_load_type=$TruckroutepriceRow['id_load_type'];
                      $order->id_goods_type=$TruckroutepriceRow['id_goods_type'];
                      
                      $order->commission='';
                      $order->insurance='';
                      
                      $Loadtype=Loadtype::model()->find('id_load_type="'.$TruckroutepriceRow['id_load_type'].'"');
                      $Goodstype=Goodstype::model()->find('id_goods_type="'.$TruckroutepriceRow['id_goods_type'].'"');
                      $order->goods_type=$Loadtype->title;
                      $order->load_type=$Goodstype->title;
                      
                      $order->id_order_status=1;
                      $order->order_status_name='Pending';
                      $order->comment='';
                      //echo '<pre>';print_r($TruckroutepriceRow);print_r($order);
                      //exit;
                      $order->save(false);
                      $id_order=$order->id_order;
                      if($_POST['Commission']['Person']['0']['commission']!=""){
                           $Orderamount=new Orderamount;
                           $Orderamount->id_order=$id_order;
                           $Orderamount->amount_prefix='+';
                           $Orderamount->comment=$_POST['Commission']['Person']['0']['title'];
                           $Orderamount->amount=$_POST['Commission']['Person']['0']['commission'];
                           $Orderamount->save(false);
                      }

                     if($_POST['Commission']['Person']['1']['commission']!=""){ 
                           $Orderamount=new Orderamount;
                           $Orderamount->id_order=$id_order;
                           $Orderamount->amount_prefix='-';
                           $Orderamount->comment=$_POST['Commission']['Person']['1']['title'];
                           $Orderamount->amount=$_POST['Commission']['Person']['1']['commission'];
                           $Orderamount->save(false);
                     }
                      
                      Truck::model()->updateAll(array('booked'=>1),'id_truck="'.$TruckroutepriceRow['id_truck'].'"');
                      
                      $Orderhistory=new Orderhistory;
                      $Orderhistory->id_order=$id_order;
                      $Orderhistory->id_order_status='1';
                      $Orderhistory->title='Pending';
                      $Orderhistory->notified_by_customer=1;
                      $Orderhistory->save(false);
                   }else{
                       $error['invalid']="Please enter valid person details";
                   }
               }else{
                   $error['invalid']="Please enter valid person details";
               }
            }else{
                $error['invalid']="Invalid order.please search for truck and try again";
            }
                if(Yii::app()->request->isAjaxRequest)
                {
                    if(!sizeof($error)){
                        Yii::app()->user->setFlash('success', 'New Order Placed Successfully!!');
                    }
                    //$error['error']='';//'invalid values';
                    echo CJSON::encode($error);
                    Yii::app()->end();
                }
        }

        $loadOwners=Yii::app()->db->createCommand('select concat(fullname,",",mobile,",",email,",",company,",",address) as loadowner from {{customer}} where type="L" and  approved=1 and status=1')->QueryAll();
        //echo '<pre>';print_r($loadOwners);echo '</pre>';
        //$TruckroutepriceRow['id_truck']=13;
        $docs=Yii::app()->db->createCommand("select * from {{truck_doc}} where id_truck='".$TruckroutepriceRow['id_truck']."'")->QueryAll();
        //echo '<pre>';print_r($docs);exit;
        $this->renderPartial('_addorder',array('docs'=>$docs,'loadOwners'=>$loadOwners,'model'=>$TruckroutepriceRow,'return'=>(int)$_GET['return'],'error'=>$error));
    }

    public function actionCreate() {
        $model = new Truckrouteprice('searchTruckBooking');
        $model->unsetAttributes();
        //echo '<pre>';print_r($model);exit;
        if (isset($_GET['Truckrouteprice']))
            $model->attributes = $_GET['Truckrouteprice'];
            $this->render('create', array(
            'model' => $model,
        ));
        //$this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_REQUEST);exit;

            $model['c']->attributes = $_POST['Customer'];
            //$model['c']->id_default_source_city='';

            $data = $_FILES['image'];
            $data['input']['prefix'] = 'profile_' . $id . '_';
            $data['input']['path'] = Library::getMiscUploadPath();

            $data['input']['prev_file'] = $_POST['prev_file'];
            $upload = Library::fileUpload($data);
            $model['c']->profile_image = $upload['file'];
            //echo '<pre>';print_r($_FILES);print_r($upload);
            //exit;
            if ($model['c']->validate()) {
                if (!empty($_POST['Customer']['password'])) {
                    $model['c']->password = Admin::hashPassword($_POST['Customer']['password']);
                } else {
                    unset($model['c']->password);
                }
                if ($model['c']->save(false)) {
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                }
            }
        }
        $this->render('update', array('model' => $model,));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_customer', $arrayRowId);

            //delete profile images
            $custObjs = Customer::model()->findAll($criteria);
            foreach ($custObjs as $custObj) {
                unlink(Library::getMiscUploadPath() . $custObj->profile_image);
            }
            CActiveRecord::model('Customer')->deleteAll($criteria);
            CActiveRecord::model('Loadtruckrequest')->deleteAll($criteria);

            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        $model = new Order('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model['0'] = Order::model()->find(array("condition" => "id_order=" . $id));
        //$model['oh'] = Orderhistory::model()->find(array("condition" => "id_order=" . $id));
        $model['oh'] = new Orderhistory();
        return $model;
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'no_of_trucks':
                $return = '<a href="' . $this->createUrl('truck/index', array('cid' => $data->id_customer)) . '" target="_blank" >' . $data->no_of_trucks . '</a>';
                break;
        }
        return $return;
    }
    
    public function getPrice($data){
        $in="";
                //echo '<pre>';print_r($data);echo '</pre>';
                    if($data->customer_commission_type!=""){
                        $in="c";
                        $comm=$data->customer_commission;
                        $comm_type=$data->customer_commission_type;
                    }else if($data->truck_commission_type!=""){
                        $in="t";
                        $comm=$data->truck_commission;
                        $comm_type=$data->truck_commission_type;
                    }else if($data->truck_route_commission_type!=""){
                        $in="tr";
                        $comm=$data->truck_route_commission;
                        $comm_type=$data->truck_route_commission_type;
                    }else{
                        $in="d";
                        $comm=Yii::app()->config->getData('CONFIG_WEBSITE_GLOBAL_COMMISSION');
                        $comm_type=Yii::app()->config->getData('CONFIG_WEBSITE_GLOBAL_COMMISSION_TYPE');
                    }
                    if($comm_type=='P'){
                        $return=$data->price+($data->price*$comm/100);
                    }else{
                        $return=$data->price+$comm;
                    }
                    return $return;
    }
    
    protected function gridSearch($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'price':
                
                    $return =$this->getPrice($data);
                    //$return.=$in;
                break;
             
            case 'status':
                //$return="helo";
                $return = '<input type="radio" name="select" id="select" onclick="fnorder('.$data->id_truck_route_price.','.$this->getPrice($data).');" value="'.$data->id_truck_route_price.'">';
                break;
        }
        return $return;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'manufacturer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
