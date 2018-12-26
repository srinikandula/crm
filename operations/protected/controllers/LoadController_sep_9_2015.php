<?php

class LoadController extends Controller {

    public function accessRules() {
        return $this->addActions(array('Sendordersms','Addorder','Approve', 'Addquote', 'Addcomment', 'searchResults','bookrequest'));
    }

	public function actionSendordersms(){
		$id=(int)$_GET['id'];
		if($id)
		{
			$row=Order::model()->find('id_order='.$id);
			if(date('Y-m-d',strtotime($row->date_ordered))==date('Y-m-d')){	
				/*echo date('Y-m-d',strtotime($row->date_ordered))." ".date('Y-m-d'); '<pre>';print_r(array('to'=>$row->driver_mobile,'load_owner_name'=>$row->orderperson_fullname,'load_owner_number'=>$row->orderperson_mobile,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time,'id_order'=>$id));
				print_r(array('to'=>$row->orderperson_mobile,'truck_reg_no'=>$row->truck_reg_no,'truck_owner_number'=>$row->customer_mobile,'driver_number'=>$row->driver_mobile,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time,'id_order'=>$id));
				print_r(array('to'=>$row->customer_mobile,'load_owner_number'=>$row->orderperson_mobile,'id_order'=>$id,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time));
				exit;*/
				$this->sendSmsToDriver(array('to'=>$row->driver_mobile,'load_owner_name'=>$row->orderperson_fullname,'load_owner_number'=>$row->orderperson_mobile,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time,'id_order'=>$id));

				$this->sendSmsToLoadOwner(array('to'=>$row->orderperson_mobile,'truck_reg_no'=>$row->truck_reg_no,'truck_owner_number'=>$row->customer_mobile,'driver_number'=>$row->driver_mobile,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time,'id_order'=>$id));

				$this->sendSmsToTruckOwner(array('to'=>$row->customer_mobile,'load_owner_number'=>$row->orderperson_mobile,'id_order'=>$id,'pickup_point'=>$row->pickup_point,'pickup_date_time'=>$row->pickup_date_time));
				Yii::app()->user->setFlash('success', 'New Order Placed Successfully!!');
				$this->redirect($this->createUrl('order/index'));        
			}
		}
	}
    
    public function sendSmsToDriver($data){
        //load owner name,no,pickup point
        $message="Name:".$data['load_owner_name'].",Mobile:".$data['load_owner_number'].",Pickup Point:".$data['pickup_point'].",Pickup Time:".$data['pickup_date_time'].",Ord No:#".$data['id_order'];
        Library::sendSingleSms(array('to'=>$data['to'],'message'=>$message));
    }
    
    public function sendSmsToLoadOwner($data){
        //load owner name,no,pickup point
        $message="Truck No:".$data['truck_reg_no'].",Owner No:".$data['truck_owner_number'].",Driver No:".$data['driver_number'].",Pickup Point:".$data['pickup_point'].",Pickup Time:".$data['pickup_date_time'];
        Library::sendSingleSms(array('to'=>$data['to'],'message'=>$message));
    }
    
    public function sendSmsToTruckOwner($data){
        //load owner name,no,pickup point
        $message="Load Owner No:".$data['load_owner_number'].",Ord No:#".$data['id_order'].",Pickup Point:".$data['pickup_point'].",Pickup Time:".$data['pickup_date_time'];
        Library::sendSingleSms(array('to'=>$data['to'],'message'=>$message));
    }
    
    public function actionAddorder(){
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest){
            $json=array();
            $json['status']=0;
            if($_POST['Loadtruckrequest']['source_address']==""){ $json['errors']['message']['source_address']="Invalid Source Address!!";}
            if($_POST['Loadtruckrequest']['destination_address']==""){ $json['errors']['message']['destination_address']="Invalid Destination Address!!";}
            if($_POST['Loadtruckrequest']['id_goods_type']==""){ $json['errors']['message']['id_goods_type']="Invalid Goods Type!!";}
            if($_POST['Loadtruckrequest']['date_required']==""){ $json['errors']['message']['date_required']="Invalid Date Required!!";}
            //if($_POST['Loadtruckrequest']['pickup_point']==""){ $json['errors']['message']['pickup_point']="Invalid Pickup Point!!";}
            if($_POST['Order']['truck_provider']==""){ $json['errors']['message']['truck_provider']="Invalid Truck Provider!!";}
            if($_POST['Order']['truck_reg_no']==""){ $json['errors']['message']['truck_reg_no']="Invalid Truck Reg No!!";}
            if($_POST['Order']['driver_name']==""){ $json['errors']['message']['driver_name']="Invalid Driver Name!!";}
            if($_POST['Order']['driver_mobile']==""){ $json['errors']['message']['driver_mobile']="Invalid Driver Mobile!!";}
            if($_POST['Order']['pickup_point']==""){ $json['errors']['message']['pickup_point']="Invalid Pickup Point!!";}
            if($_POST['Order']['pickup_date_time']==""){ $json['errors']['message']['pickup_date_time']="Invalid Pickup Date/Time!!";}
            if($_POST['Order']['amount']==""){ $json['errors']['message']['amount']="Invalid Amount!!";}
            if(!sizeof($json['errors'])){
                $cObj=Customer::model()->find('lower(idprefix)="'.strtolower(trim($_POST['Order']['truck_provider'])).'"');
                if($cObj->id_customer==""){
                    $json['errors']['message']['truck_provider']="Invalid Truck Provider!!";
                }
            }
            if(!sizeof($json['errors'])){
				//exit("inside");
                $src=Library::getGPDetails(trim($_POST['Loadtruckrequest']['source_address']));
                $dest=Library::getGPDetails(trim($_POST['Loadtruckrequest']['destination_address']));
                
                $ltrObj=Loadtruckrequest::model()->find("id_load_truck_request='".(int)$_GET['id']."'");
                //exit($_GET['id']);
                $bkObj=Customer::model()->find('id_customer="'.  $ltrObj->id_customer.'"');
                
                $tObj=Truck::model()->find('id_customer="'.$cObj->id_customer.'" and lower(truck_reg_no) like "'.trim($_POST['Order']['truck_reg_no']).'"');
                $order=new Order;
                $order->id_load_truck_request=(int)$_GET['id'];
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
                $order->date_ordered=new CDbExpression('NOW()');
                $order->customer_type=$cObj->type;
                $order->id_customer_ordered=$ltrObj->id_customer;
                $order->id_customer=$cObj->id_customer;
                $order->customer_fullname=$cObj->fullname;
                $order->customer_mobile=$cObj->mobile;
                $order->customer_email=$cObj->email;
                $order->customer_company=$cObj->company;
                $order->customer_address=$cObj->address;
                $order->customer_city=$cObj->city;
                $order->customer_state=$cObj->state;
                $order->orderperson_fullname=$bkObj->fullname;
                $order->orderperson_mobile=$bkObj->mobile;
                $order->orderperson_email=$bkObj->email;
                $order->orderperson_company=$bkObj->company;
                $order->orderperson_address=$bkObj->address;
                $order->orderperson_city=$bkObj->city;
                $order->orderperson_state=$bkObj->state;
                $order->id_truck=$tObj->id_truck;
                $order->truck_reg_no=trim($_POST['Order']['truck_reg_no']);
                $order->driver_name=trim($_POST['Order']['driver_name']);
                $order->driver_mobile=trim($_POST['Order']['driver_mobile']);
                
                $Trucktype=Trucktype::model()->find('id_truck_type="'.trim($_POST['Loadtruckrequest']['id_truck_type']).'"');
                $order->truck_type=$Trucktype->title;
                $order->tracking_available=trim($_POST['Loadtruckrequest']['tracking']);
                $order->date_available=trim($_POST['Loadtruckrequest']['date_required']);
                $order->amount=trim($_POST['Order']['amount']);//$TruckroutepriceRow['price'];

                $order->pickup_point=trim($_POST['Order']['pickup_point']);
                $order->pickup_date_time=trim($_POST['Order']['pickup_date_time']);
                $order->payable_amount=trim($_POST['Order']['amount']);
                $order->pending_amount=trim($_POST['Order']['amount']);

                $order->id_truck_type=trim($_POST['Loadtruckrequest']['id_truck_type']);
                $order->insurance_available=trim($_POST['Loadtruckrequest']['insurance']);
                $order->id_load_type=1;
                $order->id_goods_type=trim($_POST['Loadtruckrequest']['id_goods_type']);


                $Loadtype=Loadtype::model()->find('id_load_type="'.trim($_POST['Loadtruckrequest']['id_load_type']).'"');
                $Goodstype=Goodstype::model()->find('id_goods_type="'.trim($_POST['Loadtruckrequest']['id_goods_type']).'"');
                $order->goods_type=$Goodstype->title;
                $order->load_type=$Loadtype->title;

                $order->id_order_status=1;
                $order->order_status_name='Pending';
                $order->comment='';
                //echo '<pre>';print_r($TruckroutepriceRow);print_r($order);
                //exit;
                $order->save(false);
                $id_order=$order->id_order;
                
                Truck::model()->updateAll(array('booked'=>1),'id_truck="'.$tObj->id_truck.'"');

                $Orderhistory=new Orderhistory;
                $Orderhistory->id_order=$id_order;
                $Orderhistory->id_order_status='1';
                $Orderhistory->title='Pending';
                $Orderhistory->notified_by_customer=1;
                $Orderhistory->save(false);
                
                $ltrObj->id_order=$id_order;
                $ltrObj->status='Booked';
                $ltrObj->save(false);
                
                $ltrshObj=new Loadtruckrequeststatushistory;
                $ltrshObj->id_load_truck_request=(int)$_GET['id'];
                $ltrshObj->id_admin=Yii::app()->user->id;
                $ltrshObj->status='Booked';
                $ltrshObj->notify_customer=1;
                $ltrshObj->save(false);
                //Yii::app()->user->setFlash('success', 'New Order Placed Successfully!!');
                $json['status']=1;
                $json['id_order']=$id_order;
                //$this->sendSmsToDriver(array('to'=>trim($_POST['Order']['driver_mobile']),'load_owner_name'=>$bkObj->fullname,'load_owner_number'=>$bkObj->mobile,'pickup_point'=>trim($_POST['Order']['pickup_point']),'pickup_date_time'=>trim($_POST['Order']['pickup_date_time']),'id_order'=>$id_order));
                
                //$this->sendSmsToLoadOwner(array('to'=>$bkObj->mobile,'truck_reg_no'=>trim($_POST['Order']['truck_reg_no']),'truck_owner_number'=>$cObj->mobile,'driver_number'=>trim($_POST['Order']['driver_mobile']),'pickup_point'=>trim($_POST['Order']['pickup_point']),'pickup_date_time'=>trim($_POST['Order']['pickup_date_time']),'id_order'=>$id_order));
                
				//$this->sendSmsToTruckOwner(array('to'=>$cObj->mobile,'load_owner_number'=>$bkObj->mobile,'id_order'=>$id_order,'pickup_point'=>trim($_POST['Order']['pickup_point']),'pickup_date_time'=>trim($_POST['Order']['pickup_date_time'])));
				//$this->redirect('order/index');        
				//header("location:".$this->createAbsoluteUrl('order/index'));
				//exit;
                
            }
            
            //echo '<pre>';print_r($_POST);exit;
            echo CJSON::encode($json);
			
            Yii::app()->end();
        }
    }
    
    public function actionBookrequest(){
        if (Yii::app()->request->getIsAjaxRequest() && Yii::app()->request->isPostRequest){
            Loadtruckrequestquotes::model()->updateAll(array('booking_request'=>1),'id_load_truck_request_quotes='.(int)$_POST['id']);
            echo CJSON::encode(array('status'=>1));
            Yii::app()->end();
        }
    }

    public function actionsearchResults() {
        //echo md5(implode("-",$_POST['search'])).'<pre>';print_r($_POST['search']);exit;
        $searchResults = array();
        $id = (int) $_GET['id'];
        $cache = Yii::app()->cache;
        if (Yii::app()->request->isPostRequest) {
            //exit('inside');
            //echo md5(implode("-",$_POST['search'])).'<pre>';print_r($_POST['search']);exit;
            //$md5=md5(implode("-",$_POST['search'])).$_GET['id'];
            //$md5=md5(implode("-",$_POST['search'])).$_GET['id'];
            $md5 = $id;


            //$searchData=$cache->get($md5);
            //if($searchData===false)
            if (1) {

                $rows = Customer::model()->searchTrucks($_POST['search']);

                foreach ($rows[0] as $rw1) {
                    $searchResults[] = $rw1;
                    //$searchResults[]=array("id_customer"=>$rw1[id_customer],"idprefix"=>$rw1[idprefix],"type"=>$rw1[type],"fullname"=>$rw1[type],);
                }
                foreach ($rows[1] as $rw2) {
                    $searchResults[] = $rw2;
                    //$searchResults[]=array("file"=>"one","title"=>"one","installed"=>"1");
                }
                //echo sizeof($rows[0])." ".sizeof($rows[1]).'hello<pre>';print_r($_POST['search']);print_r($searchResults);
                //exit;

                $cache->set($md5, $searchResults, 100, new CDbCacheDependency('select max(date_modified) as dm from (select date_modified from {{customer}} union all select date_modified from eg_truck union all select date_modified from {{truck_route_price}} union all select date_modified from {{customer_operating_destinations}}) as tab'));

                $searchData = $searchResults;
            }
        } else {
            //exit("here");
            $searchData = $cache->get($id);
        }

        //exit;
        //echo '<pre>';print_r($searchData);
        //exit;

        $arrayDataProvider = new CArrayDataProvider($searchData, array(
            'pagination' => array(
                'pageSize' => 10, //Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
        ));

        $this->renderPartial('_form_search_results_block', array('dataProvider' => $arrayDataProvider));
    }

    public function actionAddcomment($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            //echo '<pre>';print_r($_POST);exit;
            $notify = (int) $_POST['Loadtruckrequeststatushistory']['notify_customer'];
            $status = $_POST['Loadtruckrequeststatushistory']['status'];
            $message = $_POST['Loadtruckrequeststatushistory']['comment'];
            Loadtruckrequest::model()->updateAll(array('status' => $status), 'id_load_truck_request=' . (int) $_GET['id']);
            $ltrshobj = new Loadtruckrequeststatushistory();
            $ltrshobj->id_load_truck_request = (int) $_GET['id'];
            $ltrshobj->id_admin = Yii::app()->user->id;
            $ltrshobj->status = $status;
            $ltrshobj->message = $message;
            $ltrshobj->notify_customer = $notify;
            $ltrshobj->save(false);

            $nf = $notify == 1 ? "Yes" : "No";
            $content = "<tbody ><tr>
                            <td>" . date('Y-m-d h:i:sa') . "</td>
                            <td>" . $status . "</td>
                            <td>" . $message . "</td>
                            <td>" . $nf . "</td></tr></tbody>";
            echo $content;
        }
    }

    public function actionAddquote($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            $idprefix = $_POST['Loadtruckrequestquote']['idprefix'];
            $quote = $_POST['Loadtruckrequestquote']['quote'];
            $message = $_POST['Loadtruckrequestquote']['comment'];

            $cObj = Customer::model()->find('trash=0 and islead=0 and approved=1 and idprefix="' . $idprefix . '"');
            $status = 0;
            if ($cObj->id_customer && is_numeric($quote)) {
                $ltrqobj = new Loadtruckrequestquotes;
                $ltrqobj->id_customer = $cObj->id_customer;
                $ltrqobj->id_load_truck_request = (int) $_GET['id'];
                $ltrqobj->id_admin = Yii::app()->user->id;
                $ltrqobj->quote = $quote;
                $ltrqobj->message = $message;
                $ltrqobj->save(false);

                $status = 1;
                $content = "<tbody ><tr>
                            <td>" . $cObj->idprefix . "</td>
                            <td>" . $cObj->fullname . "," . $cObj->mobile . "</td>
                            <td>" . $quote . "</td>
                            <td>" . $message . "</td></tr></tbody>";
            }
            echo $status . "---" . $content;
        }
    }

    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $row = Loadtruckrequest::model()->find('id_load_truck_request=' . $id);
            if (!$row->approved) {
                $custObj = Customer::model()->find('id_customer=' . $row->id_customer);
                if ($custObj->enable_sms_email_ads) {
                    $data = array('id' => '9', 'replace' => array('%customer_name%' => $obj->fullname), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
                    Mail::send($data);
                }
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

    public function actionCreate() {
        $model['ltr'] = new Loadtruckrequest;
        //echo '<pre>';print_r($_POST);EXIT;
        if (Yii::app()->request->isPostRequest && sizeof($_POST['Truck']) && $_POST['Loadtruckrequest']['title'] != "") {


            $xCust = explode(",", $_POST['Loadtruckrequest']['title']);
            $custObj = Customer::model()->find('idprefix="' . $xCust[0] . '" or mobile="' . $xCust[3] . '"');
            $id_customer = 0;
            if ($custObj->id_customer) {
                $id_customer = $custObj->id_customer;
            }


            foreach ($_POST['Truck'] as $data) {
                $src = Library::getGPDetails($data['source_address']);
                $dest = Library::getGPDetails($data['destination_address']);
                $row = Admin::model()->getLeastAssigmentIdSearch();
                $model['ltr'] = new Loadtruckrequest();
                $model['ltr']->id_customer = $id_customer;
                if ($_SESSION['id_admin_role'] != 8) { //other than transporter
                    $model['ltr']->id_admin_created = Yii::app()->user->id;
                }
                $model['ltr']->id_admin_assigned = $row['id_admin'];
                $model['ltr']->title = $_POST['Loadtruckrequest']['title'];
                $model['ltr']->pickup_point = $data['pickup_point'];
                $model['ltr']->source_address = trim($src['address']);
                $model['ltr']->source_state = trim($src['state']);
                $model['ltr']->source_city = trim($src['city']) == "" ? trim($src['input']) : trim($src['city']);
                $model['ltr']->source_lat = trim($src['lat']);
                $model['ltr']->source_lng = trim($src['lng']);

                $model['ltr']->destination_address = trim($dest['address']);
                $model['ltr']->destination_state = trim($dest['state']);
                $model['ltr']->destination_city = trim($dest['city']) == "" ? trim($dest['input']) : trim($dest['city']);
                $model['ltr']->destination_lat = trim($dest['lat']);
                $model['ltr']->destination_lng = trim($dest['lng']);

                $model['ltr']->status = 'New';
                $model['ltr']->approved = 1;
                $model['ltr']->comment = $data['comment'];
                $model['ltr']->tracking = $data['tracking'];
                $model['ltr']->insurance = $data['insurance'];
                $model['ltr']->id_truck_type = $data['id_truck_type'];
                $model['ltr']->id_goods_type = $data['id_goods_type'];
                $model['ltr']->date_required = $data['date_required'];
                $model['ltr']->date_created = new CDbExpression('NOW()');
                $model['ltr']->save(false);
            }

            //echo '<pre>';print_r($_POST);EXIT;
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
            /* if ($model['ltr']->validate()) {
              if ($model['ltr']->save(false)) {
              if($custObj->enable_sms_email_ads)
              {
              $data = array('id' => '4', 'replace' => array('%customer_name%' => $obj->fullname), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
              Mail::send($data);
              }
              Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
              $this->redirect('index');
              }
              } */
        }
        if ($_SESSION['id_admin_role'] == 8) { //transporter only
            $records['customer'] = Customer::model()->findAll('approved=1 and id_customer="' . Yii::app()->user->id . '"');
        } else {
            $records['customer'] = Customer::model()->getApprovedActiveCustomers();
        }
        //echo '<pre>';print_r($records['customer']);exit($records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email);
        $this->render('create', array('records' => $records, 'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $ltrqcriteria=new CDbCriteria;
        $ltrqcriteria->select="t.*,c.idprefix,concat(c.mobile,',',c.alt_mobile_1,',',c.alt_mobile_2,',',c.alt_mobile_3) as mobile,c.fullname";
        $ltrqcriteria->join="left join {{customer}} c on t.id_customer=c.id_customer";
        $ltrqcriteria->order="t.quote asc";
        $ltrqcriteria->condition='t.id_load_truck_request="' . (int) $id . '"';
        //$model['ltrq'] = Loadtruckrequestquotes::model()->findAll('id_load_truck_request="' . (int) $id . '" order by quote asc');
        $model['ltrq'] = Loadtruckrequestquotes::model()->findAll($ltrqcriteria);
        //$model['ltrq'] = Loadtruckrequestquotes::model()->findAll('id_load_truck_request="' . (int) $id . '" order by quote asc');
        $model['ltrsh'] = Loadtruckrequeststatushistory::model()->findAll('id_load_truck_request="' . (int) $id . '" order by date_created desc');


        $model['search']['source_address'] = $_POST['search']['source_address'] == "" ? $model['ltr']->source_address : $_POST['search']['source_address'];
        $model['search']['destination_address'] = $_POST['search']['destination_address'] == "" ? $model['ltr']->destination_address : $_POST['search']['destination_address'];
        $model['search']['id_goods_type'] = $_POST['search']['id_goods_type'] == "" ? $model['ltr']->id_goods_type : $_POST['search']['id_goods_type'];
        $model['search']['id_truck_type'] = $_POST['search']['id_truck_type'] == "" ? $model['ltr']->id_truck_type : $_POST['search']['id_truck_type'];
        $model['search']['tracking'] = $_POST['search']['tracking'] == "" ? $model['ltr']->tracking : $_POST['search']['tracking'];
        $model['search']['insurance'] = $_POST['search']['insurance'] == "" ? $model['ltr']->insurance : $_POST['search']['insurance'];
        $model['book']['request']=$this->isBookingRequestRaised($model['ltrq']);
        
        $this->render('update', array('model' => $model, 'dataProvider' => $arrayDataProvider));
    }
    
    public function isBookingRequestRaised($input){
        $status=0;
        if($_SESSION['id_admin_role']==10){ //accessible only to out bound calling team
            foreach($input as $row){
                if($row->booking_request){ $status=1;}
            }
        }
        return $status;
    }

    public function actionDelete() {//($id)
        $arrayRowId = is_array(Yii::app()->request->getParam('id')) ? Yii::app()->request->getParam('id') : array(
            Yii::app()->request->getParam('id'));
        if (sizeof($arrayRowId) > 0) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id_load_truck_request', $arrayRowId);

            //delete profile images
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
        $model = new Loadtruckrequest('searchLoad');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Loadtruckrequest']))
            $model->attributes = $_GET['Loadtruckrequest'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model['ltr'] = Loadtruckrequest::model()->find(array("condition" => "id_load_truck_request=" . $id));
        $model['c'] = Customer::model()->find(array("condition" => "id_customer=" . $model['ltr']->id_customer));

        return $model;
    }

    protected function grid($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'type':
                if ($data[type] == 'C') {
                    $return = 'Commission Agent';
                } else if ($data[type] == 'L') {
                    $return = 'Load Owner';
                } else if ($data[type] == 'G') {
                    $return = 'Guest';
                } else if ($data[type] == 'T') {
                    $return = 'Truck Owner';
                } else if ($data[type] == 'TR') {
                    $return = 'Transporter';
                }
                break;
            case 'least_quote':
                $row=Yii::app()->db->createCommand('select min(quote) as least_quote from {{load_truck_request_quotes}} where id_load_truck_request="'.$data['id_load_truck_request'].'"')->queryRow();
                $return = $row['least_quote'];
                break;
            
            case 'fullname':
                $truck = $data[truck_reg_no] != "" ? "[" . $data[truck_reg_no] . "]" : "";
                $return = $data[fullname] . $truck;
                break;

            case 'address':
                $return = $data['address'];
                $return.=$data['city'] != "" ? "," . $data['city'] : "";
                $return.=$data['state'] != "" ? "," . $data['state'] : "";
                break;

            case 'mobile':
                $return = $data['mobile'];
                $return.=$data['alt_mobile_1'] != "" ? "," . $data['alt_mobile_1'] : "";
                $return.=$data['alt_mobile_2'] != "" ? "," . $data['alt_mobile_2'] : "";
                $return.=$data['alt_mobile_3'] != "" ? "," . $data['alt_mobile_3'] : "";
                $return.=$data['landline'] != "" ? "," . $data['landline'] : "";
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
