<?php

class LoadController extends Controller {

    public function accessRules() {
        return $this->addActions(array('Approve','Addquote','Addcomment','searchResults'));
    }
    
    public function actionsearchResults(){
        //echo md5(implode("-",$_POST['search'])).'<pre>';print_r($_POST['search']);exit;
        $searchResults=array();        
        $id=(int)$_GET['id'];
        $cache=Yii::app()->cache;
        if (Yii::app()->request->isPostRequest) {
            //exit('inside');
            //echo md5(implode("-",$_POST['search'])).'<pre>';print_r($_POST['search']);exit;
            //$md5=md5(implode("-",$_POST['search'])).$_GET['id'];
            //$md5=md5(implode("-",$_POST['search'])).$_GET['id'];
            $md5=$id;
            
            
            //$searchData=$cache->get($md5);
            
            //if($searchData===false)
            if(1)
            {
                
                $rows=Customer::model()->searchTrucks($_POST['search']);
                
                foreach($rows[0] as $rw1){
                    $searchResults[]=$rw1;
                    //$searchResults[]=array("id_customer"=>$rw1[id_customer],"idprefix"=>$rw1[idprefix],"type"=>$rw1[type],"fullname"=>$rw1[type],);
                }
                foreach($rows[1] as $rw2){
                    $searchResults[]=$rw2;
                    //$searchResults[]=array("file"=>"one","title"=>"one","installed"=>"1");
                }
                //echo sizeof($rows[0])." ".sizeof($rows[1]).'hello<pre>';print_r($_POST['search']);print_r($searchResults);
                //exit;
                
                $cache->set($md5,$searchResults , 100, new CDbCacheDependency('select max(date_modified) as dm from (select date_modified from {{customer}} union all select date_modified from eg_truck union all select date_modified from {{truck_route_price}} union all select date_modified from {{customer_operating_destinations}}) as tab'));
            
            $searchData=$searchResults;
            }
            
        }else{
            //exit("here");
            $searchData=$cache->get($id);
        }
        
        //exit;
        //echo '<pre>';print_r($searchData);
        //exit;
        
        $arrayDataProvider=new CArrayDataProvider($searchData, array(
		'pagination'=>array(
			'pageSize'=>10,//Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
		),
            ));
        
        $this->renderPartial('_form_search_results_block', array('dataProvider'=>$arrayDataProvider));
    }
    
    public function actionAddcomment($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            //echo '<pre>';print_r($_POST);exit;
            $notify=(int)$_POST['Loadtruckrequeststatushistory']['notify_customer'];
            $status=$_POST['Loadtruckrequeststatushistory']['status'];
            $message=$_POST['Loadtruckrequeststatushistory']['comment'];
                Loadtruckrequest::model()->updateAll(array('status'=>$status),'id_load_truck_request='.(int)$_GET['id']);
                $ltrshobj= new Loadtruckrequeststatushistory();
                $ltrshobj->id_load_truck_request=(int)$_GET['id'];
                $ltrshobj->id_admin=Yii::app()->user->id;
                $ltrshobj->status=$status;
                $ltrshobj->message=$message;
                $ltrshobj->notify_customer=$notify;
                $ltrshobj->save(false);
                
                $nf=$notify==1?"Yes":"No";
                $content="<tbody ><tr>
                            <td>".date('Y-m-d h:i:sa')."</td>
                            <td>".$status."</td>
                            <td>".$message."</td>
                            <td>".$nf."</td></tr></tbody>";
            echo $content;
        }
    }
    
    
    public function actionAddquote($id) {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST)) {
            $idprefix=$_POST['Loadtruckrequestquote']['idprefix'];
            $quote=$_POST['Loadtruckrequestquote']['quote'];
            $message=$_POST['Loadtruckrequestquote']['comment'];
            
            $cObj=Customer::model()->find('trash=0 and islead=0 and approved=1 and idprefix="'.$idprefix.'"');
            $status=0;
            if($cObj->id_customer && is_numeric($quote)){
                $ltrqobj= new Loadtruckrequestquotes;
                $ltrqobj->id_customer=$cObj->id_customer;
                $ltrqobj->id_load_truck_request=(int)$_GET['id'];
                $ltrqobj->id_admin=Yii::app()->user->id;
                $ltrqobj->quote=$quote;
                $ltrqobj->message=$message;
                $ltrqobj->save(false);
                
                $status=1;    
                $content="<tbody ><tr>
                            <td>". $cObj->idprefix."</td>
                            <td>".$cObj->fullname.",".$cObj->mobile."</td>
                            <td>".$quote."</td>
                            <td>".$message."</td></tr></tbody>";
            }
            echo $status."---".$content;
        }
    }

    public function actionApprove() {
        $ids = Yii::app()->request->getParam('id');
        $approved = 0;
        foreach ($ids as $id) {
            $row = Loadtruckrequest::model()->find('id_load_truck_request=' . $id);
            if (!$row->approved) {
                $custObj = Customer::model()->find('id_customer=' . $row->id_customer);
                if($custObj->enable_sms_email_ads)
                {    
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
        if (Yii::app()->request->isPostRequest && sizeof($_POST['Truck']) && $_POST['Loadtruckrequest']['title']!="" ) {
            

               $xCust=explode(",",$_POST['Loadtruckrequest']['title']); 
                $custObj=Customer::model()->find('idprefix="'.$xCust[0].'" or mobile="'.$xCust[3].'"');
                $id_customer=0;
                if($custObj->id_customer){
                    $id_customer=$custObj->id_customer;
                }
                
            
            foreach($_POST['Truck'] as $data){
                $src=Library::getGPDetails($data['source_address']);
                $dest=Library::getGPDetails($data['destination_address']);
                $row=Admin::model()->getLeastAssigmentIdSearch();
                $model['ltr']=new Loadtruckrequest();
                $model['ltr']->id_customer=$id_customer;
                if($_SESSION['id_admin_role']!=8){ //other than transporter
                    $model['ltr']->id_admin_created=Yii::app()->user->id;
                }
                $model['ltr']->id_admin_assigned=$row['id_admin'];
                $model['ltr']->title=$_POST['Loadtruckrequest']['title'];
                $model['ltr']->source_address=trim($src['address']);
                $model['ltr']->source_state=trim($src['state']);
                $model['ltr']->source_city=trim($src['city'])==""?trim($src['input']):trim($src['city']);
                $model['ltr']->source_lat=trim($src['lat']);
                $model['ltr']->source_lng=trim($src['lng']);
                
                $model['ltr']->destination_address=trim($dest['address']);
                $model['ltr']->destination_state=trim($dest['state']);
                $model['ltr']->destination_city=trim($dest['city'])==""?trim($dest['input']):trim($dest['city']);
                $model['ltr']->destination_lat=trim($dest['lat']);
                $model['ltr']->destination_lng=trim($dest['lng']);

                $model['ltr']->status = 'New';
                $model['ltr']->approved = 1;
                $model['ltr']->comment = $data['comment'];
                $model['ltr']->tracking = $data['tracking'];
                $model['ltr']->insurance = $data['insurance'];
                $model['ltr']->id_truck_type = $data['id_truck_type'];
                $model['ltr']->id_goods_type = $data['id_goods_type'];
                $model['ltr']->date_required = $data['date_required'];
                $model['ltr']->date_created=new CDbExpression('NOW()');
                $model['ltr']->save(false);
            }
            
            //echo '<pre>';print_r($_POST);EXIT;
            Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
            $this->redirect('index');
            /*if ($model['ltr']->validate()) {
                if ($model['ltr']->save(false)) {
                    if($custObj->enable_sms_email_ads)
                    {    
                        $data = array('id' => '4', 'replace' => array('%customer_name%' => $obj->fullname), 'mail' => array("to" => array($custObj->email => $custObj->fullname)));
                        Mail::send($data);
                    }
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_create_success'));
                    $this->redirect('index');
                }
            }*/
        }
        if($_SESSION['id_admin_role']==8){ //transporter only
            $records['customer']=  Customer::model()->findAll('approved=1 and id_customer="'.Yii::app()->user->id.'"');
        }else{
            $records['customer']=  Customer::model()->getApprovedActiveCustomers();
        }
        //echo '<pre>';print_r($records['customer']);exit($records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email);
        $this->render('create', array('records'=>$records,'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        
        if (Yii::app()->request->isPostRequest) {
            //echo md5(implode("-",$_POST['search'])).'<pre>';print_r($_POST['search']);exit;
            
            
            /*$expSource=explode(', ',$_POST['Loadtruckrequest']['source_address']);
            $expDestination=explode(', ',$_POST['Loadtruckrequest']['destination_address']);
            $expSourceRev=  array_reverse($expSource);
            $expDestinationRev=  array_reverse($expDestination);
            $gdetailsSource=Library::getGPDetails($_POST['Loadtruckrequest']['source_address']);
            $gdetailsDestination=Library::getGPDetails($_POST['Loadtruckrequest']['destination_address']);

            $model['ltr']->attributes=$_POST['Loadtruckrequest'];
            if($expSourceRev[0]=='India'){
                $model['ltr']->source_state=trim($expSourceRev[1]);
                $model['ltr']->source_city=trim($expSourceRev[2]);
                $model['ltr']->source_lat=trim($gdetailsSource['lat']);
                $model['ltr']->source_lng=trim($gdetailsSource['lng']);
            }

            if($expDestinationRev[0]=='India'){
                $model['ltr']->destination_state=trim($expDestinationRev[1]);
                $model['ltr']->destination_city=trim($expDestinationRev[2]);
                $model['ltr']->destination_lat=trim($gdetailsDestination['lat']);
                $model['ltr']->destination_lng=trim($gdetailsDestination['lng']);
            }
            $model['ltr']->price_from = $_POST['Loadtruckrequest']['price_from'];
            $model['ltr']->price_to = $_POST['Loadtruckrequest']['price_to'];
            $model['ltr']->status = $_POST['Loadtruckrequest']['status'];
            $model['ltr']->tracking = $_POST['Loadtruckrequest']['tracking'];
            $model['ltr']->id_truck_type = $_POST['Loadtruckrequest']['id_truck_type'];
            $model['ltr']->id_goods_type = $_POST['Loadtruckrequest']['id_goods_type'];
            $model['ltr']->date_required = $_POST['Loadtruckrequest']['date_required'];
            
            if ($model['ltr']->validate()) {
                if ($model['ltr']->save(false)) {
                    Yii::app()->user->setFlash('success', Yii::t('common', 'message_modify_success'));
                    $this->redirect(base64_decode(Yii::app()->request->getParam('backurl')));
                }
            }*/
        }
        $model = $this->loadModel($id);
        $model['ltrq']=  Loadtruckrequestquotes::model()->findAll('id_load_truck_request="'.(int)$id.'" order by quote asc');
        $model['ltrsh']= Loadtruckrequeststatushistory::model()->findAll('id_load_truck_request="'.(int)$id.'" order by date_created desc');
        //echo '<pre>';print_r($model);exit;
        
        /*$searchResults=array();
        $searchResults[]=array("file"=>"one","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"2","title"=>"2","installed"=>"2");
        $searchResults[]=array("file"=>"3","title"=>"3","installed"=>"3");
        $searchResults[]=array("file"=>"4","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"5","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"6","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"7","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"8","title"=>"one","installed"=>"1");
        $searchResults[]=array("file"=>"9","title"=>"9","installed"=>"1");
        
        $arrayDataProvider=new CArrayDataProvider($searchResults, array(
		'pagination'=>array(
			'pageSize'=>2,//Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
		),
            ));*/
        
        $model['search']['source_address']=$_POST['search']['source_address']==""?$model['ltr']->source_address:$_POST['search']['source_address'];
        $model['search']['destination_address']=$_POST['search']['destination_address']==""?$model['ltr']->destination_address:$_POST['search']['destination_address'];
        $model['search']['id_goods_type']=$_POST['search']['id_goods_type']==""?$model['ltr']->id_goods_type:$_POST['search']['id_goods_type'];
        $model['search']['id_truck_type']=$_POST['search']['id_truck_type']==""?$model['ltr']->id_truck_type:$_POST['search']['id_truck_type'];
        $model['search']['tracking']=$_POST['search']['tracking']==""?$model['ltr']->tracking:$_POST['search']['tracking'];
        $model['search']['insurance']=$_POST['search']['insurance']==""?$model['ltr']->insurance:$_POST['search']['insurance'];
        $this->render('update', array('model' => $model,'dataProvider'=>$arrayDataProvider));
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
                if($data[type]=='C'){
                    $return ='Commission Agent';
                }else if($data[type]=='L'){
                    $return ='Load Owner' ;
                }else if($data[type]=='G'){
                    $return = 'Guest';
                }else if($data[type]=='T'){
                    $return = 'Truck Owner';
                }
                break;
            
            case 'fullname':
                    $truck=$data[truck_reg_no]!=""?"[".$data[truck_reg_no]."]":"";
                    $return=$data[fullname].$truck;
                break;
            
            case  'address':
                    $return=$data['address']; 
                    $return.=$data['city']!=""?",".$data['city']:"";
                    $return.=$data['state']!=""?",".$data['state']:"";
                break;
            
            case  'mobile':
                $return=$data['mobile'];
                $return.=$data['alt_mobile_1']!=""?",".$data['alt_mobile_1']:"";
                $return.=$data['alt_mobile_2']!=""?",".$data['alt_mobile_2']:"";
                $return.=$data['alt_mobile_3']!=""?",".$data['alt_mobile_3']:"";
                $return.=$data['landline']!=""?",".$data['landline']:"";
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
