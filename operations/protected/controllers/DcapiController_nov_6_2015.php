<?php

class DcapiController extends Controller {

    public $layout = "//layouts/guest";
    public $limit=50;

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

	public function actionForgotpassword() {
		
		$json=array("status"=>0);
        $username=$_POST['username'];
		if (Yii::app()->request->isPostRequest && $username!="") {
		//if(1){$username="suresh@easygaadi.com";	
	    $user=Admin::model()->find('id_admin_role="11" and status=1 and LOWER(email)=?',array(strtolower($username)));
        if($user===null){
			$json['status']=0;
		}else if($user->id_admin){
                $json['status']=1;
                $password=Library::randomPassword();
                $user->model()->updateAll(array('password'=>Admin::hashPassword($password)),'id_admin='.$user->id_admin);
                Library::sendSingleSms(array('to'=>$user->phone,'message'=>'password:'.$password));
         }else{
                $json['status']=0;
         }
	}
        //echo '<pre>';print_r($json);echo '</pre>';
        echo CJSON::encode($json);
		Yii::app()->end();
    }
 
    public function actionLogin() {
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest && $_POST['username']!="" && $_POST['password']!="") {
            $username=$_POST['username'];
            $password=$_POST['password'];
            $user=Admin::model()->find('id_admin_role="11" and status=1 and LOWER(email)=?',array(strtolower($username)));
            if($user===null){
				$json['status']=0;
			}else if(!$user->validatePassword($password)){
                $json['status']=0;
            }else{
                $json['status']=1;
                $json['data']=array('first_name'=>$user->first_name,'last_name'=>$user->last_name,'phone'=>$user->phone,'email'=>$user->email,'city'=>$user->city,'state'=>$user->state);
                Yii::app()->db->createCommand('UPDATE {{admin}} SET last_visit_date = present_visit_date,present_visit_date = NOW( ) WHERE id_admin ="'.$user->id_admin.'"')->query();
            }
	}
        //echo '<pre>';print_r($json);echo '</pre>';
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionGetLeads(){
        $json=array("status"=>0);
        $id=(int)$_POST['id'];
        $offset=(int)$_POST['offset'];
        if (Yii::app()->request->isPostRequest) {
            
            $sql="select c.*,cl.lead_status,cl.lead_source,clsh.message from {{customer}} c,{{customer_lead}} cl,{{customer_access_permission}} cap,{{customer_lead_status_history}} clsh where cap.id_admin='".$id."'  and cap.id_customer=c.id_customer and cap.id_customer=cl.id_customer and c.islead=1 and clsh.id_customer=cap.id_customer and status='Document Collection'";
            $count=Yii::app("select count(*) from (".$sql.") as tab")->createCommand()->queryScalar();
            $rows = Yii::app()->db->CreateCommand($sql." order by date_created desc limit ".$this->limit." offset ".$offset)->queryAll();
                $json['status']=1;
                $json['data']=$rows;
                $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function getTruckTypes(){
        $rows=Yii::app()->db->createCommand('select * from {{truck_type}} where status=1')->queryAll();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }
    
    public function getGoodsType(){
        $rows=Yii::app()->db->createCommand('select * from {{goods_type}} where status=1')->queryAll();
        echo CJSON::encode($rows);
        Yii::app()->end();
    }
    
    public function actionGetLead(){
        $json=array("status"=>0);
        $id=(int)$_POST['id'];
        if (Yii::app()->request->isPostRequest) {
             
            $rows['customer']=Yii::app()->db->createCommand("select c.*,cl.lead_status,cl.lead_source from {{customer}} c,{{customer_lead}} cl where c.id_customer=cl.id_customer and c.id_customer='".$id."'")->queryRow();

            $rows['accessPermissions']=Yii::app()->db->createCommand("select a.first_name,a.last_name,ar.role from {{customer_access_permission}} cap,{{admin}} a,{{admin_role}} ar where  cap.id_customer='".$id."' and cap.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role")->queryAll();

            $rows['docs']=Yii::app()->db->createCommand("select * from {{customer_docs}} where id_customer='".$id."'")->queryAll();

            $rows['drivers']=Yii::app()->db->createCommand("select d.* from {{driver}} d,{{customer_driver_current}} cdc where cdc.id_customer='".$id."' and cdc.id_driver=d.id_driver")->queryAll();

            $rows['leadStatus']=Yii::app()->db->createCommand("select clsh.*,a.first_name,a.last_name,ar.role from {{customer_lead_status_history}} clsh,{{admin}} a,{{admin_role}} ar where  clsh.id_customer='".$id."' and clsh.id_admin=a.id_admin and a.id_admin_role=ar.id_admin_role order by date_created desc")->queryAll();

            $rows['optDest']=Yii::app()->db->createCommand("select * from {{customer_operating_destinations}} where id_customer='".$id."'")->queryAll();

            $rows['truckTypes']=Yii::app()->db->createCommand("select * from {{customer_vechile_types}} where id_customer='".$id."'")->queryAll();

            $rows['trucks']=Yii::app()->db->createCommand("select * from {{truck}} where id_customer='".$id."'")->queryAll();
            $json['status']=1;
            $json['data']=$rows;
            $json['count']=$count;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    
    
    public function actionGetInBoundUser(){
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest) {
                $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{customer}} c,{{customer_access_permission}} cl where c.id_customer=cl.id_customer and c.islead=1 and cl.id_admin=a.id_admin and cl.status=1 and c.date_created>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and a.id_admin_role=9 order by rows asc")->queryRow();
                $json['status']=1;
                $json['id_admin']=$row['id_admin'];
                
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}