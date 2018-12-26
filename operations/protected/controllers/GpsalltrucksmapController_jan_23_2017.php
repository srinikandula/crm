<?php
class GpsalltrucksmapController extends Controller
{
    public function actionIndex()
{
	//echo "value of ".time();
	//exit;
	$checkTime=time()-14400;
			//and d.lastGPSTimestamp>".$checkTime."
    /*$rows=Yii::app()->db_gts->createCommand('SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
    ON a.accountID = d.accountID;')->queryAll();//queryAll()
	$var=Yii::app()->db_gts->createCommand('SELECT * from Device;')->queryAll();*/
	
  
  //  $deviceid =Yii::app()->db_gts->createCommand("select accountID from device where accountID like '%".$truck."%';")->queryAll();//queryAll()

//  echo '<pre>';print_r($dd);exit;
    $truck = $_POST['truck'];
	//  echo '<pre>';print_r($truck);exit;
	//$truck_list = 'ka561597';
	$myArray = explode(',', $truck);
        //$myArray[1] = 'aa';
    //echo '<pre>';print_r($myArray);exit;
    //$curr_accID = $_POST['truck'];
	if($myArray[1]=="")
	{
		$myArray[1]='aa';
	}
    
    $truck_list = $_POST['truck_list'];
	// echo '<pre>';print_r($truck_list);exit;
     //$truck='ka561597';
// 	echo '<pre>';print_r($myArray[0]);exit;
    
	// echo '<pre>';print_r($rows);exit;
///$deviceid =Yii::app()->db_gts->createCommand("select accountID from device where accountID like '%".$truck."%';")->queryAll();//queryAll()
	//  echo '<pre>';print_r($deviceid);exit;
	  
	//  echo '<pre>';print_r($truck);exit;
	 if(($truck =="")&($truck_list =="")){

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel,ceil(d.lastValidSpeedKPH*1.852) as speed
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID and d.lastValidLatitude!='' and d.lastValidLongitude!='' and a.accountID!='santosh' and d.isActive=1 and d.lastGPSTimestamp>".$checkTime)->queryAll();//queryAll()
	}  
	
	else if(($truck !="")&($truck_list !="")){
    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel,ceil(d.lastValidSpeedKPH*1.852) as speed
    FROM Account as a
    JOIN Device as d                        
    ON  a.accountID!='santosh' and a.accountID = d.accountID  and d.isActive=1 and d.lastGPSTimestamp>".$checkTime." and (lastValidLatitude!='' and lastValidLongitude!='') AND (((d.deviceID like '%".$myArray[0]."%'  OR d.accountID like '%".$myArray[1]."%') and (d.truckTypeId = '$truck_list')) and (d.vehicleType='TK'));")->queryAll();//queryAll()
    }
	   
	 else  if(($truck !="")&($truck_list =="")){

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel,ceil(d.lastValidSpeedKPH*1.852) as speed
    FROM Account as a
    JOIN Device as d
     ON a.accountID!='santosh' and  a.accountID = d.accountID  and d.isActive=1 and d.lastGPSTimestamp>".$checkTime." and (lastValidLatitude!='' and lastValidLongitude!='') AND ((d.deviceID like '%".$myArray[0]."%'  OR d.accountID like '%".$myArray[1]."%') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	} else  if(($truck =="")&($truck_list !="")){

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel,ceil(d.lastValidSpeedKPH*1.852) as speed
    FROM Account as a
    JOIN Device as d
     ON a.accountID!='santosh' and  a.accountID = d.accountID  and d.isActive=1 and d.lastGPSTimestamp>".$checkTime." and (lastValidLatitude!='' and lastValidLongitude!='') AND ((d.truckTypeId = '$truck_list') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	}   
	//echo '<pre>';print_r($rows1);echo '</pre>';exit;
	//echo '<pre>';print_r($rows);echo '</pre>';
    //$rows2=Gpsdevice::model()->findAll(array('select' => 'accountID,deviceID', 'condition' => 'isActive=1'));
    //Yii::app()->db->enableProfiling=1;
	$this->render('index',array('rows1'=>$rows1,'url'=> $this->createUrl('index')));
}
}      
?>