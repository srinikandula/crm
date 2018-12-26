<?php
class GpsalltrucksmapController extends Controller
{
    public function actionIndex()
{
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

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID and lastValidLatitude!='' and lastValidLongitude!=''")->queryAll();//queryAll()
	}  
	
	else if(($truck !="")&($truck_list !="")){
    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d                        
    ON a.accountID = d.accountID and (lastValidLatitude!='' and lastValidLongitude!='') AND (((d.deviceID like '%".$myArray[0]."%'  OR d.accountID like '%".$myArray[1]."%') and (d.truckTypeId = '$truck_list')) and (d.vehicleType='TK'));")->queryAll();//queryAll()
    }
	   
	 else  if(($truck !="")&($truck_list =="")){

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID and (lastValidLatitude!='' and lastValidLongitude!='') AND ((d.deviceID like '%".$myArray[0]."%'  OR d.accountID like '%".$myArray[1]."%') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	} else  if(($truck =="")&($truck_list !="")){

    $rows1=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID and (lastValidLatitude!='' and lastValidLongitude!='') AND ((d.truckTypeId = '$truck_list') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	}   
	//echo '<pre>';print_r($rows);echo '</pre>';
    
    $this->render('index',array('rows1'=>$rows1,'url'=> $this->createUrl('index')));
}
}      
?>