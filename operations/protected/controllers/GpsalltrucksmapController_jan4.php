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
	
  

//  echo '<pre>';print_r($dd);exit;
    $truck = $_POST['truck'];
	//$truck_list = 'ka561597';
    
    //$curr_accID = $_POST['truck'];
    
    $truck_list = $_POST['truck_list'];
	// echo '<pre>';print_r($truck_list);exit;
     //$truck='ka561597';
//	echo '<pre>';print_r($truck);exit
    
	// echo '<pre>';print_r($rows);exit;
	 if(($truck =="")&($truck_list =="")){

    $rows=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID and lastValidLatitude!='' and lastValidLongitude!=''")->queryAll();//queryAll()
	}  
	
	else if(($truck !="")&($truck_list !="")){
    $rows=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
    ON a.accountID = d.accountID AND (((d.deviceID like '$truck'  OR d.accountID like '$truck') and (d.truckTypeId = '$truck_list')) and (d.vehicleType='TK'));")->queryAll();//queryAll()
    }
	   
	 else  if(($truck !="")&($truck_list =="")){

    $rows=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID AND ((d.deviceID like '$truck'  OR d.accountID like '$truck') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	} else  if(($truck =="")&($truck_list !="")){

    $rows=Yii::app()->db_gts->createCommand("SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude,d.vehicleModel
    FROM Account as a
    JOIN Device as d
     ON a.accountID = d.accountID AND ((d.truckTypeId = '$truck_list') and (d.vehicleType='TK'));")->queryAll();//queryAll()
	}   
	//echo '<pre>';print_r($rows);echo '</pre>';
    
    $this->render('index',array('rows'=>$rows,'url'=> $this->createUrl('index')));
}
}      
?>