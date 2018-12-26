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
	     //exit;
		 /*
          http://egcrm.cloudapp.net/operations/index.php/cron/GPSLocationSmsAlerts
         deviceID should be Truck Reg No,contactPhone will receive sms
        */
        /*$addrArr=Library::getGPBYLATLNGDetails('17.3700,78.4800');
        echo substr(str_replace(' ','', $addrArr['address']),6,-6);
        echo '<pre>';print_r($addrArr);exit;
		Library::sendSingleSms(array('to'=>'9848750094','message'=>'langana 500033|device7:oli - Tandur Road,Gowthapur,Tandur,Telangana 501141|device8:t Rd,Mega Hills,Madhapur,Hyderabad,Telangana 500081|device9: Number 72,Kavu'));
		exit;*/
        if (1) {
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

                    //eg gps server
					$addrArr=Library::getGPBYLATLNGDetailsCloud($devRow['lastValidLatitude'].",".$devRow['lastValidLongitude']);
                    $addr=trim($addrArr['address']);
					
					//start removing district,unname road from addr
					//$addr=str_replace("Unnamed Road,","",$addr);
					$addr=str_replace(" district","",$addr);
					//end removing 

					//google maps
					//$addrArr=Library::getGPBYLATLNGDetails($devRow['lastValidLatitude'].",".$devRow['lastValidLongitude']);
					//$addr=substr(str_replace(', ',',',$addrArr['address']),0,-13);
					
					if($addr!=""){
						$msg.=$prefix.$devRow['deviceID'].":".$addr;
					}

                    $prefix="|";
                }
				$mailContent.=$accRow['contactPhone'].":".$msg."\r\n";
                $this->sendSms(trim($accRow['contactPhone']),$msg);
                //echo $msg." <br/>";
            }
			$headers .= 'From: <info@easygaadi.com>' . "\r\n";
			mail("arjun@easygaadi.com,suresh@easygaadi.com,krish9067@gmail.com",date('Y-m-d_h:i:sa'),$mailContent,$headers);
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
			$standardSmsLen=156;
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

	public function actionMoveDataToTemp(){
            //date('Y-m-d H:i:s').strtotime(date('Y-m-d H:i:s'))
			//http://egcrm.cloudapp.net/operations/index.php/cron/MoveDataToTemp
            $daysOld=86400*31;
			$currentDate=strtotime(date('Y-m-d H:i:s')); 
            $execDate=$currentDate-$daysOld;
			$deleteETData=$currentDate-$daysOld-$daysOld;//2months after data will be delete in EventDataTemp
            //exit("value of ".$execDate." deleteETData ".$deleteETData);
            if($execDate!=""){

			Yii::app()->db_gts->CreateCommand("delete from EventData where timestamp=0")->query();

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
}