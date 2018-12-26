<?php

class OrderController extends Controller {
	public $adminUsers=array();

    public function accessRules() {
        return $this->addActions(array('downloadPendingOrderAdvance','uploadpod','downloadpod','updateCustomerDetails','addComments','updateFields','payAmountTransaction','deleterow','updateStatus', 'updateAmount', 'modifyTruck', 'payAmount','addOrder','Addlocation','deletelocation'));
    }
	
	public function actiondownloadPendingOrderAdvance(){
        //echo "here";
        $expObj=new Export();
        $return=$expObj->downloadPendingOrderAdvance();
        exit();
    }

	public function actiondownloadpod(){
        $file=trim($_GET['file']);
        $path=trim(Library::getOrderUploadPath().$file);
        $link=trim(Library::getOrderUploadLink().$file);
        if(file_exists($path)){
            ob_clean(); 
            Yii::app()->getRequest()->sendFile( $file , file_get_contents($link));
            
        }
        Yii::app()->end();
    }

	 public function actionuploadpod(){
        //echo '<pre>';print_r($_POST);print_r($_FILES);exit;
        $json['status']=0;
        $json['type']=$_POST['type'];
        if ( Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            $id=(int)$_POST['id_order'];
            $prev=$_POST['prev_pod_img'];
            $data = $_FILES['file'];
            $data['input']['prefix'] = $id . '_';
            $data['input']['path'] = Library::getOrderUploadPath();
            $data['input']['prev_file'] = $prev;
            $upload = Library::fileUpload($data);
            if($upload['status']){
                $json['status']=1;
                Order::model()->updateAll(array($json['type']=>$upload['file']),'id_order="'.$id.'"');
                $json['file']=$upload['file'];        
            }else{
                $json['status']=0;
            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

	public function actionupdateCustomerDetails() {
        $field=$_POST['field'];
        $id=(int)$_POST['id'];
        $val=$_POST['val'];
        $json['status']=0;
        //echo '<pre>';print_r($_POST);exit;
        if ($val!="" && $field!="" && $id!=0 &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            $exp=explode(",",$val);
            $custObj=Customer::model()->find('mobile="'.$exp['2'].'" or idprefix="'.$exp['3'].'"');
            if($field=='Order_customer_fullname'){
                $prefix="customer";
                Order::model()->updateAll(array("id_customer"=>$custObj->id_customer,$prefix."_fullname"=>$custObj->fullname,$prefix."_email"=>$custObj->email,$prefix."_mobile"=>$custObj->mobile,$prefix."_company"=>$custObj->company,$prefix."_address"=>$custObj->address,$prefix."_city"=>$custObj->city,$prefix."_state"=>$custObj->state),'id_order="'.$id.'"');
                $json['status']=1;
            }else if($field=='Order_orderperson_fullname'){
                $prefix="orderperson";
                Order::model()->updateAll(array("id_customer_ordered"=>$custObj->id_customer,$prefix."_fullname"=>$custObj->fullname,$prefix."_email"=>$custObj->email,$prefix."_mobile"=>$custObj->mobile,$prefix."_company"=>$custObj->company,$prefix."_address"=>$custObj->address,$prefix."_city"=>$custObj->city,$prefix."_state"=>$custObj->state),'id_order="'.$id.'"');
                $json['status']=1;
            }
            echo CJSON::encode($json);
            Yii::app()->end();
        }
    }

	    public function actionaddComments($id) {
        
        $id=(int)$_GET['id'];
        $comment=$_POST['Ordercomment']['comment'];
        //echo $id." id comment ".$comment;exit;
        $json=array();
        $json['status']=0;
        if ($comment!="" &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest)        {
            $json['status']=1;
            $Ordercomment=new Ordercomment;
            $Ordercomment->id_order=$id;
            $Ordercomment->id_admin=Yii::app()->user->id;
            $Ordercomment->comment=$comment;
            $Ordercomment->save(false);
            Order::model()->updateAll(array('comment'=>$comment),'id_order='.$id);
            Yii::app()->user->setFlash('success', 'Updated successfully!!');
        }else{
            $json['error']='All fields are mandatory';
        }
        
        echo CJSON::encode($json);
	Yii::app()->end();
    }
    
    public function actionupdateFields() {
        $field=$_POST['field'];
        $id=(int)$_POST['id'];
        $val=$_POST['val'];
        $json['status']=0;
        //echo '<pre>';print_r($_POST);exit;
        if ($val!="" && $field!="" && $id!=0 &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Order::model()->updateAll(array($field=>$val),'id_order="'.$id.'"');
            $json['status']=1;
        }
        echo CJSON::encode($json);
	Yii::app()->end();
    }
    
    public function actiondeleterow() {
        $type=$_POST['type'];
        $id=(int)$_POST['id'];
		$order_id=(int)$_POST['oid'];
        $json=array();
        $json['status']=0;
        //echo '<pre>';print_r($_POST);exit;
        if ($type!="" && $id!=0 &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            if($type=='billing'){
                Orderbillinghistory::model()->deleteAll('id_order_billing_history="'.$id.'"');
				$load=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_billing_history where id_order=".$order_id." and customer_type='L'")->queryRow();
                $truck=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_billing_history where id_order=".$order_id." and customer_type='T'")->queryRow();
                Order::model()->updateAll(array('truck_owner_bill'=>$truck['balance'],'load_owner_bill'=>$load['balance']),'id_order="'.$order_id.'"');
			}else if($type=='transaction'){
                Ordertransactionhistory::model()->deleteAll('id_order_transaction_history="'.$id.'"');
				$load=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_transaction_history where id_order=".$order_id." and customer_type='L'")->queryRow();
                $truck=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_transaction_history where id_order=".$order_id." and customer_type='T'")->queryRow();
                Order::model()->updateAll(array('truck_owner_received'=>$truck['balance'],'load_owner_paid'=>$load['balance']),'id_order="'.$order_id.'"');
			
			}else if($type=='comment'){
                Ordercomment::model()->deleteAll('id_order_comment="'.$id.'"');
            }
            $json['status']=1;
            Yii::app()->user->setFlash('success', 'Deleted record successfully!!');
        }
        echo CJSON::encode($json);
	Yii::app()->end();
    }

	    public function addOrderbillinghistory($input){
        $Orderbillinghistory=new Orderbillinghistory;
        $Orderbillinghistory->id_order=$input['id_order'];
        $Orderbillinghistory->id_customer=$input['id_customer'];
        $Orderbillinghistory->customer_type=$input['type'];
        $Orderbillinghistory->amount_prefix=$input['prefix'];
        $Orderbillinghistory->amount=$input['amount'];
        $Orderbillinghistory->comment=$input['comment'];
        $Orderbillinghistory->save(false);
    }
    
    public function addOrdertransactionhistory($input){
        $Ordertransactionhistory=new Ordertransactionhistory;
        $Ordertransactionhistory->id_order=$input['id_order'];
        $Ordertransactionhistory->id_customer=$input['id_customer'];
        $Ordertransactionhistory->customer_type=$input['type'];
        $Ordertransactionhistory->amount_prefix=$input['prefix'];
        $Ordertransactionhistory->amount=$input['amount'];
        $Ordertransactionhistory->comment=$input['comment'];
        $Ordertransactionhistory->save(false);
    }
    
    public function actionpayAmountTransaction($id) {
        
        $id=(int)$_GET['id'];
        $cid=(int)$_GET['cid'];
        $type=$_GET['type'];
        
        //$comment=$_POST['Ordertransactionhistory']['status_'.$type];
        //$prefix=$_POST['Ordertransactionhistory']['prefix_'.$type];
        $exp=explode("|",$_POST['Ordertransactionhistory']['status_'.$type]);
        $comment=$exp[1];
        $prefix=$exp[0];
		$amount=$_POST['Ordertransactionhistory']['amount_'.$type];
        $transaction_by=$_POST['Ordertransactionhistory']['transaction_by_'.$type];
        $payment_type=$_POST['Ordertransactionhistory']['payment_type_'.$type];
		$transaction_datetime=$_POST['Ordertransactionhistory']['transaction_datetime_'.$type];
		$transaction_desc=$_POST['Ordertransactionhistory']['transaction_desc_'.$type];
		$bank=$_POST['Ordertransactionhistory']['bank_'.$type];
        $json=array();
        $json['status']=0;
        if ($transaction_datetime!="" && $payment_type!="" && $comment!="" && $prefix!="" && $amount!=""  && $amount!="0" &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest)        {
            $json['status']=1;
            $Orderbillinghistory=new Ordertransactionhistory;
            $Orderbillinghistory->id_order=$id;
            $Orderbillinghistory->id_customer=$cid;
            $Orderbillinghistory->customer_type=$type;
            $Orderbillinghistory->amount_prefix=$prefix;
            $Orderbillinghistory->amount=$amount;
            $Orderbillinghistory->comment=$comment;
			$Orderbillinghistory->transaction_by=$transaction_by;
            $Orderbillinghistory->payment_type=$payment_type;
			$Orderbillinghistory->bank=$bank;
            $Orderbillinghistory->transaction_datetime=$transaction_datetime;
			$Orderbillinghistory->transaction_desc=$transaction_desc;

			$Orderbillinghistory->save(false);
			
			if($type=='L'){
                $row=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_transaction_history where id_order='".$id."' and customer_type='L'")->queryRow();
                Order::model()->updateAll(array('load_owner_paid'=>$row['balance']),'id_order="'.$id.'"');
            }else if($type=='T'){
                $row=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_transaction_history where id_order='".$id."' and customer_type='T'")->queryRow();
                Order::model()->updateAll(array('truck_owner_received'=>$row['balance']),'id_order="'.$id.'"');
            }

            Yii::app()->user->setFlash('success', 'Updated successfully!!');
        }else{
            $json['error']='All fields are mandatory';
        }
        
        echo CJSON::encode($json);
	Yii::app()->end();
    }
    
    public function actionpayAmount($id) {
        
        $id=(int)$_GET['id'];
        $cid=(int)$_GET['cid'];
        $type=$_GET['type'];
        
        //$comment=$_POST['Orderbillinghistory']['status_'.$type];
        //$prefix=$_POST['Orderbillinghistory']['prefix_'.$type];
        $exp=explode("|",$_POST['Orderbillinghistory']['status_'.$type]);
        $comment=$exp[1];
        $prefix=$exp[0];
		$amount=$_POST['Orderbillinghistory']['amount_'.$type];
                
        $json=array();
        $json['status']=0;
        if ($comment!="" && $prefix!="" && $amount!=""  && $amount!="0" &&  Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest)        {
            $json['status']=1;
            $Orderbillinghistory=new Orderbillinghistory;
            $Orderbillinghistory->id_order=$id;
            $Orderbillinghistory->id_customer=$cid;
            $Orderbillinghistory->customer_type=$type;
            $Orderbillinghistory->amount_prefix=$prefix;
            $Orderbillinghistory->amount=$amount;
            $Orderbillinghistory->comment=$comment;
            $Orderbillinghistory->save(false);
			
			 if($type=='L'){
                $row=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_billing_history where id_order='".$id."' and customer_type='L'")->queryRow();
                Order::model()->updateAll(array('load_owner_bill'=>$row['balance']),'id_order="'.$id.'"');
            }else if($type=='T'){
                $row=Yii::app()->db->createCommand("select (sum(if(amount_prefix='+',amount,0))-sum(if(amount_prefix='-',amount,0))) as balance  from eg_order_billing_history where id_order='".$id."' and customer_type='T'")->queryRow();
                Order::model()->updateAll(array('truck_owner_bill'=>$row['balance']),'id_order="'.$id.'"');
            }

            Yii::app()->user->setFlash('success', 'Updated successfully!!');
        }else{
            $json['error']='All fields are mandatory';
        }
        
        echo CJSON::encode($json);
	Yii::app()->end();
        
        //echo '<pre>';print_r($_POST);print_r($_GET);exit;
        /*
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
        }*/
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
        //echo '<pre>';print_r($_POST['Orderhistory']);exit;
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            
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
            if($data['title']=='Rejected'){
            $data['message'] = $_POST['Orderhistory']['message_dropdown'];
            }else{
                $data['message'] = $_POST['Orderhistory']['message'];
            }
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

	    public function addCustomer($input){
            $CustObj=new Customer;           
            $CustObj->islead=1;
            $CustObj->type=$input['type']==''?'T':$input['type'];//'T';
            $CustObj->fullname=$input['fullname'];
            $CustObj->mobile=$input['mobile'];
            $CustObj->email=$input['email'];
            $CustObj->company=$input['company'];
            $CustObj->date_created=new CDbExpression('NOW()');
            $CustObj->save(false);

            $CustleadObj=new Customerlead;
            $CustleadObj->id_customer=$CustObj->id_customer;
            $CustleadObj->lead_status='Initiated';
            $CustleadObj->save(false);
            return array('id_customer'=>$CustObj->id_customer,'fullname'=>$input['fullname'],'mobile'=>$input['mobile'],'company'=>$input['company'],'email'=>$input['email'],'type'=>$input['type']);
    }
    
    public function getCustomer($input){
        $expLO=explode(",",$input['search']);
        //$loadOwner=Yii::app()->db->createCommand('select * from eg_customer where mobile like "'.$expLO[2].'" and idprefix like "'.$expLO[3].'"')->queryRow();
		$loadOwner=Yii::app()->db->createCommand('select * from eg_customer where mobile like "'.$expLO[2].'"')->queryRow();
				//Customer::model()->find('mobile="'.$expLO[2].'" or idprefix="'.$expLO[3].'"');
        if(is_array($loadOwner)){
            $return=$loadOwner;
        }else{
            $return=array();
        }
        return $return;
    }

    public function actionCreate() {
        $model['o']=new Order();
        $model['c'] = new Customer;
        //if (isset($_GET['Order'])){
        if (Yii::app()->request->isPostRequest) {
            $postOrder=$_POST['Order'];
            //echo '<pre>';print_r($_POST);
            //if($_POST['loadowner']['type']!=1){
			if(!$_POST['loadowner']['type']){
				$inputLoad=array('fullname'=>$postOrder['orderperson_fullname'],'mobile'=>$postOrder['orderperson_mobile'],'company'=>$postOrder['orderperson_company'],'email'=>$postOrder['orderperson_email'],'type'=>$postOrder['orderperson_type']);

                $loadOwner=$this->addCustomer($inputLoad);
            }else{
                $loadOwner=$this->getCustomer(array('search'=>$_POST['Order']['orderperson_fullname_search']));
            }
            
            //if($_POST['truckowner']['type']!=1){
			if(!$_POST['truckowner']['type']){
				$inputTruck=array('fullname'=>$postOrder['customer_fullname'],'mobile'=>$postOrder['customer_mobile'],'company'=>$postOrder['customer_company'],'email'=>$postOrder['customer_email'],'type'=>'T');

                $truckOwner=$this->addCustomer($inputTruck);
            }else{
                $truckOwner=$this->getCustomer(array('search'=>$_POST['Order']['customer_fullname_search']));
            }
            //Yii::app()->db->enableProfiling=1;
            $tObj=Truck::model()->find('lower(truck_reg_no) like "%'.strtolower(str_replace(' ','', $postOrder['truck_reg_no'])).'%"');
            
            //echo '<pre>';print_r($tObj);exit;
            if(sizeof(loadOwner) && sizeof(truckOwner)){//exit('inside');
                $src=Library::getGPDetails(trim($postOrder['source_address']));
                $dest=Library::getGPDetails(trim($postOrder['destination_address']));
                
                $order=new Order;
                $order->attributes=$postOrder;
                $order->id_admin_created=Yii::app()->user->id;
                //$order->id_admin_assigned=$getLeastAssigmentForOrders['id_admin'];
                $order->source_address=$src['input'];
                $order->destination_address=$dest['input'];
                $order->source_city=$src['city'];
                $order->source_state=$src['state'];
                $order->destination_city=$dest['city'];
                $order->destination_state=$dest['state'];
                $order->source_lat=$src['lat'];
                $order->source_lng=$src['lng'];
                $order->destination_lat=$dest['lat'];
                $order->destination_lng=$dest['lng'];
                $order->booking_type='T';
                $order->apply_tds=$postOrder['apply_tds'];
                $order->date_ordered=$postOrder['date_ordered'];
                $order->customer_type=$truckOwner['type'];
                $order->id_customer_ordered=$loadOwner['id_customer'];
                $order->id_customer=$truckOwner['id_customer'];
                $order->customer_fullname=$truckOwner['fullname'];
                $order->customer_mobile=$truckOwner['mobile'];
                $order->customer_email=$truckOwner['email'];
                $order->customer_company=$truckOwner['company'];
                $order->customer_address=$truckOwner['address'];
                $order->customer_city=$truckOwner['city'];
                $order->customer_state=$truckOwner['state'];
                $order->truck_mileage=$tObj->mileage;
                $order->orderperson_type=$loadOwner['type'];
                $order->orderperson_fullname=$loadOwner['fullname'];
                $order->orderperson_mobile=$loadOwner['mobile'];
                $order->orderperson_email=$loadOwner['email'];
                $order->orderperson_company=$loadOwner['company'];
                $order->orderperson_address=$loadOwner['address'];
                $order->orderperson_city=$loadOwner['city'];
                $order->orderperson_state=$loadOwner['state'];
                $order->id_truck=$tObj->id_truck;
                $order->truck_reg_no=trim($postOrder['truck_reg_no']);
                $order->driver_name=trim($postOrder['driver_name']);
                $order->driver_mobile=trim($postOrder['driver_mobile']);
                
                $Trucktype=Trucktype::model()->find('id_truck_type="'.trim($postOrder['id_truck_type']).'"');
                $order->truck_type=$Trucktype->title;
                $order->tracking_available=$tObj->tracking_available;
                $order->date_available=trim($postOrder['date_ordered']);
                $order->amount=trim($postOrder['amount']);//$TruckroutepriceRow['price'];

                $order->pickup_point=trim($postOrder['pickup_point']);
                $order->pickup_date_time=trim($postOrder['pickup_date_time']);
                $order->payable_amount=trim($postOrder['amount']);
                $order->pending_amount=trim($postOrder['amount']);

                $order->id_truck_type=trim($postOrder['id_truck_type']);
                $order->id_load_type=1;
                $order->id_goods_type=0;


                $Loadtype=Loadtype::model()->find('id_load_type="1"');
                $order->goods_type='All';
                $order->load_type='Full Load';

                $order->id_order_status=1;
                $order->order_status_name='Pending';
                $order->comment=trim($postOrder['comment']);
                //echo '<pre>';print_r($TruckroutepriceRow);print_r($order);
                //exit;
                $order->loading_agent_no=trim($postOrder['loading_agent_no']);
                if(trim($postOrder['truck_reg_no'])!=""){ //truck owner plan updation
                    //echo "value of ".$tObj->id_customer_truck_attachment_policy;    
                    $rowTruck=Customertruckattachmentpolicy::model()->find('id_customer_truck_attachment_policy="'.$tObj->id_customer_truck_attachment_policy.'"');
                    $tapModel=Truckattachmentpolicy::model()->find('id_truck_attachment_policy="'.$rowTruck->id_truck_attachment_policy.'"');
                    if($rowTruck->id_truck){
						//exit("in if");
                        $order->truck_attachment_policy_title=$tapModel->title;
                        $order->id_truck_attachment_policy=$rowTruck->id_truck_attachment_policy;
                        $order->id_customer_truck_attachment_policy=$rowTruck->id_customer_truck_attachment_policy;
                        $order->truck_attachment_policy_min_kms=$rowTruck->min_kms;
                        $order->truck_attachment_policy_price_per_km=$rowTruck->price_per_km;
                        $order->truck_attachment_policy_flat_rate=$rowTruck->flat_rate;
                        $order->truck_attachment_policy_diesel_price_per_km=$rowTruck->diesel_price_per_km;
                    }else{
						//exit("in else");
                        $order->truck_attachment_policy_title=$tapModel->title;
                        $order->id_truck_attachment_policy=$rowTruck->id_truck_attachment_policy;
                    }
                }
				//echo '<pre>';print_r($order);
                //exit("at last");
                //$validate=$order->validate();
                //echo $validate.'hee<pre>';print_r($order->getErrors());exit;
                if ($order->validate()) {
                    //exit("inside validate");
                    $order->date_created = new CDbExpression('NOW()');
                    if($postOrder['apply_tds']){
                        $truck_booked_amount=($postOrder['truck_booked_amount']-($postOrder['truck_loading_amount']-$postOrder['truck_unloading_amount']));
                        $deduct_truck_tds=($truck_booked_amount*2/100);
                    }
                    
                    $order->truck_owner_bill=$postOrder['truck_booked_amount']-$postOrder['truck_loading_amount']-$postOrder['truck_unloading_amount']-$postOrder['truck_owner_commission']-$deduct_truck_tds;
                    $order->load_owner_bill= $postOrder['amount']-$postOrder['load_owner_loading_charges'] - $postOrder['load_owner_unloading_charges'] - $postOrder['load_owner_commission'];

                    $order->truck_owner_received=$postOrder['truck_advance_payment'];
                    $order->load_owner_paid=$postOrder['load_advance_payment'];
					
					$order->save(false);
                    $id_order=$order->id_order;
                    if($order->comment!=""){
                        $Ordercomment=new Ordercomment;
                        $Ordercomment->id_order=$id_order;
                        $Ordercomment->date_created=new CDbExpression('NOW()');
                        $Ordercomment->id_admin=Yii::app()->user->id;
                        $Ordercomment->comment=$order->comment;
                        $Ordercomment->save(false);        
                    }
                    
                    $Orderhistory=new Orderhistory;
                    $Orderhistory->id_order=$id_order;
                    $Orderhistory->id_order_status='1';
                    $Orderhistory->title='Pending';
                    $Orderhistory->notified_by_customer=1;
                    $Orderhistory->save(false);
                
                    $truck_booked_amount=($postOrder['truck_booked_amount']-($postOrder['truck_loading_amount']-$postOrder['truck_unloading_amount']));
                    

                    if($postOrder['apply_tds']){
                        //$deduct_truck_tds=($truck_booked_amount*2/100);
                        $this->addOrderbillinghistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'-','comment'=>'Deduct TDS','amount'=>$deduct_truck_tds)); //deduct tds amount
                    }
                    
                    $this->addOrderbillinghistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'-','comment'=>'Unloading Charges','amount'=>$postOrder['truck_unloading_amount'])); //unloading amount
                    
                    $this->addOrderbillinghistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'-','comment'=>'Loading Charges','amount'=>$postOrder['truck_loading_amount'])); //loading amount
                    
                    $this->addOrderbillinghistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'+','comment'=>'Booked Amount','amount'=>$truck_booked_amount)); //truck booked amount
                    
					$this->addOrderbillinghistory(array('id_customer'=>$loadOwner['id_customer'],'id_order'=>$id_order,'type'=>'L','prefix'=>'+','comment'=>'Booked Amount','amount'=>$postOrder['amount'])); //load booked amount

                    if((int)$postOrder['truck_advance_payment']>0){
                            $this->addOrdertransactionhistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'+','comment'=>'Advance Paid','amount'=>$postOrder['truck_advance_payment']));
                    }

					if((int)$postOrder['load_advance_payment']>0){
                            $this->addOrdertransactionhistory(array('id_customer'=>$loadOwner['id_customer'],'id_order'=>$id_order,'type'=>'L','prefix'=>'+','comment'=>'Advance Paid','amount'=>$postOrder['load_advance_payment']));
                    }

					                    //start may 24 2016
                    if((int)$postOrder['load_owner_loading_charges']){
	$this->addOrderbillinghistory(array('id_customer'=>$loadOwner['id_customer'],'id_order'=>$id_order,'type'=>'L','prefix'=>'-','comment'=>'Loading Charges','amount'=>$postOrder['load_owner_loading_charges'])); //load_owner_loading_charges
}

if((int)$postOrder['load_owner_unloading_charges']){
	$this->addOrderbillinghistory(array('id_customer'=>$loadOwner['id_customer'],'id_order'=>$id_order,'type'=>'L','prefix'=>'-','comment'=>'Unloading Charges','amount'=>$postOrder['load_owner_unloading_charges'])); //load_owner_unloading_charges
}

if((int)$postOrder['load_owner_commission']){
	$this->addOrderbillinghistory(array('id_customer'=>$loadOwner['id_customer'],'id_order'=>$id_order,'type'=>'L','prefix'=>'-','comment'=>'Commission','amount'=>$postOrder['load_owner_commission'])); //load_owner_commission
}

if((int)$postOrder['truck_owner_commission']){
	$this->addOrderbillinghistory(array('id_customer'=>$truckOwner['id_customer'],'id_order'=>$id_order,'type'=>'T','prefix'=>'-','comment'=>'Commission','amount'=>$postOrder['truck_owner_commission'])); //truck_owner_commission
}
                    //end may 24 2016


                    //exit("inside validate");
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                    $this->redirect('index');

                }
                //exit("outside validate");
            }
            //exit;
            
        }
        //exit("here");    
        $records['customer'] = Customer::model()->findAll('mobile!=""');//findAll('type!="G"');
        $this->render('create', array('model' => $model,'records' => $records));
    }
    
    public function actionAddlocation($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            $model['otrh']->attributes = $_POST['Ordertruckroutehistory'];
            //echo '<pre>';print_r($model['otrh']->attributes);exit;
            $otrh = new Ordertruckroutehistory();
            $otrh->id_order = (int) $_GET['id'];
            $otrh->location_address = $_POST['Ordertruckroutehistory']['location_address'];
            $otrh->date_time = $_POST['Ordertruckroutehistory']['date_time'];
            //echo '<pre>';print_r($otrh->date_time);exit;
            $src = Library::getGPDetails($_POST['Ordertruckroutehistory']['location_address']);
            $otrh->location_address = trim($src['address']);
            $otrh->location_state = trim($src['state']);
            $otrh->location_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
            $otrh->location_lat = trim($src['lat']);
            $otrh->location_lng = trim($src['lng']);
            
            $otrh->save(false);
            $content = "<tbody ><tr>
                            <td>" . $otrh->location_address . "</td>
                            <td>" . $otrh->date_time . "</td>";
            echo $content;
            	
        }
    }
    public function actiondeletelocation() {
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest) {
            Ordertruckroutehistory::model()->deleteAll('id_order_truck_route_history="'.$_POST['id'].'"');
        }
	Yii::app()->end();
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_REQUEST);exit;
			$expt=explode(",",$_POST['Order']['customer_fullname']);
			$expl=explode(",",$_POST['Order']['orderperson_fullname']);
            $model['0']->customer_fullname = trim($expt[0]);
            $model['0']->customer_mobile = $_POST['Order']['customer_mobile'];
            $model['0']->orderperson_fullname = trim($expl[0]);
            $model['0']->orderperson_mobile = $_POST['Order']['orderperson_mobile'];
            $model['0']->driver_name = $_POST['Order']['driver_name'];
            $model['0']->driver_mobile = $_POST['Order']['driver_mobile'];
            $model['0']->truck_reg_no = $_POST['Order']['truck_reg_no'];
            $model['0']->truck_source_start_date_time = $_POST['Order']['truck_source_start_date_time'];
            $model['0']->truck_destination_reach_date_time = $_POST['Order']['truck_destination_reach_date_time'];
            $model['0']->truck_route_run_time = $_POST['Order']['truck_route_run_time'];
            
            $model['0']->expenses_diesel = $_POST['Order']['expenses_diesel'];
            $model['0']->expenses_tollgate = $_POST['Order']['expenses_tollgate'];
            $model['0']->expenses_loading_unloading = $_POST['Order']['expenses_loading_unloading'];
            $model['0']->expenses_police_charges = $_POST['Order']['expenses_police_charges'];

			$model['0']->amount = $_POST['Order']['payable_amount'];
			$model['0']->payable_amount = $_POST['Order']['payable_amount'];
			$model['0']->id_truck_type = $_POST['Order']['id_truck_type'];
			$trucktObj=Trucktype::model()->find('id_truck_type="'.$_POST['Order']['id_truck_type'].'"');
			$model['0']->truck_type = $trucktObj->title;
            
			$src=Library::getGPDetails(trim($_POST['Order']['source_address']));
            $dest=Library::getGPDetails(trim($_POST['Order']['destination_address']));
            $model['0']->source_address=$src['input'];
            $model['0']->source_city=$src['city'];
            $model['0']->source_state=$src['state'];
            $model['0']->source_lat=$src['lat'];
            $model['0']->source_lng=$src['lng'];
            $model['0']->destination_address=$dest['input'];
            $model['0']->destination_city=$dest['city'];
            $model['0']->destination_state=$dest['state'];
            $model['0']->destination_lat=$dest['lat'];
            $model['0']->destination_lng=$dest['lng'];


            //$na = $_POST['Ordr']['orderperson_fullname'];
            //$model['c']->id_default_source_city='';
            //echo '<pre>';print_r($_POST);print_r($model['0']);exit;
            //if($model['0']->save())
			if($model['0']->save(false))
			{
				 Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));//exit("inside");
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
			}
        }
        /*$model['joinTable']['truck_attachment_policy_title']=Yii::app()->db->createCommand('select title from {{truck_attachment_policy}} where id_truck_attachment_policy="'.$model['0']->id_truck_attachment_policy.'"')->queryScalar();
        //id_customer_truck_attachment_policy
        if($model['0']->id_truck_attachment_policy!=1 && $model['0']->id_truck!=0){
            $model['joinTable']['truck_attachment_policy_details']=Yii::app()->db->createCommand('select ctap.* from {{customer_truck_attachment_policy}} ctap where ctap.id_customer_truck_attachment_policy="'.$model['0']->id_customer_truck_attachment_policy.'"')->queryRow();
        }*/
        $model['L']=$this->getCustomerDetails($model['0']->id_customer_ordered);
        $model['T']=$this->getCustomerDetails($model['0']->id_customer);
        $model['customer'] = Customer::model()->findAll('id_customer!=""');
		
		$modelAdminRows=Admin::model()->findAll('status=1');
        foreach($modelAdminRows as $modelAdminRow){
            $this->adminUsers[$modelAdminRow->id_admin]=$modelAdminRow->first_name." ".$modelAdminRow->last_name;
        }
		
		$this->render('update', array('model' => $model,));
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_order', $arrayRowId);

            //delete profile images
            CActiveRecord::model('Order')->deleteAll($criteria);
			CActiveRecord::model('Orderbillinghistory')->deleteAll($criteria);
			CActiveRecord::model('Ordertransactionhistory')->deleteAll($criteria);
            CActiveRecord::model('Ordercomment')->deleteAll($criteria);
			//CActiveRecord::model('Ordercustomersupport')->deleteAll($criteria);
            //CActiveRecord::model('Orderpaymenthistory')->deleteAll($criteria);
            //CActiveRecord::model('Orderamount')->deleteAll($criteria);
            CActiveRecord::model('Orderhistory')->deleteAll($criteria);

            Yii::app()->user->setFlash('success', Yii::t('common', 'message_delete_success'));
        } else {
            Yii::app()->user->setFlash('alert', Yii::t('common', 'message_checkboxValidation_alert'));
            Yii::app()->user->setFlash('error', Yii::t('common', 'message_delete_fail'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
    }

    public function actionIndex() {
        /*$rows=GpsAccount::model()->find('accountID="santosh"');
        echo '<pre>';print_r($rows);echo '</pre>';
        exit;*/
        /*$rows=Yii::app()->db_gts->createCommand('select * from account')->queryAll();
        echo '<pre>';print_r($rows);exit;*/
        $model = new Order('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];
       $orderStatusRows=  Orderstatus::getOrderStatuses();
       $block['total']=$model->getOrderTotal();
		$block['pod_not_received']=Order::getPodNotReceivedOrders();
       $block['pod_sub_pay_delay']=Order::getPodSubmittedPendingPay();	
	   $block['profit']=Order::getorderTotals();
	   //echo '<pre>';print_r($block);exit;
        $this->render('index', array('block'=>$block,'orderStatus'=>$orderStatusRows,
            'model' => $model,'dataSet'=>$model->search()
        ));
    }

    public function loadModel($id) {
        $model['0'] = Order::model()->find(array("select"=>"*,DATE_FORMAT(date_ordered,'%d-%m-%Y %h:%i %p') as date_ordered_format,DATE_FORMAT(date_available,'%d-%m-%Y %h:%i %p') as date_available_format,DATE_FORMAT(pickup_date_time,'%d-%m-%Y %h:%i %p') as pickup_date_time","condition" => "id_order=" . $id));
        //$model['oh'] = Orderhistory::model()->find(array("condition" => "id_order=" . $id));
        $model['oh'] = new Orderhistory();
        $model['otrh'] = Ordertruckroutehistory::model()->findAll('id_order="' . (int) $id . '" order by date_created desc');        
        return $model;
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            /*case 'id_order':
                $return = '<a class="grid_link" href="' . $this->createUrl('order/update', array('id' => $data->id_order,'backurl'=>base64_encode(urldecode(Yii::app()->request->requestUri)))) . '" >#Ord' . $data->id_order . '</a>';
                break;*/
			case 'id_order':
				$arr=explode("-",substr($data->date_ordered,0,10));
				$ord=$arr[2].$arr[1].$arr[0].$data->id_order;
                $return = '<a class="grid_link" href="' . $this->createUrl('order/update', array('id' => $data->id_order,'backurl'=>base64_encode(urldecode(Yii::app()->request->requestUri)))) . '" >#Ord' . $ord . '</a>';
                break;
            case 'no_of_trucks':
                $return = '<a href="' . $this->createUrl('truck/index', array('cid' => $data->id_customer)) . '" target="_blank" >' . $data->no_of_trucks . '</a>';
                break;
            case 'message':
                $getOrderStatusMessages=Library::getOrderStatusMessages();
                return $getOrderStatusMessages[$data->message]!=""?$getOrderStatusMessages[$data->message]:$data->message;
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
    
    public function calcTripExpense($input){
        $amount=0;
        if((int)$input['startKm']!=0 && (int)$input['stopKm']!=0){
            //echo "<pre>";print_r($input);echo "</pre>";
            $amount=(($input['stopKm']-$input['startKm'])/$input['mileage'])*$input['currentDieselPrice'];
            //echo "value of ".$amount;
        }
        return $amount;
    }
    
    public function getCustomerDetails($id){
        return Customer::model()->find('id_customer="'.$id.'"');
    }
}