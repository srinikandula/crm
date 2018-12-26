<?php
class GpsalltrucksmapController extends Controller
{
    public function actionIndex()
{
    $rows=Yii::app()->db_gts->createCommand('SELECT a.accountID, a.contactName,a.contactPhone, d.deviceID, d.lastValidLatitude, d.lastValidLongitude
FROM Account as a
JOIN Device as d
ON a.accountID = d.accountID;')->queryAll();    
    //echo '<pre>';print_r($rows);exit;
  
    $this->render('index',array('url'=> $this->createUrl('index')));
}
}      
?>