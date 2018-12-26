<?php

class CronController extends Controller {

    public $layout = "//layouts/guest";
    public $limit = 10;

    public function actions() {

        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function filters() {
        return array(
            'ajaxOnly + editable'
        );
    }

    public function actionGPSLocationSmsAlerts() { //need to upload image
	     //exit("bye");
		 /*
          http://egcrm.cloudapp.net/operations/index.php/cron/GPSLocationSmsAlerts
         deviceID should be Truck Reg No,contactPhone will receive sms
        */
        /*$addrArr=Library::getGPBYLATLNGDetails('17.3700,78.4800');
        echo substr(str_replace(' ','', $addrArr['address']),6,-6);
        echo '<pre>';print_r($addrArr);exit;
		Library::sendSingleSms(array('to'=>'9848750094','message'=>'langana 500033|device7:oli - Tandur Road,Gowthapur,Tandur,Telangana 501141|device8:t Rd,Mega Hills,Madhapur,Hyderabad,Telangana 500081|device9: Number 72,Kavu'));
		exit;*/


		Yii::app()->db_gts->CreateCommand("delete from EventData where timestamp=0  and accountID!='' and deviceID!=''")->query();
        
		if (1) {
			//$accRows = Yii::app()->db_gts->CreateCommand("select contactPhone,accountID  from Account where isActive=1 and smsEnabled=1 and accountID in ('ayyappacargo')")->queryAll(); //this is for trial

            $accRows = Yii::app()->db_gts->CreateCommand("select contactPhone,accountID  from Account where isActive=1 and smsEnabled=1 and accountID!='santosh'")->queryAll();
            $mailContent="";
			$time=time();
            $lastSixHours=$time-21600;//last six hours

            foreach($accRows as $accRow){
                $devRows=Yii::app()->db_gts->CreateCommand("select deviceID,lastValidLongitude,lastValidLatitude  from Device where accountID='".$accRow['accountID']."' and isActive=1 and lastValidLongitude!=0 and lastValidLatitude!=0 and lastGPSTimestamp>".$lastSixHours)->queryAll();
				/*echo "select deviceID,lastValidLongitude,lastValidLatitude  from Device where accountID='".$accRow['accountID']."' and isActive=1 and lastValidLongitude!=0 and lastValidLatitude!=0 and lastGPSTimestamp>".$lastSixHours;
				exit;*/
                $msg="";
                $prefix="";
                foreach($devRows as $devRow){
                    $addr="";
					
					/*
                    //eg gps server
					$addrArr=Library::getGPBYLATLNGDetailsCloud($devRow['lastValidLatitude'].",".$devRow['lastValidLongitude']);
                    $addr=trim($addrArr['address']);
					
					//start removing district,unname road from addr
					//$addr=str_replace("Unnamed Road,","",$addr);
					$addr=str_replace(" district","",$addr);
					//end removing 
					*/


					//google maps
					$addrArr=Library::getGPBYLATLNGDetailsGoogle($devRow['lastValidLatitude'].",".$devRow['lastValidLongitude']);
					$addr=substr(str_replace(', ',',',$addrArr['address']),0,-13);
					
					if($addr!=""){
						$exp=explode(",",$addr);
                        $shortAddr=sizeof($exp)>3?$exp[0].",".$exp[1].",".$exp[2]:$addr;
                        $msg .= $prefix . $devRow['deviceID'] . ":" . $shortAddr;
						//$msg.=$prefix.$devRow['deviceID'].":".$addr;
					}

                    $prefix="|";
                }
				$mailContent.=$accRow['contactPhone'].":".$msg."\r\n";
                $this->sendSms(trim($accRow['contactPhone']),$msg);
                //echo $msg." <br/>";
            }
			$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),$mailContent,$headers);
			//mail("suresh@easygaadi.com,krish9067@gmail.com",date('Y-m-d_h:i:sa'),$mailContent,$headers);
            
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function sendSms($inputMob,$inputMsg){
		//$inputMob='9848750094';
        /*$inputMsg="hello how are you,hope you are doing well,wish you all the best";
        echo $str.  strlen($str);*/
        if($inputMob!="" && $inputMsg!=""){
            //$standardSmsLen=500;
			$standardSmsLen=155;//smsstricker
			//$standardSmsLen=480;//smsstricker
            $msgLen=strlen($inputMsg);
            if($msgLen<=$standardSmsLen){
                echo "<br/>Single ".$inputMob." ".$inputMsg;
                Library::sendSingleSms(array('to'=>$inputMob,'message'=>$inputMsg));
				//mail("arjun@easygaadi.com,suresh@easygaadi.com",$inputMob,$inputMsg);
            }else{
                $splitStrArr=explode("***",chunk_split($inputMsg,$standardSmsLen,"***"));
                foreach($splitStrArr as $k=>$v){
                    if($v!=""){
                        echo "<br/>Multiple ".$inputMob." ".$v;
                        Library::sendSingleSms(array('to'=>$inputMob,'message'=>$v));
						//mail("arjun@easygaadi.com,suresh@easygaadi.com",$inputMob,$v);
                    }
                }
            }
        }
    }

	public function actionPeriodicDeviceCheckUp(){
		//http://egcrm.cloudapp.net/operations/index.php/cron/PeriodicDeviceCheckUp
        //lastUpdateTime,lastGPSTimestamp
		//$time=86400;
		$time=21600;
        $rows=Yii::app()->db_gts->CreateCommand("select simPhoneNumber,deviceID,accountID,FROM_UNIXTIME(lastGPSTimestamp) as lastupdatetime from Device where isActive=1 and lastGPSTimestamp!=0 and accountID!='santosh' and lastGPSTimestamp+".$time."<UNIX_TIMESTAMP(NOW())")->queryAll();
        if(sizeof($rows)){
            $message="";
            foreach($rows as $row){
                $message.="Account ID:".$row['accountID'].", Device ID:".$row['deviceID'].", Sim No:".$row['simPhoneNumber'].", LastUpdate:".$row['lastupdatetime']."\r\n";    
            }
            
            if($message!=""){
                echo "message ".$message;
				$headers .= 'From: <info@easygaadi.com>' . "\r\n";
                mail("arjun@easygaadi.com,suresh@easygaadi.com","GPS Device Down - ".date('Y-m-d_h:i:sa'),$message,$headers);
            }
        }
        
    }


	public function actionSynGPAccount(){
		/*
		//exit(CPasswordHelper::hashPassword('9341789035'));
		//http://egcrm.cloudapp.net/operations/index.php/cron/SynGPAccount
				$v="9341789035";
				$Accrow=Yii::app()->db_gts->createCommand("select * from Account where contactPhone='".$v."'")->queryRow();
                if(is_array($Accrow)){
                    $encyPwd=CPasswordHelper::hashPassword($Accrow[password]);
                    $custObj=new Customer;
                    $custObj->gps_account_id=$Accrow[accountID];
                    $custObj->islead=0;
                    $custObj->type='T';
                    $custObj->fullname=$Accrow[contactName];
                    $custObj->date_created=new CDbExpression('NOW()');
                    $custObj->mobile=$Accrow[contactPhone];
                    $custObj->password=$encyPwd;
                    $custObj->status=1;
                    $custObj->approved=1;
                    $custObj->save(false);

                    $idprefix=Library::getIdPrefix(array('id'=>$custObj->id_customer,'type'=>'T'));
                    Customer::model()->updateAll(array('idprefix'=>$idprefix),'id_customer="'.$custObj->id_customer.'"');        
                    $custLeadObj = new Customerlead;
                    $custLeadObj->id_customer = $custObj->id_customer;
                    $custLeadObj->lead_source = 'Truck App';
                    $custLeadObj->lead_status = 'Initiated';
                    $custLeadObj->save(false);
                }
				exit;*/
		/*
		
		exit;
		//Yii::app()->db->enableProfiling=1;
		//http://egcrm.cloudapp.net/operations/index.php/cron/SynGPAccount
        //'9444009518','9346137100','9848159131','8951434426','9448272130','9908126699','9071910991','9885433232','9686666248','9036366786','9341934575','9741973995','9740307341','9986884916','9036238595','9342638027','9964317305','9393624793',
		//'9848371787','9640025888','9951129888','9449541765','9880684390','9487474998','9908570869','9341759151','9448697338','9341882940','9894511364','9392464612','9840936373','9341880759','9880736181','9481428410','9448186762','8971009822',
		$aM=array('9480359256','9666957476','9963454138','9848071179','9676539962','9443302508');
		echo sizeof($aM);
        foreach($aM as $k=>$v){

            $Custrow=Yii::app()->db->createCommand("select * from eg_customer where mobile='".$v."' and type!='G'")->queryRow();
            if(is_array($Custrow)){ //existing customer
				echo "existin ".$v."<br>";// echo '<pre>';print_r($Custrow);exit;
				$Accrow=Yii::app()->db_gts->createCommand("select * from Account where contactPhone='".$v."'")->queryRow();
                if(is_array($Accrow)){
					//echo '<pre>';print_r($Accrow);
					//echo "value of ".$Accrow[accountID];//exit;
                    $encyPwd=CPasswordHelper::hashPassword($Accrow[password]);
                    $idprefix=Library::getIdPrefix(array('id'=>$Custrow['id_customer'],'type'=>'T'));
                    Customer::model()->updateAll(array('idprefix'=>$idprefix,'gps_account_id'=>$Accrow[accountID],'islead'=>0,'type'=>'T','status'=>1,'approved'=>1,'password'=>$encyPwd),'mobile="'.$v.'"');
                }
            }else{ //new customer
                echo "new ".$v."<br>";
                $Accrow=Yii::app()->db_gts->createCommand("select * from Account where contactPhone='".$v."'")->queryRow();
                if(is_array($Accrow)){
                    $encyPwd=CPasswordHelper::hashPassword($Accrow[password]);
                    $custObj=new Customer;
                    $custObj->gps_account_id=$Accrow[accountID];
                    $custObj->islead=0;
                    $custObj->type='T';
                    $custObj->fullname=$Accrow[contactName];
                    $custObj->date_created=new CDbExpression('NOW()');
                    $custObj->mobile=$Accrow[contactPhone];
                    $custObj->password=$encyPwd;
                    $custObj->status=1;
                    $custObj->approved=1;
                    $custObj->save(false);

                    $idprefix=Library::getIdPrefix(array('id'=>$custObj->id_customer,'type'=>'T'));
                    Customer::model()->updateAll(array('idprefix'=>$idprefix),'mobile="'.$v.'"');        
                    $custLeadObj = new Customerlead;
                    $custLeadObj->id_customer = $custObj->id_customer;
                    $custLeadObj->lead_source = 'Truck App';
                    $custLeadObj->lead_status = 'Initiated';
                    $custLeadObj->save(false);
                }
            }
        }*/
    }

	public function actionCalTraffic(){
		//http://egcrm.cloudapp.net/operations/index.php/cron/CalTraffic
		/*$time=86400*15;
		$date=1454022000-$time;//jan 29
		$hour=0;
		for($i=0;$i<=23;$i++){
			$from=$date;
			$to=$from+3600;
			$row=Yii::app()->db_gts->createCommand("select count(*) as records from EventData where creationTime>".$from." and creationTime<".$to)->queryRow();
			Yii::app()->db_gts->createCommand("INSERT INTO `EventDataTraffic` (`Records`, `from`, `to`) VALUES ('".$row['records']."','".date('Y-m-d H:i:s',$from)."','".date('Y-m-d H:i:s',$to)."')")->query();
			$date=$to;
		}*/	
	}
	
	//public function actionMoveDataToTemp(){
	public function actionDeleteEventDataLimit(){
            //date('Y-m-d H:i:s').strtotime(date('Y-m-d H:i:s'))
			//http://egcrm.cloudapp.net/operations/index.php/cron/MoveDataToTemp
            //$limit=50000;
			$limit=80000;
			$daysOld=86400*3;
			$currentDate=strtotime(date('Y-m-d H:i:s')); 
            $execDate=$currentDate-$daysOld;
			$deleteETData=$currentDate-$daysOld-$daysOld;//2months after data will be delete in EventDataTemp
            //exit("value of ".$execDate." deleteETData ".$deleteETData);
            if($execDate!=""){

			//Yii::app()->db_gts->CreateCommand("delete from EventData where timestamp=0  and accountID!='' and deviceID!=''")->query();

			//$instEDT=Yii::app()->db_gts->CreateCommand("INSERT INTO EventDataTemp SELECT * FROM EventData WHERE creationTime<".$execDate." and accountID!='demo'")->query();

			$instEDT=Yii::app()->db_gts->CreateCommand("INSERT INTO EventDataTemp SELECT `accountID`, `deviceID`, `timestamp`,UNIX_TIMESTAMP(NOW()) as `statusCode`, `latitude`, `longitude`, `gpsAge`, `speedKPH`, `heading`, `altitude`, `transportID`, `inputMask`, `outputMask`, `address`, `dataSource`, `rawData`, `distanceKM`, `odometerKM`, `odometerOffsetKM`, `geozoneIndex`, `geozoneID`, `creationTime` FROM EventData WHERE creationTime<".$execDate." and accountID!='demo' limit ".$limit)->query();

            if(is_object($instEDT)){
                Yii::app()->db_gts->CreateCommand("delete from EventData where creationTime<".$execDate." and accountID!='demo' limit  ".$limit)->query();

				/*$count=Yii::app()->db_gts->CreateCommand("select count(*) as count from EventData where creationTime<".$execDate." and accountID!='demo'")->queryScalar();
				//$count=100000;
				$limit=10000;
				while($count>1){
					//echo $count."<br/>";
					Yii::app()->db_gts->CreateCommand("delete from EventData where creationTime<".$execDate." and accountID!='demo' limit  ".$limit)->query();
					$count=$count-$limit;
				}
                
				
				$count=Yii::app()->db_gts->CreateCommand("select count(*) as count from EventDataTemp where creationTime<".$deleteETData." and accountID!='demo'")->queryScalar();
				while($count>1){
					//echo $count."<br/>";
					Yii::app()->db_gts->CreateCommand("delete from EventDataTemp where creationTime<".$deleteETData." and accountID!='' limit ".$limit)->query();
                	$count=$count-$limit;
				}*/
			
				echo "Query Success";
            }else{
                echo "Query Failed";
            }
		}
    }

	public function actionMoveDataToTemp1(){}
	public function actionMoveDataToTempComment(){
            //date('Y-m-d H:i:s').strtotime(date('Y-m-d H:i:s'))
			//http://egcrm.cloudapp.net/operations/index.php/cron/MoveDataToTemp
            $daysOld=86400*31;
			$currentDate=strtotime(date('Y-m-d H:i:s')); 
            $execDate=$currentDate-$daysOld;
			$deleteETData=$currentDate-$daysOld-$daysOld;//2months after data will be delete in EventDataTemp
            //exit("value of ".$execDate." deleteETData ".$deleteETData);
            if($execDate!=""){

			Yii::app()->db_gts->CreateCommand("delete from EventData where timestamp=0  and accountID!='' and deviceID!=''")->query();

			$instEDT=Yii::app()->db_gts->CreateCommand("INSERT INTO EventDataTemp SELECT * FROM EventData WHERE creationTime<".$execDate." and accountID!='demo'")->query();

            if(is_object($instEDT)){
                $count=Yii::app()->db_gts->CreateCommand("select count(*) as count from EventData where creationTime<".$execDate." and accountID!='demo'")->queryScalar();
				//$count=100000;
				$limit=10000;
				while($count>1){
					//echo $count."<br/>";
					Yii::app()->db_gts->CreateCommand("delete from EventData where creationTime<".$execDate." and accountID!='demo' limit  ".$limit)->query();
					$count=$count-$limit;
				}
                
				
				$count=Yii::app()->db_gts->CreateCommand("select count(*) as count from EventDataTemp where creationTime<".$deleteETData." and accountID!='demo'")->queryScalar();
				while($count>1){
					//echo $count."<br/>";
					Yii::app()->db_gts->CreateCommand("delete from EventDataTemp where creationTime<".$deleteETData." and accountID!='' limit ".$limit)->query();
                	$count=$count-$limit;
				}
				
				Yii::app()->db_gts->CreateCommand("update EventData  set odometerKM=0 where accountID!='' and deviceID!='' and odometerKM is NULL")->query();
				Yii::app()->db_gts->CreateCommand("update EventData  set distanceKM=0 where accountID!='' and deviceID!='' and  distanceKM is NULL")->query();
				Yii::app()->db_gts->CreateCommand("update Device set lastDistanceKM=0 where accountID!='' and deviceID!='' and  lastDistanceKM is NULL")->query();
				Yii::app()->db_gts->CreateCommand("update Device set lastOdometerKM=0 where accountID!='' and deviceID!='' and  lastOdometerKM is NULL")->query();

				echo "Query Success";
            }else{
                echo "Query Failed";
            }
		}
    }

	public function actiongetOrdersMail() {
		//http://egcrm.cloudapp.net/operations/index.php/cron/getOrdersMail
        $yesterday = '2016-06-30';//date('Y-m-d', (time() - 86400));
        //exit("value of ".$yesterday);
        $rows = Yii::app()->db->createCommand('SELECT t.*,DATE_FORMAT(date_ordered,"%d-%m-%Y") as date_ordered,DATE_FORMAT(date_available,"%d-%m-%Y %h:%i %p") as date_available,(select count(*) as count from eg_order_transaction_history oth where oth.id_order=t.id_order and oth.customer_type="L" and oth.amount_prefix="+" and comment="Advance Received") as load_owner_advance_recv,(select concat(a.first_name," ",a.last_name) from eg_admin a where a.id_admin=t.id_admin_created) as admin_created,(select oh.message from eg_order_history oh where oh.id_order=t.id_order order by date_created desc limit 1) as message, (select sum(obhc.amount) from eg_order_billing_history obhc where obhc.customer_type="T" and obhc.comment="Commission" and obhc.amount_prefix="-" and obhc.id_order=t.id_order) as truck_owner_commission,(select (sum(if(amount_prefix="+",amount,0))-sum(if(amount_prefix="-",amount,0))) as billing from eg_order_billing_history obh where obh.id_order=t.id_order and obh.customer_type="L" ) as billing,(select (sum(if(amount_prefix="+",amount,0))-sum(if(amount_prefix="-",amount,0))) as billing from eg_order_billing_history obh where obh.id_order=t.id_order and obh.customer_type="T") as tobilling FROM `eg_order` `t` where date(t.date_created)>"' . $yesterday . '"')->queryAll();
        if (sizeof($rows)) {
            $sno = 1;
            $txt = '<table border="1">
	<tr><td>S.No</td><td>Order Details</td><td>Load Details</td><td>Truck Details</td></tr>';
            foreach ($rows as $row) {
                $txt.='<tr>
	<td>' . $sno . '
	</td>
	<td>
		<table border="1">
			<tr><td>Trip</td><td>' . substr($row['source_address'], 0, -7) . '-' . substr($row['destination_address'], 0, -7) . '</td></tr>
			<tr><td>Load Owner</td><td>' . $row['orderperson_fullname'] . '/' . $row['orderperson_mobile'] . '</td></tr>
			<tr><td>Truck Owner</td><td>' . $row['customer_fullname'] . '/' . $row['customer_mobile'] . '</td></tr>
			<tr><td>Truck No</td><td>' . $row['truck_reg_no'] . '/' . $row['truck_type'] . '</td></tr>
			<tr style="color:green;font-weight:bold;"><td>Net Profit</td><td>' . number_format(((int) $row['billing'] - (int) $row['tobilling']), 2) . '</td></tr>
			<tr style="color:green;font-weight:bold;"><td>Commission</td><td>' . number_format((int) $row['truck_owner_commission'], 2) . '</td></tr>
			<tr><td>Date Booked</td><td>' . $row['date_ordered'] . '</td></tr>
			<tr><td>OrdId</td><td>#Ord' . $row['id_order'] . '</td></tr>
		</table>
	</td>
	<td>
		<table border="1">
                        <tr><td colspan="2"><b>Load Billing</b></td></tr>';
                $lbillingRows = Yii::app()->db->createCommand("select * from eg_order_billing_history where customer_type='L' and id_order='" . $row['id_order'] . "' order by comment asc")->queryAll();
                
				$lbgt = 0;
				$lbbamount=0;
				$ltar=0;
				$ltbar=0;
				foreach ($lbillingRows as $billingRow) {
					if($billingRow['comment']=='Booked Amount'){ $lbbamount=1;}
					$lbgt = $billingRow['amount_prefix'] == '+' ? $lbgt + $billingRow['amount'] : $lbgt - $billingRow['amount'];
					$txt.='<tr><td>' . $billingRow['comment'] . '(' . $billingRow['amount_prefix'] . ')</td><td>' . $billingRow['amount'] . '</td></tr>';
				}
				if(!$lbbamount){
					$txt.='<tr style="color:red;font-weight:bold;"><td>Booked Amount(+)</td><td>0</td></tr>';
				}
				$txt.='<tr style="color:blue;font-weight:bold;"><td><i>Grand Total</i></td><td>' . number_format($lbgt,2) . '</td></tr>';
                

                $txt.='<tr><td colspan="2"><b>Load Receivables</b></td></tr>';
                $ltranRows = Yii::app()->db->createCommand("select * from eg_order_transaction_history where customer_type='L' and id_order='" . $row['id_order'] . "' order by comment asc")->queryAll();
                    $lbgtt = 0;
                    foreach ($ltranRows as $billingRow) {
						if($billingRow['comment']=='Advance Received'){ $ltar=1;}
						if($billingRow['comment']=='Balance Amount Received'){ $ltbar=1;}
                        $lbgtt = $billingRow['amount_prefix'] == '+' ? $lbgtt + $billingRow['amount'] : $lbgtt - $billingRow['amount'];
                        $txt.='<tr><td>' . $billingRow['comment'] . '(' . $billingRow['amount_prefix'] . ')</td><td>' . $billingRow['amount'] . '</td></tr>';
                    }
					if(!$ltar){
                        $txt.='<tr style="color:red;font-weight:bold;"><td>Advance Received(+)</td><td>0</td></tr>';
                    }
                    if(!$ltbar){
                        $txt.='<tr style="color:red;font-weight:bold;"><td>Balance Amount Received(+)</td><td>0</td></tr>';
                    }
                    $txt.='<tr style="color:green;font-weight:bold;"><td><i>Balance</i></td><td>' . number_format(($lbgt - $lbgtt),2) . '</td></tr>';
                
                /* $txt.='<tr><td colspan="2"><b>Transaction</b></td></tr>';
                  $txt.='<tr><td>Advance Paid(+)</td><td>9300</td></tr>';
                  $txt.='<tr><td><i>Balance</i></td><td>9300</td></tr>'; */
                $txt.='</table>
	</td>
		<td>
		<table border="1">
                        <tr><td colspan="2"><b>Truck Billing</b></td></tr>';
                $lbillingRows = Yii::app()->db->createCommand("select * from eg_order_billing_history where customer_type='T' and id_order='" . $row['id_order'] . "' order by comment asc")->queryAll();
                    $lbgt = 0;
                    $tbba=0;
					foreach ($lbillingRows as $billingRow) {
						if($billingRow['comment']=='Booked Amount'){ $tbba=1;}
                        $lbgt = $billingRow['amount_prefix'] == '+' ? $lbgt + $billingRow['amount'] : $lbgt - $billingRow['amount'];
                        $txt.='<tr><td>' . $billingRow['comment'] . '(' . $billingRow['amount_prefix'] . ')</td><td>' . $billingRow['amount'] . '</td></tr>';
                    }
					if(!$tbba){
                        $txt.='<tr style="color:red;font-weight:bold;"><td>Booked Amount(+)</td><td>0</td></tr>';
                    }
                    $txt.='<tr style="color:blue;font-weight:bold;"><td><i>Grand Total</i></td><td>' . number_format($lbgt,2) . '</td></tr>';
                
                $txt.='<tr><td colspan="2"><b>Truck Payments</b></td></tr>';
                $ltranRows = Yii::app()->db->createCommand("select * from eg_order_transaction_history where customer_type='T' and id_order='" . $row['id_order'] . "' order by comment asc")->queryAll();
                
                    $lbgtt = 0;
					$ttar=0;
                    $ttbar=0;
                    foreach ($ltranRows as $billingRow) {
                        if($billingRow['comment']=='Advance Paid'){ $ttar=1;}
                        if($billingRow['comment']=='Balance Amount Paid'){ $ttbar=1;}
						$lbgtt = $billingRow['amount_prefix'] == '+' ? $lbgtt + $billingRow['amount'] : $lbgtt - $billingRow['amount'];
                        $txt.='<tr><td>' . $billingRow['comment'] . '(' . $billingRow['amount_prefix'] . ')</td><td>' . $billingRow['amount'] . '</td></tr>';
                    }
					if(!$ttar){
                        $txt.='<tr style="color:red;font-weight:bold;"><td>Advance Paid(+)</td><td>0</td></tr>';
                    }
                    if(!$ttbar){
                        $txt.='<tr style="color:red;font-weight:bold;"><td>Balance Amount Paid(+)</td><td>0</td></tr>';
                    }
                    $txt.='<tr style="color:green;font-weight:bold;"><td><i>Balance</i></td><td>' . number_format(($lbgt - $lbgtt),2) . '</td></tr>';
                $txt.='</table>
	</td>
	</tr>';
                $sno++;
            }
            $txt.='</table>';
        } else {
            $txt = 'No orders!!';
        }
        //echo $txt;
		//exit;
        $to = 'suresh@easygaadi.com,team@easygaadi.com,info@easygaadi.com,ellite007@gmail.com,gouthamgandra@gmail.com,executive.easygaadi@gmail.com';
        $subject = 'Orders On ' . $yesterday;
        $headers = "From: info@easygaadi.com\r\n";
//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $subject, $txt, $headers);
    }

	public function actionstopAlert(){
		//exit;
		//http://egcrm.cloudapp.net/operations/index.php/cron/stopAlert
        $group[10]='7337359072';//Suman N
        $group[9]='7032978803';//santosh/charan
        //$group[6]='7331135221';//suman
        //$group[8]='8978938888';//ranga rao
		$group[8]='7331335220';//ranga rao replaced with krisham raju
        $group[11]='7331135221';//Bhaskar

		//lastStartTime no of waiting mins
        $curTime=time()+400;//10mins late so adding less than 10mins 
        $stopNextMsgTime=1500; //after 25mins //april 19 2017
		//$stopNextMsgTime=2700; //after 45mins
        //$prevTime=$curTime-4800;//1.20mins search from last 1.20mins april 19 2017
		$prevTime=$curTime-7200;//2.00mins search from last 2.00mins
        $twoDaysOld=$curTime-259200; //trip not older than 3 days
        $lastGPSTimestamp=$curTime-7200; //gps active since last 2 hours
        //$rows=Yii::app()->db->createCommand("select groupID,lastValidLongitude,lastValidLatitude,deviceID,accountID,lastStopTime from Device  where lastValidSpeedKPH<10 and lastMalfunctionLamp=1 and accountID='venkatreddy' and lastStopTime+".$stopNextMsgTime."< ".$curTime)->queryAll();
        
        $rows=Yii::app()->db_gts->createCommand("select distinct(d.deviceID),d.lastGPSTimestamp,d.groupID,d.lastValidLongitude,d.lastValidLatitude,d.accountID from Device d,Trip t  where t.accountID=d.accountID and t.deviceID=d.deviceID and t.startPointTime>".$twoDaysOld." and t.endPointTime=0 and d.lastGPSTimestamp>".$lastGPSTimestamp." and d.lastValidSpeedKPH<5  and d.accountID='venkatreddy' and ifnull(d.lastStopTime,0)+".$stopNextMsgTime."< ".$curTime)->queryAll();
		
		//echo "select d.groupID,d.lastValidLongitude,d.lastValidLatitude,d.deviceID,d.accountID from Device d,Trip t  where t.accountID=d.accountID and t.deviceID=d.deviceID and t.startPointTime>".$twoDaysOld." and t.endPointTime=0 and d.lastGPSTimestamp>".$lastGPSTimestamp." and d.lastValidSpeedKPH<5  and d.accountID='venkatreddy' and ifnull(d.lastStopTime,0)+".$stopNextMsgTime."< ".$curTime;
		//exit;

		//echo "select d.groupID,d.lastValidLongitude,d.lastValidLatitude,d.deviceID,d.accountID,d.lastStopTime from Device d,Trip t  where t.accountID=d.accountID and t.deviceID=d.deviceID and t.startPointTime>".$twoDaysOld." and t.endPointTime=0 and d.lastGPSTimestamp>".$lastGPSTimestamp."   and d.accountID='venkatreddy' and d.lastStopTime+".$stopNextMsgTime."< ".$curTime;
		//and d.lastValidSpeedKPH<10
        //device active since more than 2 hours
        //and d.lastMalfunctionLamp=1 temporary
		
		//echo "select d.groupID,d.lastValidLongitude,d.lastValidLatitude,d.deviceID,d.accountID,d.lastStopTime from Device d,Trip t  where t.accountID=d.accountID and t.deviceID=d.deviceID and t.startPointTime>".$twoDaysOld." and t.endPointTime=0 and d.lastGPSTimestamp>".$lastGPSTimestamp." and d.lastValidSpeedKPH<10  and d.accountID='venkatreddy' and d.lastStopTime+".$stopNextMsgTime."< ".$curTime;
		//echo '<pre>';print_r($rows);exit;
        
		//echo '<pre>';print_r($rows);exit;
		Yii::app()->db_gts->createCommand("update Device set lastStartTime=0 where lastStartTime!=0 and accountID='venkatreddy'")->query();
        
		foreach($rows as $row){

            //no of kms less in 1 hour,
            //$prevTime=1474481000+3600; //9/21/2016, 11:33:20 PM //for testing
			//$tempStopTime="and timestamp<".(1474484600+4800);//12:33:20 AM //for testing
			$eventRows=Yii::app()->db_gts->createCommand("select speedKPH,timestamp from EventData where accountID='".$row['accountID']."' and deviceID='".$row['deviceID']."' and timestamp>".$prevTime." ".$tempStopTime." order by timestamp asc")->queryAll();

            $stopTime=0;
			//print_r($eventRows); //exit;
            foreach($eventRows as $k=>$v){
                if($eventRows[$k]['speedKPH']<10 && $eventRows[$k+1]['speedKPH']<10 && $eventRows[$k+1]['speedKPH']!=""){

                    $stopTime+=$eventRows[$k+1]['timestamp']-$eventRows[$k]['timestamp'];

					echo (round($stopTime)/60)." mins ".$eventRows[$k+1]['timestamp']."-".$eventRows[$k]['timestamp']." ".$row['deviceID']." <br/>";
                }
            }
            
            if($stopTime>=1500)//25mins wait time
            {
				//echo "inside";
                $this->stopAlertSms(array('lastGPSTimestamp'=>$curTime,'mobile'=>$group[$row['groupID']],'stopTime'=>$stopTime,'lat'=>$row['lastValidLatitude'],'lng'=>$row['lastValidLongitude'],'deviceID'=>$row['deviceID'],'accountID'=>$row['accountID']));
            //echo '<pre>';print_r(array('lastGPSTimestamp'=>$curTime,'mobile'=>$group[$row['groupID']],'stopTime'=>$stopTime,'lat'=>$row['lastValidLatitude'],'lng'=>$row['lastValidLongitude'],'deviceID'=>$row['deviceID'],'accountID'=>$row['accountID']));echo '</pre>';
			}
        }
		//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
		//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"Stop Alerts",$headers);
		//exit("at last ".$stopTime/60);
    }
    
    public function stopAlertSms($input){
		//echo '<pre>';print_r($input);echo '</pre>';//exit;
        $stopTime=round($input['stopTime']/60);
        //$addrArr=Library::getGPBYLATLNGDetailsCloud($input['lat'].",".$input['lng']);
		$loc=Library::getGPBYLATLNGDetailsGoogle($input['lat'].",".$input['lng']);
						$addrArr=substr(str_replace(', ',',',$loc['address']),0,-13);

        //$message=$input['deviceID']." is at ".$addrArr['address']." more than ".$stopTime." mins";
		$message=$input['deviceID']." is at ".$addrArr." more than ".$stopTime." mins";
		//echo $message;
		//exit;
        if($stopTime>53){
            //echo $input['mobile'].$message."in if ".$stopTime."<br/>";  
		    //echo "update Device set lastStopTime=".time()." where accountID='venkatreddy' and deviceID='".$input['deviceID']."'";
           // Library::sendSingleSms(array('to' => '9100655113', 'message' =>$message));//old no 7032978801 april 15 2017
            //$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa')." TO Srikanth for 54+ ",$message,$headers);
			$this->pushStopNotiJV($message);
        }else{
			//echo $input['mobile'].$message."in else ".$stopTime."<br/>";
            if($input['mobile']!=""){
				//Library::sendSingleSms(array('to' => $input['mobile'], 'message' =>$message)); //deactivated sms on march 9 2017
			}
			//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa')." TO ".$input['mobile'],$message,$headers);
        }

		Yii::app()->db_gts->createCommand("update Device set lastStopTime=".$input['lastGPSTimestamp']." where accountID='venkatreddy' and deviceID='".$input['deviceID']."'")->query();
		//echo "<br/>update Device set lastStartTime=".$input['stopTime']." where accountID='venkatreddy' and deviceID='".$input['deviceID']."'";
        Yii::app()->db_gts->createCommand("update Device set lastStartTime=".$input['stopTime']." where accountID='venkatreddy' and deviceID='".$input['deviceID']."'")->query();
    }

	public function pushStopNotiJV($message) {
		$pushSql = "select distinct device_id from {{gps_login_device}}  where lower(username) like 'venkatreddy'";
		$pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
		$devices = array();
		foreach ($pushRows as $k => $row) {
			$devices[] = $row['device_id'];
		}

		if(sizeof($devices)){
			$pushArray=array('devices' => $devices, 'message' => array('message' =>"Stop Alert:".$message, 'type' => 'general'));
			$result = Library::sendPushNotification($pushArray);
			Gpslogindevice::updateDuplicateDevices($devices,$result);
		}

		$model=new Notification;
		$model->accountID='venkatreddy';
		$model->info=$message;
		$model->visibility = 'Single';
		$model->save(false);
	}
	
	public function actionmorningStarStops() {
        //http://egcrm.cloudapp.net/operations/index.php/cron/morningStarStops
        
        $points[0]=array('lat'=>16.29799,'lng'=>80.44676,'addr'=>'Nissankaravari Street, Railpet, Guntur');//'16.29799,80.44676'; //Nissankaravari Street, Railpet
        $points[1]=array('lat'=>16.29717,'lng'=>80.44636,'addr'=>'Naaz Circle, Railpet, Guntur');//'16.29717,80.44636'; //Nazz_Center
        $points[2]=array('lat'=>16.29928,'lng'=>80.44044,'addr'=>'Railway Station Road,Kanna Vari Thota');//'16.29928,80.44044';//Railway_Qtr
        $points[3]=array('lat'=>16.29653,'lng'=>80.43431,'addr'=>'Collector Office Road');//'16.29653,80.43431';//Collector_Offc
        $points[4]=array('lat'=>16.30199,'lng'=>80.42559,'addr'=>'Ankamma Nagar, Kankaragunnta Turning Point');//'16.30199,80.42559';//Ankamma_Nagar
        $points[5]=array('lat'=>16.300955,'lng'=>80.424712,'addr'=>'A.T.Agraramm 1st Line');//'16.300955,80.424712';//AT_1st
        $points[6]=array('lat'=>16.29938,'lng'=>80.42495,'addr'=>'A.T.Agraramm 4th Line');//'16.29938,80.42495';//AT_4th
        $points[7]=array('lat'=>16.29321,'lng'=>80.42528,'addr'=>'A.T.Agraramm 17th Line');//'16.29321,80.42528';//AT_17th
        $points[8]=array('lat'=>16.30576,'lng'=>80.37028,'addr'=>'Loyola Public School');//'16.30576,80.37028';//School
        
        /*
        $points[8]=array('lat'=>16.29799,'lng'=>80.44676,'addr'=>'Nissankaravari Street, Railpet, Guntur');//'16.29799,80.44676'; //Nissankaravari Street, Railpet
        $points[7]=array('lat'=>16.29717,'lng'=>80.44636,'addr'=>'Naaz Circle, Railpet, Guntur');//'16.29717,80.44636'; //Nazz_Center
        $points[6]=array('lat'=>16.29928,'lng'=>80.44044,'addr'=>'Railway Station Road,Kanna Vari Thota');//'16.29928,80.44044';//Railway_Qtr
        $points[5]=array('lat'=>16.29653,'lng'=>80.43431,'addr'=>'Collector Office Road');//'16.29653,80.43431';//Collector_Offc
        $points[4]=array('lat'=>16.30199,'lng'=>80.42559,'addr'=>'Ankamma Nagar, Kankaragunnta Turning Point');//'16.30199,80.42559';//Ankamma_Nagar
        $points[3]=array('lat'=>16.300955,'lng'=>80.424712,'addr'=>'A.T.Agraramm 1st Line');//'16.300955,80.424712';//AT_1st
        $points[2]=array('lat'=>16.29938,'lng'=>80.42495,'addr'=>'A.T.Agraramm 4th Line');//'16.29938,80.42495';//AT_4th
        $points[1]=array('lat'=>16.29321,'lng'=>80.42528,'addr'=>'A.T.Agraramm 17th Line');//'16.29321,80.42528';//AT_17th
        $points[0]=array('lat'=>16.30576,'lng'=>80.37028,'addr'=>'Loyola Public School');//'16.30576,80.37028';//School
         **/
        //server time is 10mins less compared to original
        $curTime=time()-1200;//active since 20mins=20+10 min late
        //echo $curTime." - time ".time();exit;
        if(date('H')>15){  //route will be reverse in evening from school to parking
            $points=  array_reverse($points);
        }
        
        $row=Yii::app()->db_gts->createCommand("select  lastInputState,lastValidSpeedKPH as speedKPH,lastGPSTimestamp from Device where accountID='morningstar' and deviceID='AP07T9473' and lastValidSpeedKPH>0 and lastGPSTimestamp>".$curTime)->queryRow();
        $lastInputState=$row['lastInputState'];
        //echo 'here<pre>';print_r($row);exit;
	if($row['speedKPH']>0){	
            
			$sql="select  latitude,longitude,timestamp from EventData where accountID='morningstar' and deviceID='AP07T9473' and timestamp<".($row['lastGPSTimestamp']+600)." order by timestamp desc limit 5";
			$events=Yii::app()->db_gts->createCommand($sql)->queryAll();
            echo '<pre>';print_r($events);
			//exit($sql);
			foreach($events as $event){
                $km=  number_format($this->distance($points[$lastInputState+1]['lat'],$points[$lastInputState+1]['lng'],$event['latitude'], $event['longitude'],'K'),3);
                //echo $km." km ".$lastInputState." point ".$points[$lastInputState+1]['lat'].",".$points[$lastInputState+1]['lng'].",".$event['latitude'].",". $event['longitude']."<br/>";
                //echo $km;//.'<pre>';print_r($row);print_r($points);//exit();
                if(0.05>$km){
                    //if(1){
                    echo "<br><b>".$points[$lastInputState+1]['addr']." KM ".$km."  Dt ".date('h:i A',$event['timestamp'])." ".$points[$lastInputState+1]['lat'].",".$points[$lastInputState+1]['lng']." -- ".$event['latitude'].",". $event['longitude']."</b>";
                    //9030849999
					//exit("in if");
                    Library::sendSingleSms(array('to' => '9030849999', 'message' =>"Bus:AP07T9473 reached  ".$points[$lastInputState+1]['addr']." : ".date('h:i A',$event['timestamp'])));
                    
					mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"Bus:AP07T9473 reached  ".$points[$lastInputState+1]['addr']." : ".date('h:i A',$event['timestamp']),$headers);
					
					$lastInputState=$lastInputState==7?0:$lastInputState+1;
                    Yii::app()->db_gts->createCommand("update Device set lastInputState='".$lastInputState."' where accountID='morningstar' and deviceID='AP07T9473'")->query();
					$headers .= 'From: <info@easygaadi.com>' . "\r\n";
					
                    exit;
                }else{
                    echo "<br>".$points[$lastInputState+1]['addr']." KM ".$km."  Dt ".date('h:i A',$event['timestamp'])." ".$points[$lastInputState+1]['lat'].",".$points[$lastInputState+1]['lng']." -- ".$event['latitude'].",". $event['longitude'];
                }
            }
        }

	//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
	//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"test",$headers);
		exit("end");
    }

	public function actionmorningStarStopsDevice() {
        //http://egcrm.cloudapp.net/operations/index.php/cron/morningStarStops
        
        $points[0]=array('lat'=>16.29799,'lng'=>80.44676,'addr'=>'Nissankaravari Street, Railpet, Guntur');//'16.29799,80.44676'; //Nissankaravari Street, Railpet
        $points[1]=array('lat'=>16.29717,'lng'=>80.44636,'addr'=>'Naaz Circle, Railpet, Guntur');//'16.29717,80.44636'; //Nazz_Center
        $points[2]=array('lat'=>16.29928,'lng'=>80.44044,'addr'=>'Railway Station Road,Kanna Vari Thota');//'16.29928,80.44044';//Railway_Qtr
        $points[3]=array('lat'=>16.29653,'lng'=>80.43431,'addr'=>'Collector Office Road');//'16.29653,80.43431';//Collector_Offc
        $points[4]=array('lat'=>16.30199,'lng'=>80.42559,'addr'=>'Ankamma Nagar, Kankaragunnta Turning Point');//'16.30199,80.42559';//Ankamma_Nagar
        $points[5]=array('lat'=>16.300955,'lng'=>80.424712,'addr'=>'A.T.Agraramm 1st Line');//'16.300955,80.424712';//AT_1st
        $points[6]=array('lat'=>16.29938,'lng'=>80.42495,'addr'=>'A.T.Agraramm 4th Line');//'16.29938,80.42495';//AT_4th
        $points[7]=array('lat'=>16.29321,'lng'=>80.42528,'addr'=>'A.T.Agraramm 17th Line');//'16.29321,80.42528';//AT_17th
        $points[8]=array('lat'=>16.306306,'lng'=>80.370516,'addr'=>'Loyola Public School');//'16.306306,80.370516';//School
        
        if(date('H')>15){  //route will be reverse in evening from school to parking
            $points=  array_reverse($points);
        }
        
        $row=Yii::app()->db_gts->createCommand("select  lastInputState,lastValidLatitude as latitude,lastValidLongitude as longitude, lastValidSpeedKPH as speedKPH,FROM_UNIXTIME(ifnull(lastGPSTimestamp,0), '%h:%i %p') as dat from Device where accountID='morningstar' and deviceID='AP07T9473'")->queryRow();
        $lastInputState=$row['lastInputState'];
		
        $km=  number_format($this->distance($points[$lastInputState+1]['lat'],$points[$lastInputState+1]['lng'],$row['latitude'], $row['longitude'],'K'),3);
		echo $lastInputState." hello  ".$points[$lastInputState+1]['lat'].",".$points[$lastInputState+1]['lng'].",".$row['latitude'].",". $row['longitude']."<br/>";
        echo $km;//.'<pre>';print_r($row);print_r($points);//exit();
			if(0.01>$km){
			//if(1){
                echo "<br><b>".$points[$lastInputState+1]['addr']." KM ".$km." Speed ".round($row['speedKPH'])." Addr ".$addrArr['address']." Dt ".$row['dat']."</b>";
                //9030849999
                
                Library::sendSingleSms(array('to' => '9848750094', 'message' =>"Bus:AP07T9473 reached  ".$points[$lastInputState+1]['addr']." : ".$row['dat']));
                $lastInputState=$lastInputState==7?0:$lastInputState+1;
                Yii::app()->db_gts->createCommand("update Device set lastInputState='".$lastInputState."' where accountID='morningstar' and deviceID='AP07T9473'")->query();
            }else{
                echo "<br> KM ".$km." Speed ".round($row['speedKPH'])." Addr ".$addrArr['address']." Dt ".$row['dat'];
            }

			//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"test",$headers);
    }

	function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);//1.609344
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

	public function actiongeofence() {
		//http://egcrm.cloudapp.net/operations/index.php/cron/geofence
        $vertices_y = array(17.534488365367608, 17.535716000689618, 17.538897583547914, 17.540626458857716, 17.539347706517912, 17.537966643859495, 17.534866887144812, 17.531900073665067, 17.528902519723577, 17.52724514781469, 17.526774533277937, 17.524626060102726, 17.523909896724216, 17.52145445796834, 17.51742337397437, 17.515479418226647, 17.511816539785315, 17.50635277933786, 17.504040351821804, 17.498207992404282, 17.49783962657735, 17.496509410430743, 17.496120576179855, 17.489714923810755, 17.48793439888362, 17.483616041881167, 17.481323791771132, 17.477762560203498, 17.473648636978265, 17.464458492545752, 17.45862486401386, 17.452361181207785, 17.451153452901828, 17.4510306326258, 17.453875947768783, 17.454234167749764, 17.45174708306928, 17.45307762642074, 17.45868627160292, 17.464550600971556, 17.47113111570858, 17.47491761823993, 17.477936530031588, 17.48156939137876, 17.487627410065187, 17.49327592135876, 17.496581037702025, 17.49799311242931, 17.49899588347218, 17.503334339325576, 17.506547185619503, 17.512103024561654, 17.515765897223574, 17.51810886919443, 17.521679541237475, 17.52338811876857, 17.52449305854616, 17.525976531925604, 17.525464990751086, 17.527265609288584, 17.528124989108644, 17.529096901856576, 17.53399730891171, 17.536329815233838, 17.53950115727496, 17.541178877078327, 17.53953184741079, 17.534795274977277);

        $vertices_x = array(78.35794150829315, 78.35826337337494, 78.35900366306305, 78.36356341838837, 78.36327373981476, 78.3638209104538, 78.36512982845306, 78.36532294750214, 78.36510300636292, 78.3678925037384, 78.368901014328, 78.37108969688416, 78.37413668632507, 78.37488770484924, 78.37990880012512, 78.38214039802551, 78.38398575782776, 78.38589549064636, 78.38724732398987, 78.38965058326721, 78.39125990867615, 78.39340567588806, 78.39454293251038, 78.39203238487244, 78.38937163352966, 78.38926434516907, 78.38909268379211, 78.39022994041443, 78.3875048160553, 78.3850371837616, 78.38420033454895, 78.3824622631073, 78.3960235118866, 78.39683890342712, 78.39797616004944, 78.3973377943039, 78.39637219905853, 78.38317573070526, 78.38486015796661, 78.38581502437592, 78.38746726512909, 78.38901221752167, 78.3907288312912, 78.38958084583282, 78.39174807071686, 78.3941513299942, 78.39546024799347, 78.39242398738861, 78.39002072811127, 78.38857233524323, 78.38649094104767, 78.38448464870453, 78.38249981403351, 78.38011801242828, 78.37562263011932, 78.37527930736542, 78.37451756000519, 78.37259709835052, 78.37098777294159, 78.36927115917206, 78.36743652820587, 78.36565554141998, 78.36563408374786, 78.36507618427277, 78.36370289325714, 78.3640569448471, 78.3588856458664, 78.35752308368683);
        
        $row = Yii::app()->db_gts->createCommand("select lastValidLatitude,lastValidLongitude  from Device where accountID='accounts' and deviceID='358511021202324' and imeiNumber='358511021202324'")->queryRow();
        //$row['lastValidLongitude']='78.354425';
		//$row['lastValidLatitude']='17.526666';
// y-coordinates of the vertices of the polygon
        $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
        $longitude_x = $row['lastValidLongitude'];//$_GET["lon"];  // x-coordinate of the point to test
        $latitude_y = $row['lastValidLatitude'];//$_GET["lat"];    // y-coordinate of the point to test
        echo $longitude_x . " " . $latitude_y;
        echo '<pre>';
//print_r($vertices_x);
//print_r($vertices_y);
        if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) {
            echo "Is in polygon!";
        } else {
            echo "Is not in polygon";
            $headers .= 'From: <info@easygaadi.com>' . "\r\n";
            $addrArr = Library::getGPBYLATLNGDetailsCloud($row['lastValidLatitude'] . "," . $row['lastValidLongitude']);
            $msg="Mr.Anthony took diversion at ".$addrArr['address'];
            //mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);
            //mail("sravan@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);
			//mail("info@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);
        }
		//$msg="Mr.Anthony took diversion at ".$addrArr['address'];
        //mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);
    }

	function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
            $i = $j = $c = 0;
            for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
                if ((($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])))
                    $c = !$c;
            }
            return $c;
        }

		public function actionMedicareMove() {
	//http://egcrm.cloudapp.net/operations/index.php/cron/MedicareMove
	//40 speed
	//$currenttime=1477973329;//time()+600;
	$currenttime=time()+600;
	$checkTime=$currenttime-2700; //40min
    $fromTime= $currenttime-5400; //90min
	echo "CT".$currenttime." FT ".$fromTime." TT ".$checkTime;//exit;
	$devices=array('AP15AM2322','TS02UA3783');
	foreach($devices as $device){
		echo "<b>".$device."</b><br/>";
	$rows=Yii::app()->db_gts->createCommand("select inputMask,distanceKM,latitude,longitude,speedKPH,timestamp from EventData where accountID='medcarehospital' and deviceID='".$device."' and timestamp>".$fromTime."  order by timestamp desc")->queryAll();
	$time=0;
	$alert=0;
	echo '<pre>';print_r($rows);//exit;
	foreach($rows as $k=>$row){

	   if($row['timestamp']>=$checkTime){
		   echo $row['timestamp']."<br/>";
		   if(($row['speedKPH']!=0)){
		   //if(($rows[$k]['speedKPH']!=0) || ($rows[$k+1]['speedKPH']!="")){
			//if(($rows[$k]['speedKPH']!=0 && $rows[$k+1]['timestamp']!="")){
            //if(isset($rows[$k+1]['timestamp'])){ 
			   $time+=$rows[$k]['timestamp']-$rows[$k+1]['timestamp'];
			   echo "Time : ".$rows[$k]['timestamp']."-".$rows[$k+1]['timestamp']."=".$time."<br/>";
		   }
	   }

	   if($row['inputMask']==1){
			$alert=1;
			break;
	   }
	}
		echo $time." time";
		//exit;
		if(!$alert && $time>1740 && $rows[0]['speedKPH']>0) { //greater than 29 mins
		//if(1){
			Yii::app()->db_gts->createCommand("update EventData set inputMask=1 where accountID='medcarehospital'	and deviceID='".$device."' and timestamp=".$rows[0]['timestamp'])->query();
			$addrArr = Library::getGPBYLATLNGDetailsCloud($rows[0]['latitude'] . "," . $rows[0]['longitude']);
			$msg=$device." is at ".$addrArr['address']." moving continuously since 30mins";
			Library::sendSingleSms(array('to' => '9866235548', 'message' => $msg));

			$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);   
		}
	}

		//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
		//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"test :".($time/60),$headers);
	}

	public function actionMedicareMoveSingleVehicle() {
		//http://egcrm.cloudapp.net/operations/index.php/cron/MedicareMove
	//40 speed
	//$currenttime=1477973329;//time()+600;
	$currenttime=time()+600;
	$checkTime=$currenttime-2700; //40min
    $fromTime= $currenttime-5400; //90min
	echo "CT".$currenttime." FT ".$fromTime." TT ".$checkTime;//exit;
	$rows=Yii::app()->db_gts->createCommand("select inputMask,distanceKM,latitude,longitude,speedKPH,timestamp from EventData where accountID='medcarehospital' and deviceID='AP15AM2322' and timestamp>".$fromTime."  order by timestamp desc")->queryAll();
	$time=0;
	$alert=0;
	echo '<pre>';print_r($rows);//exit;
	foreach($rows as $k=>$row){

	   if($row['timestamp']>=$checkTime){
		   echo $row['timestamp']."<br/>";
		   if(($row['speedKPH']!=0)){
		   //if(($rows[$k]['speedKPH']!=0) || ($rows[$k+1]['speedKPH']!="")){
			//if(($rows[$k]['speedKPH']!=0 && $rows[$k+1]['timestamp']!="")){
            //if(isset($rows[$k+1]['timestamp'])){ 
			   $time+=$rows[$k]['timestamp']-$rows[$k+1]['timestamp'];
			   echo "Time : ".$rows[$k]['timestamp']."-".$rows[$k+1]['timestamp']."=".$time."<br/>";
		   }
	   }

	   if($row['inputMask']==1){
			$alert=1;
			break;
	   }
	}
		echo $time." time";
		//exit;
		if(!$alert && $time>1740 && $rows[0]['speedKPH']>0) { //greater than 29 mins
		//if(1){
			Yii::app()->db_gts->createCommand("update EventData set inputMask=1 where accountID='medcarehospital'	and deviceID='AP15AM2322' and timestamp=".$rows[0]['timestamp'])->query();
			$addrArr = Library::getGPBYLATLNGDetailsCloud($rows[0]['latitude'] . "," . $rows[0]['longitude']);
			$msg="AP15AM2322 is at ".$addrArr['address']." moving continuously since 30mins";
			Library::sendSingleSms(array('to' => '9866235548', 'message' => $msg));

			$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),$msg,$headers);   
		}

		//$headers .= 'From: <info@easygaadi.com>' . "\r\n";
		//mail("suresh@easygaadi.com",date('Y-m-d_h:i:sa'),"test :".($time/60),$headers);
	}

	public function actionAlphores() {
        //$this->progressRouteAlert();
		//http://egcrm.cloudapp.net/operations/index.php/cron/Alphores
        $row = Yii::app()->db_gts->createCommand("select lastIgnitionOnTime as stoptime,lastIgnitionOffTime as stop,lastValidLatitude,lastValidLongitude from Device where accountID='alphores' and deviceID='TS02UA0581' and lastValidSpeedKPH>0")->queryRow();
        // and lastValidSpeedKPH>0
        
        /*$points[0] = array('lat' => 18.43069, 'lng' => 79.12124, 'addr' => 'Aphores Junior College,Bhagathnagar','mob'=>'9246934447');
        $points[1] = array('lat' => 18.45072, 'lng' => 79.12296, 'addr' => 'Aphores E Techno College,Vavilalapally','mob'=>'9885938405');
        $points[2] = array('lat' => 18.45220, 'lng' => 79.12091, 'addr' => 'Aphores Girls Hostel,Vavilalapally','mob'=>'9246934453');
        $points[3] = array('lat' => 18.47220, 'lng' => 79.10517, 'addr' => 'Aphores High School,Rekurthi','mob'=>'9985566025');
        $points[4] = array('lat' => 18.47739, 'lng' => 79.10309, 'addr' => 'Aphores Boys Junior College,Kothapally','mob'=>'9848869432');
        //$points[5] = array('lat' => 18.48131, 'lng' => 79.10473, 'addr' => 'Alphores E Techno School,Suryanagar','mob'=>'');
		*/
	$points[0] = array('lat' => 18.430875, 'lng' => 79.1252067, 'addr' => 'Alphores Junior College Boys,Mukarampura','mob'=>'9246734447');
	$points[1] = array('lat' => 18.43048, 'lng' => 79.12117, 'addr' => 'Alphores Junior College Girls,Bhagathnagar','mob'=>'9246934447');
	$points[2] = array('lat' => 18.45208, 'lng' => 79.12091, 'addr' => 'Alphores High School,Kothapally','mob'=>'9246934451');
	$points[3] = array('lat' => 18.45217, 'lng' => 79.12099, 'addr' => 'Alphores Boys Junior College,Kothapally','mob'=>'9848869432');
	$points[4] = array('lat' => 18.45236, 'lng' => 79.12111, 'addr' => 'Alphores Girls Junior College,Rekurthy','mob'=>'9985566025');
	$points[5] = array('lat' => 18.45068, 'lng' => 79.12296, 'addr' => 'Alphores Hostel s1','mob'=>'9866111100');
	$points[6] = array('lat' => 18.45068, 'lng' => 79.12296, 'addr' => 'Alphores Boys Junior College,Vavilalapally','mob'=>'9885938405');
	$points[7] = array('lat' => 18.45088, 'lng' => 79.12272, 'addr' => 'Alphores High School,Vavilalapally','mob'=>'9246934441');
	$points[8] = array('lat' => 18.45068, 'lng' => 79.12296, 'addr' => 'Alphores Junior College,Vavilalapall','mob'=>'9246934453');
	
	echo 'here<pre>';	
    //$rows=Yii::app()->db_gts->createCommand("select latitude as lastValidLatitude,longitude as lastValidLongitude from EventData where accountID='alphores' and deviceID='TS02UA0581' and speedKPH!=0 and timestamp>1479850000")->queryAll();
    //print_r($rows);exit;
    //foreach($rows as $row)	
    //{    
        if ($row['lastValidLatitude']!="" && $row['lastValidLongitude']!="") {
            foreach ($points as $k=>$point) {
                $km = number_format($this->distance($point['lat'], $point['lng'], $row['lastValidLatitude'], $row['lastValidLongitude'], 'K'), 3);
                echo $row['lastValidLatitude'].",".$row['lastValidLongitude']." ".$point['addr']." ".$km." <br/>"; 
                if (0.09 > $km) { //less than 90 meters
                    $timestamp=strtotime('now')+720;
					
                    if($timestamp>$row['stoptime'] || $k!=$row['stop']){
					echo "inside <br/>";
                    $msg="Vehicle is going to reach ".$point['addr']." shortly : ".date('d-M H:i',$timestamp);
                    $headers .= 'From: <info@easygaadi.com>' . "\r\n";
                    //mail("suresh@easygaadi.com","Alphores ".date('Y-m-d_h:i:sa'),$msg,$headers);
                    //Library::sendSingleSms(array('to' => $point['mob'], 'message' => $msg));
					Library::sendPushNotification(array('devices'=>array('APA91bG9RQAOg4gPlzsGhTlIY85loO7Oz5OeQIm-z1M0YF1cbAhs5wbzUlS3qlmflEL1EtSlf5u5X6oOexJb8bLMdvrcn3NiUNCpHC8wX4r_RE7R1zHbWs_gn9G_URcQ3epFTpr3BU-F'),'message'=>array('message'=>$msg,'type'=>'load')));

                       $updatetimestamp=$timestamp+300;
                    Yii::app()->db_gts->createCommand("update Device set lastIgnitionOnTime=".$updatetimestamp.",lastIgnitionOffTime=".$k." where accountID='alphores' and deviceID='TS02UA0581'")->queryRow();
					   echo $msg;
                       exit;
                    }
                }
            }
        //}
        }
		
    }

	public function progressRouteAlert(){
	$row = Yii::app()->db_gts->createCommand("select lastIgnitionOnTime as stoptime,lastIgnitionOffTime as stop,lastValidLatitude,lastValidLongitude from Device where accountID='progress' and deviceID='ROUTE07' and lastValidSpeedKPH>0")->queryRow();
        // and lastValidSpeedKPH>0
        
        
	$points[0] = array('lat' => 17.33652, 'lng' => 78.46877, 'addr' => 'Progress High School','mob'=>'');
	$points[1] = array('lat' => 17.27019, 'lng' => 78.47208, 'addr' => 'Pahadi Shareef','mob'=>'');
	$points[2] = array('lat' => 17.29612, 'lng' => 78.48508, 'addr' => 'Shaheen Colony, Venkatapuram','mob'=>'');
	$points[3] = array('lat' => 17.32457, 'lng' => 78.47976, 'addr' => 'Kummar Basthi, Chandrayangutta','mob'=>'');
	
	echo 'here<pre>';	
        if ($row['lastValidLatitude']!="" && $row['lastValidLongitude']!="") {
            foreach ($points as $k=>$point) {
                $km = number_format($this->distance($point['lat'], $point['lng'], $row['lastValidLatitude'], $row['lastValidLongitude'], 'K'), 3);
                echo $row['lastValidLatitude'].",".$row['lastValidLongitude']." ".$point['addr']." ".$km." <br/>"; 
                if (0.09 > $km) { //less than 90 meters
                    $timestamp=strtotime('now')+720;
					
                    if($timestamp>$row['stoptime'] || $k!=$row['stop']){
					echo "inside <br/>";
                    $msg="Vehicle is going to reach ".$point['addr']."  shortly : ".date('d-M H:i',$timestamp);
                    $headers .= 'From: <info@easygaadi.com>' . "\r\n";
                    //mail("suresh@easygaadi.com","Progress ".date('Y-m-d_h:i:sa'),$msg,$headers);
                    Library::sendPushNotification(array('devices'=>array('APA91bFouyWGqS-sUogBbyaycMY03pFXnm-UAq3UGnI2eG6UJIgtnJny8dXN6fhqZdoHUP9EWjnEMuiFJmIwBQpQLBaKqIVEV-IIbOc3YDdRphddeFlgrCThdlnj22lPR9B07RSnDPTF','APA91bEZWFpnIRZqcLIjIdERPWKtVL5wObqsPuy4aVcVKmybYwdrYzQBMVUXMJz82dC8HcF_iXOwP6jOWit60kKmm4TIr-8QfU7yDrgwXfnU5cJp9NGc8JwvhsEBDaJZg6-6gAGYdBNZ','APA91bHHN9WsLLQ3bJBX2m4s-R3ACGSts4AX7VsqEwA3QSwQUjELiF1gObuy5BulonanMMWmmJS0b7sRD61U7hSgrf1-IsnBVG4MFP-TN0J3v7kZrrgAkvWBS_-UFMCVY7UUHpPIDOnK','APA91bGLQu8zQBsbhP3IRG_KE2V4uLmPxm9IqWMa_kHCpM97nVWr_xE3ywfZEyR70PWqIISWKA2AKLMjUaz_YjbBIGwmo72o5B72hm6kSXUKb9ht8TicjnlsV5-3B_k3ZxLxhrnEwjWa','APA91bHcB6MtbwiYSKHAQbFvZzi2mdyAjhM9RebFw7kxLtltIvjpOJXKNiwxnYl986TwVegN0-zdQa2zQrUt3QU7XsTNH3ttlWNmKdQ_6EGBR9HniLJNgTjiPcHJjF2adqWazkHx_av-','APA91bHGwVjqw8aVECApmYdjeWonWN95-IrT4bBAJM5fA6XtHky07j0sbg0-QgOrJp8z6bem2vth_A7WuC_4UMhVG9RSj5Xd_0tNyjL6SYQSom6UNe93XJ2uCUzo0AmMvHzEJGTASePc','APA91bH2toxVenODr9UL6TwaehTZF6Cig7XxZHn3A3eEg0aln8nVz6aYMeVg_ULJq1zkoECosoelOoMg-__31zcHWEpEsMtJJn8YiyH82nLd7wjJ_4DPjpWiZAEQWv6g9uU7GXHoJvE8','APA91bEgwpXBT7wbFHsABe_XFcbMND95bOqJbDxPrI45mePXQRZFPbmGpyuGHAmO1-V9MeFb5m9GkCkL1xFUsnslCM7djN9W46HFfdkDDbgHa6wjbuy9rbQBXPis5M04cyQzlOmEOQP1','APA91bGIadjuhVRo7hQ32KiDuaVc02ULCx62nHvEioA-mLg1Z3s993_NIKAAMpKSPGDiNkgpzEmF3lbxkFPPQD9fQmInQ2xhJLnoRSm8riIe9w3h-C8rH_RqOZ8V3lPmOjzAhHC6PpFu','APA91bG9RQAOg4gPlzsGhTlIY85loO7Oz5OeQIm-z1M0YF1cbAhs5wbzUlS3qlmflEL1EtSlf5u5X6oOexJb8bLMdvrcn3NiUNCpHC8wX4r_RE7R1zHbWs_gn9G_URcQ3epFTpr3BU-F'),'message'=>array('message'=>$msg,'type'=>'load')));
                    $updatetimestamp=$timestamp+300;
                    Yii::app()->db_gts->createCommand("update Device set lastIgnitionOnTime=".$updatetimestamp.",lastIgnitionOffTime=".$k." where accountID='progress' and deviceID='ROUTE07'")->queryRow();
					   echo $msg;
                       //exit;
					   break;
                    }
                }
            }
        }
	}

	public function actionRouteFence(){
        //http://egcrm.cloudapp.net/operations/index.php/cron/RouteFence
        $group[10]='7337359072';//Suman N
        $group[9]='7032978803';//santosh/charan
        //$group[6]='7331135221';//suman
        $group[8]='8978938888';//ranga rao
        $group[11]='7331135221';//Bhaskar
        
        $timestampNow=strtotime('now')+720;
        $waitTime=$timestampNow+3600;
        $accountID='venkatreddy';
        $rows = Yii::app()->db_gts->createCommand("select d.groupID,d.deviceID,d.lastValidLatitude,d.lastValidLongitude,d.dataKey from Device d where d.lastValidSpeedKPH>0 and  d.lastOutputState<".$timestampNow." and  d.accountID='".$accountID."' and d.lastInputState!=0")->queryAll();
        //d.lastValidSpeedKPH>0 and  and d.lastOutputState<".$timestampNow
        foreach($rows as $row){
            if($row['dataKey']!=""){
                //print($row['dataKey']);
                $data=json_decode($row['dataKey']);
                //echo '<pre>';print_r($data);exit;
                $vertices_x=$data->lng;//longitude
                $vertices_y=$data->lat;//latitude
                $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array

				$lastValidLongitude=$row['lastValidLongitude'];
				$lastValidLatitude=$row['lastValidLatitude'];
				
				
				

				//$lastValidLatitude='17.318218';
				//$lastValidLongitude='77.508009';
                $longitude_x = $lastValidLongitude;  // x-coordinate of the point to test
                $latitude_y = $lastValidLatitude;    // y-coordinate of the point to test
                echo $longitude_x." ".$latitude_y."<br/>";
                echo '<pre>';
                print_r($vertices_x);
                print_r($vertices_y);
				//exit;

                if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                  echo "Is in polygon!";
                } else{    
                    echo "<b>Is not in polygon</b><br/>";
                    //$loc=Library::getGPBYLATLNGDetailsGoogle($lastValidLatitude.",".$lastValidLongitude);
                    $addrArr['address']=substr(str_replace(', ',',',$loc['address']),0,-13);
                    if($addrArr['address']==""){
                        $addrArr=Library::getGPBYLATLNGDetailsCloud($lastValidLatitude.",".$lastValidLongitude);
                    }
                    GpsDevice::model()->updateAll(array("lastOutputState"=>$waitTime),"deviceID='".$row['deviceID']."' and  accountID='".$accountID."'");
                    $msg="Truck No ".$row['deviceID']." has deviated near ".$addrArr['address']." at ".date('Y-m-d_h:i:sa');
                    $mobile=$group[$row['groupID']];
                    $smsTo=$mobile!=""?$mobile:"7032978803";
                    Library::sendSingleSms(array('to'=>$smsTo,'message'=>$msg));
                   
                    $headers .= 'From: <info@easygaadi.com>' . "\r\n";
                    mail("suresh@easygaadi.com,jvlogistics2346hyd@gmail.com,krish9067@gmail.com,executive.easygaadi@gmail.com","JV Route Fence".date('Y-m-d_h:i:sa'),$msg." ".$lastValidLatitude.",".$lastValidLongitude,$headers);
                }
            }
        }
		echo "at last";
    }

	public function actionemailDailySummary() {
    //exit("today");
	//http://egcrm.cloudapp.net/operations/index.php/cron/emailDailySummary
	$sqlAccount="select accountID,contactEmail from Account where contactEmail!='' and emailDailSummaryReport=1 and isActive=1";
    //exit($sqlAccount);
    $accountRows = Yii::app()->db_gts->createCommand($sqlAccount)->queryAll();
    $day=date('Y-m-d', strtotime(' -1 day'));
    $from=$day." 00:00";
    $to=$day.' 23:55';
        foreach($accountRows as $accountRow){

            $sqlEventData='select deviceID,sum(distanceKM) as total from EventData where (timestamp <= unix_timestamp("' . $to . '") AND timestamp >=unix_timestamp("' . $from . '")) and accountID="'.$accountRow['accountID'].'" group by deviceID order by deviceID asc';
            $eventRows = Yii::app()->db_gts->createCommand($sqlEventData)->queryAll();
            $data=array();
            foreach($eventRows as $eventRow){
                            $data[]=array("deviceID"=>$eventRow['deviceID'],"km"=>round($eventRow['total'], 2));
            }
            if(sizeof($data)){
                    $this->sendDailyMail(array('day'=>$day,'email'=>$accountRow['contactEmail'],'data'=>$data));
            }
        }
    }

    public function sendDailyMail($data){
        //echo '<pre>';print_r($data);echo '</pre>';
        //$to = 'sureshbabu.kokkonda@gmail.com';
        $to=$data['email'];
        $subject = "EasyGaadi Vehicle Daily Transit Report : ".$data['day'];
        $from = 'info@easygaadi.com';
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Create email headers
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        $str="";
        $str="<html><body><table><tr><td>Vehicle No</td><td>KM</td></tr>";
        foreach($data['data'] as $k=>$row){
            $str.="<tr><td>".$row['deviceID']."</td><td>".$row['km']."</td></tr>";
        }
        $str.="</table></body></html>";
        // Sending email
        if(mail($to, $subject, $str, $headers)){
            echo 'Your mail has been sent successfully.';
        } else{
            echo 'Unable to send email. Please try again.';
        }
    }

	public function actionrouteNotification() {
        //http://egcrm.cloudapp.net/operations/index.php/cron/routeNotification
		//accountID='aadisakthitrans' and
        $accRows = Yii::app()->db_gts->createCommand("select accountID,contactEmail,routeNotificationCount from Account where  isActive=1 and routeNotificationCount!=0")->queryAll();

        $slot[1] = array(6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
        $slot[2] = array(6, 8, 10, 12, 14, 16, 18, 20, 22);
        $slot[3] = array(6, 9, 12, 15, 18, 21);
        $slot[4] = array(6, 10, 14, 18, 22);
        $slot[5] = array(6, 11, 16, 21);
        $slot[6] = array(6, 12, 18);
        $hour = (int) date('H');//12;
        
        //echo $hour.'<pre>';print_r($slot);print_r($accRows);
        foreach ($accRows as $accRow) {
            if (in_array($hour, $slot[$accRow['routeNotificationCount']])) {
                $this->processDevice($accRow);
            }
        }
    }

    public function processDevice($data) {
       
        if ($data['contactEmail'] != "") {
            //$deviceSql="select from Device where isActive=1";
            //Yii::app()->db_gts->createCommand()->queryAll();
        }

        //$pushSql = "select distinct device_id from {{gps_login_device}}  where duplicate=0 and  lower(username) like '" . strtolower($data['accountID']) . "'";

		$pushSql = "select distinct device_id from {{gps_login_device}}  where lower(username) like '" . strtolower($data['accountID']) . "'";

        //echo $pushSql;
        $pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
        $devices = array();
        foreach ($pushRows as $k => $row) {
            $devices[] = $row['device_id'];
        }
        //print_r($devices);
        //$devices[]='APA91bHYBoP1ZXqKLAyttETCYU9BLtEa1rp8QMlJyJoxCUaekWwIovihDVTggScWgEZB_KaOgyfevAPxkVDFPdqudOlMhu81roNIauoVNIHtm4rhjRHxjVm4TtggW86MdPqb-rn7Pf7D';
		if(sizeof($devices)){
			$push=array('devices' => $devices, 'message' => array('message' => "Track Vehicles!", 'type' => 'route'));
			$result = Library::sendPushNotification($push);
			//Gpslogindevice::updateDuplicates($result);
			Gpslogindevice::updateDuplicateDevices($devices,$result);

			$headers = 'From: <info@easygaadi.com>' . "\r\n";
			//mail("suresh@easygaadi.com",'Route '.date('Y-m-d_h:i:sa'),$data['accountID']."=".implode(",",$devices),$headers);
			//mail("suresh@easygaadi.com",'Route '.date('Y-m-d_h:i:sa'),$data['accountID'].":".implode(",",$devices)."-".$result,$headers);
			echo $data['accountID'].'<pre>';print_r($push);
			echo $result;
		}
        //exit;
    }

	public function actiongpsTripDestAlertTemp() {
        /*$destLat=18.0056228;
        $destLng=79.5572463; 
        $lastValidLatitude=17.8431791;//20.307198333333332; 
        $lastValidLongitude=79.3943308;//79.022655;
        $km=number_format($this->distance($destLat,$destLng, $lastValidLatitude, $lastValidLongitude, 'K'), 2);
        exit("value of ".$km);*/
		//http://egcrm.cloudapp.net/operations/index.php/cron/gpsTripDestAlert
		$stopDays=3*86400; //will ignore 3 days old trip which is not stopped
		
		$tripRows=Yii::app()->db_gts->createCommand("select id,deviceID,destLat,destLng,accountID,destination from Trip where endPointTime=0 and startPointTime+".$stopDays.">".strtotime('now'))->queryAll();
		//echo '<pre>';print_r($tripRows);
		foreach($tripRows as $k=>$tripRow){
			if($tripRow['destLat']==0 || $tripRow['destLng']==0 ){
				$gDetails2 = Library::getGPDetails($tripRow['destination']);
				$tripRow['destLat'] = $gDetails2['lat'];
				$tripRow['destLng'] = $gDetails2['lng'];
				GpsTrip::model()->updateAll(array('destLat'=>$tripRow['destLat'],'destLng'=>$tripRow['destLng']),'id='.$tripRow['id']);
			}

			$deviceRow=Yii::app()->db_gts->createCommand("select lastValidLatitude,lastValidLongitude from Device where accountID='".$tripRow['accountID']."'  and deviceID='".$tripRow['deviceID']."'  and lastValidSpeedKPH>5 and lastGPSTimestamp+3600>".strtotime('now'))->queryRow();
			//print_r($deviceRow);
			if(sizeof($deviceRow)){
				$km=$this->distance($tripRow['destLat'],$tripRow['destLng'], $deviceRow['lastValidLatitude'], $deviceRow['lastValidLongitude'], 'K');
				//echo $km."<br/>";	
				if($km<=10){
					$pushSql = "select distinct device_id from {{gps_login_device}}  where duplicate=0 and  lower(username) like '" . strtolower($tripRow['accountID']) . "'";
					//echo $pushSql;
					$devices = array();
					/*$pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
					
					foreach ($pushRows as $j => $row) {
						$devices[] = $row['device_id'];
					}*/
					$devices[]='APA91bHYBoP1ZXqKLAyttETCYU9BLtEa1rp8QMlJyJoxCUaekWwIovihDVTggScWgEZB_KaOgyfevAPxkVDFPdqudOlMhu81roNIauoVNIHtm4rhjRHxjVm4TtggW86MdPqb-rn7Pf7D';
					$msg=$tripRow['deviceID']." is ".number_format($km,2)." km's away from ".$tripRow['destination'].".";
					//$result = Library::sendPushNotification(array('devices' => $devices, 'message' => array($msg."stop trip to avoid notifications!", 'type' => 'general')));
					echo $result;
					/*$model=new Notification;
					$model->accountID=$tripRow['accountID'];
					$model->info=$msg;
					$model->save(false);*/

					echo '<pre>';print_r($tripRow);print_r(array('devices' => $devices, 'message' => array('message'=>$msg, 'type' => 'general')));echo '</pre>';
				}
			}

		}
    }


	public function actiongpsTripDestAlert() {
		/*$destLat=18.0056228;
		$destLng=79.5572463; 
		$lastValidLatitude=17.8431791;//20.307198333333332; 
		$lastValidLongitude=79.3943308;//79.022655;
		$km=number_format($this->distance($destLat,$destLng, $lastValidLatitude, $lastValidLongitude, 'K'), 2);
		exit("value of ".$km);*/
		//http://egcrm.cloudapp.net/operations/index.php/cron/gpsTripDestAlert
		$stopDays=3*86400; //will ignore 3 days old trip which is not stopped
		
		$tripRows=Yii::app()->db_gts->createCommand("select id,deviceID,destLat,destLng,accountID,destination from Trip where endPointTime=0 and startPointTime+".$stopDays.">".strtotime('now'))->queryAll();
		echo '<pre>';print_r($tripRows);echo "tripRows";
		$accInfo=array();
		foreach($tripRows as $k=>$tripRow){
			if($tripRow['destLat']==0 || $tripRow['destLng']==0 ){
				$gDetails2 = Library::getGPDetails($tripRow['destination']);
				$tripRow['destLat'] = $gDetails2['lat'];
				$tripRow['destLng'] = $gDetails2['lng'];
				GpsTrip::model()->updateAll(array('destLat'=>$tripRow['destLat'],'destLng'=>$tripRow['destLng']),'id='.$tripRow['id']);
			}

			$deviceRow=Yii::app()->db_gts->createCommand("select lastValidLatitude,lastValidLongitude from Device where accountID='".$tripRow['accountID']."'  and deviceID='".$tripRow['deviceID']."'  and lastValidSpeedKPH>5 and lastGPSTimestamp+3600>".strtotime('now'))->queryRow();
			print_r($deviceRow);
			if(sizeof($deviceRow)){
				$km=$this->distance($tripRow['destLat'],$tripRow['destLng'], $deviceRow['lastValidLatitude'], $deviceRow['lastValidLongitude'], 'K');
				echo $km."<br/>";	
				if($km<=10){
					$msg="";
					$msg=$tripRow['deviceID']." is ".number_format($km,2)." km's away from ".$tripRow['destination'].".";			
					$accInfo[strtolower($tripRow['accountID'])][]=$msg;
					$model=new Notification;
					$model->accountID=$tripRow['accountID'];
					$model->info=$msg;
					$model->visibility = 'Single';
					$model->save(false);
		
					//echo '<pre>';print_r($tripRow);print_r(array('devices' => $devices, 'message' => array($msg, 'type' => 'general')));echo '</pre>';
				}
			}

		}
		//echo '<pre>';print_r($accInfo);
		//exit;
		foreach($accInfo as $m=>$n){
			$pushSql = "select distinct device_id from {{gps_login_device}}  where lower(username) like '" . $m . "'";
			//echo $pushSql;
			$devices = array();
			$pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
			
			foreach ($pushRows as $j => $row) {
				$devices[] = $row['device_id'];
			}
			if(sizeof($devices)){
			$pushArray=array('devices' => $devices, 'message' => array('message' =>implode(",",$n), 'type' => 'general'));
			$result = Library::sendPushNotification($pushArray);
			
			$headers = 'From: <info@easygaadi.com>' . "\r\n";
			//mail("suresh@easygaadi.com",'Trip '.date('Y-m-d_h:i:sa'),$m."=".implode(",",$devices).':'.implode(",",$n),$headers);
			//mail("suresh@easygaadi.com",'Trip '.date('Y-m-d_h:i:sa'),implode(",",$devices)."-".$result,$headers);
			//Gpslogindevice::updateDuplicates($result);
			Gpslogindevice::updateDuplicateDevices($devices,$result);
			echo $result;
			echo '<pre>';print_r($pushArray);
			}
		}
		//exit("here");
	}

	public function getDateDiff($from, $to) {
        $date1 = date_create($from);
        $date2 = date_create($to);
        $differ = date_diff($date2, $date1);
        $diff = $differ->format("%R%a");
        return $diff;
    }

    public function actionrunDashboardCron() {
        /*
          http://egcrm.cloudapp.net/operations/index.php/cron/runDashboardCron
         */

        $sql = "select dm.*,d.lastOdometerKM from DeviceMaintenance dm,Device d where d.deviceID=dm.deviceID"; //have to check with Device table as well as it is active or not.
        $rows = Yii::app()->db_gts->createCommand($sql)->queryAll();
        $today = date('Y-m-d');
        $odo = array();
        $json = array();
        //echo '<pre>';print_r($rows);
        foreach ($rows as $row) {
            $odo[$row['deviceID']] = $row['lastOdometerKM'];
            //National Permit
            if ($row['nPExpire'] != "0000-00-00") {
                //$today
                
                $diff = $this->getDateDiff($row['nPExpire'], $today);
                //echo $diff." ".$row['deviceID'].'<br/>'; 
                if ($diff >= 0 && $diff < 6) { //5 day Alert
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " permit  due date " . $row['nPExpire'];
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " permit expired on " . $row['nPExpire'];
                    ;
                }
            }

            //Rc
            if ($row['rcExpire'] != "0000-00-00") {
                $diff = $this->getDateDiff($row['rcExpire'], $today);
                if ($diff >= 0 && $diff < 6) { //5 day Alert
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " permit  due date " . $row['rcExpire'];
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " permit expired on " . $row['rcExpire'];
                }
            }

            //Insurance
            if ($row['insuranceExpire'] != "0000-00-00") {
                $diff = $this->getDateDiff($row['insuranceExpire'], $today);
                if ($diff >= 0 && $diff < 6) { //5 day Alert
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " insurance due date " . $row['insuranceExpire'];
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " insurance expired on " . $row['insuranceExpire'];
                }
            }

            //Fitness
            if ($row['fitnessExpire'] != "0000-00-00") {
                $diff = $this->getDateDiff($row['fitnessExpire'], $today);
                if ($diff >= 0 && $diff < 6) { //5 day Alert
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " fitness due date " . $row['fitnessExpire'];
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " fitness expired on " . $row['fitnessExpire'];
                }
            }

            //Pollution
            if ($row['pollExpire'] != "0000-00-00") {
                $diff = $this->getDateDiff($row['pollExpire'], $today);
                if ($diff >= 0 && $diff < 6) { //5 day Alert
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " pollution due date " . $row['pollExpire'];
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " pollution expired on " . $row['pollExpire'];
                }
            }

            //servicing
            $currentKm = $row['lastServicingReading'] != 0 ? $row['lastOdometerKM'] - $row['lastServicingReading'] : 0;
            $check = 0;
            if ($row['nextServicingDate'] != '0000-00-00') {
                $diff = $this->getDateDiff($row['nextServicingDate'], $today);
                if ($diff >= 0 && $diff < 6) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " servicing due date " . $row['nextServicingDate'];
                    $check = 1;
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " servicing expired on " . $row['nextServicingDate'];
                    $check = 1;
                }
            }

            if (($row['lastServicingReading'] != 0) && ($row['servicingKm'] != 0) && !$check) {
                if ($currentKm < 100 && $currentKm > 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " about to cross servicing km limit";
                } else if ($currentKm < 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " crossed servicing km limit";
                }
            }


            //greasing
            $currentKm = $row['lastGreasingReading'] != 0 ? $row['lastOdometerKM'] - $row['lastGreasingReading'] : 0;
            $check = 0;
            if ($row['nextGreasingDate'] != '0000-00-00') {
                $diff = $this->getDateDiff($row['nextGreasingDate'], $today);
                if ($diff >= 0 && $diff < 6) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " greasing due date " . $row['nextGreasingDate'];
                    $check = 1;
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " greasing expired on " . $row['nextGreasingDate'];
                    $check = 1;
                }
            }

            if (($row['lastGreasingReading'] != 0) && ($row['greasingKm'] != 0) && !$check) {
                if ($currentKm < 100 && $currentKm > 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " about to cross greasing km limit";
                } else if ($currentKm < 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " crossed greasing km limit";
                }
            }

            //eoil
            $currentKm = $row['lasteOilReading'] != 0 ? $row['lastOdometerKM'] - $row['lasteOilReading'] : 0;
            $check = 0;
            if ($row['eOilExpiryDate'] != '0000-00-00') {
                $diff = $this->getDateDiff($row['eOilExpiryDate'], $today);
                if ($diff >= 0 && $diff < 6) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " engine oil due date " . $row['eOilExpiryDate'];
                    $check = 1;
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " engine oil expired on " . $row['eOilExpiryDate'];
                    $check = 1;
                }
            }

            if (($row['lasteOilReading'] != 0) && ($row['eOilKm'] != 0) && !$check) {
                if ($currentKm < 100 && $currentKm > 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " about to cross engline oil km limit";
                } else if ($currentKm < 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . " crossed engline oil km limit";
                }
            }
        }
        //Tyres
        $sql = "select dt.* from DeviceTyres dt where accountID='" . $accountID . "'"; //have to check with Device table as well as it is active or not.
        $rows = Yii::app()->db_gts->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            //tyres
            $currentKm = $row['installReading'] != 0 ? $odo[$row['deviceID']] - $row['installReading'] : 0;
            $check = 0;
            if ($row['expiryDate'] != '0000-00-00') {
                $diff = $this->getDateDiff($row['expiryDate'], $today);
                if ($diff >= 0 && $diff < 6) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . "-" . $row['level'] . '-' . $row['position'] . ' tyre replacement due on ' . $row['expiryDate'];
                    $check = 1;
                } else if ($diff < 0) { //expired
                    $json['data'][$row['accountID']][] = $row['deviceID'] . "-" . $row['level'] . '-' . $row['position'] . ' tyre replacement expired on ' . $row['expiryDate'];
                    $check = 1;
                }
            }

            if (($row['installReading'] != 0) && ($row['km'] != 0) && !$check) {
                if ($currentKm < 100 && $currentKm > 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . "-" . $row['level'] . '-' . $row['position'] . ' tyre about to cross limit ' . $row['km'] . 'km.';
                } else if ($currentKm < 0) {
                    $json['data'][$row['accountID']][] = $row['deviceID'] . "-" . $row['level'] . '-' . $row['position'] . ' tyre crossed limit ' . $row['km'] . 'km.';
                }
            }
        }

        echo '<pre>value of ';
        print_r($json['data']);
        foreach($json['data'] as $k=>$v){
            $message=implode(", ",$v);
            $pushSql = "select distinct device_id from {{gps_login_device}}  where lower(username) like '" . strtolower($k) . "'";
            //echo $pushSql;
            $pushRows = Yii::app()->db->createCommand($pushSql)->queryAll();
            $devices = array();
            foreach ($pushRows as $k => $row) {
                $devices[] = $row['device_id'];
            }
            //print_r($devices);
            //$devices[]='APA91bH9LGlycahkyyFsqWOWhdGzBqeO8pRvV2sb_YMsQxLwtxJ3ntUogGCFf4r1nB9gJGlen3WBdbdF9Iyj2jpT5rmWs3Wci5iQYjvKcNpDtRVn5h6ropCTebjrtieT-jen_j5BgaI4';
			if(sizeof($devices)){
				$pushArray=array('devices' => $devices, 'message' => array('message' =>$message, 'type' => 'dashboard'));
				$result = Library::sendPushNotification($pushArray);
				//Gpslogindevice::updateDuplicates($result);
				Gpslogindevice::updateDuplicateDevices($devices,$result);
			}
			echo $result;
			echo '<pre>';print_r($pushArray);
			$headers = 'From: <info@easygaadi.com>' . "\r\n";
			//mail("suresh@easygaadi.com",'Dashboard '.date('Y-m-d_h:i:sa'),implode(",",$devices)."-".$result."<br/>".$message,$headers);
        }
		Yii::app()->db->createCommand("delete from eg_notification where date(dateCreated)<'".date('Y-m-d', strtotime(' -3 day'))."'")->query();//delete notification of last 3 days
    }

	public function actionDeleteEventDataLimit____(){
		
		//date('Y-m-d H:i:s').strtotime(date('Y-m-d H:i:s'))
		//http://egcrm.cloudapp.net/operations/index.php/cron/DeleteEventDataLimit
		$daysOld=86400*31;
		$currentDate=strtotime(date('Y-m-d H:i:s')); 
		$execDate=$currentDate-$daysOld;
		$deleteETData=$currentDate-$daysOld-$daysOld;//2months after data will be delete in EventDataTemp
		//exit("value of ".$execDate." deleteETData ".$deleteETData);
		$limit="50000";
		if($execDate!=""){
			//echo "delete from EventData where creationTime<".$execDate." and deviceID!='' and accountID!='demo' limit  ".$limit;
			Yii::app()->db_gts->CreateCommand("delete from EventData where creationTime<".$execDate." and deviceID!='' and accountID!='demo' limit  ".$limit)->query();
			echo "Query Success";
		}else{
			echo "Query Failed";
		}
	}
}