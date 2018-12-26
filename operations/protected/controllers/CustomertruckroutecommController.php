<?php
class CustomertruckroutecommController extends Controller 
{
    /*public function accessRules() {
        return $this->addActions(array('Autosuggest'));
    }*/
    

    
    /*public function actionIndex()
    {
        $model = new Commission();
        if (Yii::app()->request->isPostRequest)
        {
            $model->unSetAttributes();
            //$model->CONFIG_WEBSITE_DEFAULT_TIME_ZONE='kolkata';
            //$model->attributes=$_POST['WebsiteForm'];
            
            foreach($_POST['WebsiteForm'] as $key=>$config):
            $model->$key=$config;
            endforeach;
            //$model->_dynamicFields=$_POST['WebsiteForm'];

            if($model->validate())
            {
                foreach($_POST['WebsiteForm'] as $key=>$config):
                Configuration::model()->updateAll(array('value'=>$config),"`key`='".$key."'");
                endforeach;

				ConfigurationGroup::model()->updateAll(array('date_modified'=> new CDbExpression('NOW()')),"`type`='CONFIG' and `code`='CONFIG'");

                Yii::app()->user->setFlash('success',Yii::t('common','message_modify_success'));
                $this->redirect('website');
            }
        }
        
        $this->render('index', array('model'=>$model));
    }*/
    
    public function actionIndex(){
        $criteria=new CDbCriteria;
        $criteria->select="t.fullname,t.type,t.mobile,tr.truck_reg_no";
        $criteria->join="inner join {{truck}} tr on t.id_customer=tr.id_customer where t.approved=1";
	
        $records['customer']=  Customer::model()->findAll($criteria);
        //$criteria1=new CDbCriteria;
        //$criteria1->select="distinct(source_city)";
        //$criteria1->join="inner join {{truck}} tr on t.id_customer=tr.id_customer where t.approved=1";
        $source=Truckrouteprice::model()->findAll(array('select'=>'distinct(source_city)','condition'=>'route_allowed=1'));
        $destination=Truckrouteprice::model()->findAll(array('select'=>'distinct(destination_city)','condition'=>'route_allowed=1'));
        //echo '<pre>';print_r($source);echo '</pre>';
        //exit;
        
        
        $this->render('index', array('destination'=>$destination,'source'=>$source,'records'=>$records,'model'=>  Commission::model()->findAll('type="PERSON_TRUCK_ROUTE"')));
    }
    
    public function actionUpdate(){
        if (Yii::app()->request->isPostRequest)
        {
            //echo '<pre>';print_r($_POST);echo '</pre>';//exit;
            Commission::model()->deleteAll('type="PERSON_TRUCK_ROUTE"');
            $cTypes=  array_flip(Library::getCustomerTypes());
            Truckrouteprice::model()->updateAll(array('commission_type'=>'','commission'=>''));
            foreach($_POST['Commission']['PersonTruckRoute'] as $key=>$row){
            //echo '<pre>';print_r($row);echo '</pre>';
                if($row['title']=="" || $row['commission']=="" || $row['source_city']=="" || $row['destination_city']==""){
                    continue;
                }
                $exp=explode(",",$row['title']);
                //echo '<pre>';print_r($exp);echo '</pre>';
                //$custObj=Customer::model()->find('type="'.$cTypes[$exp[1]].'" and mobile="'.$exp[2].'" and fullname like "'.$exp[0].'"');
                //echo "<br/>value of ".$custObj->id_customer;

                    $com=new Commission;
                    $com->title=$row['title'];
                    $com->type='PERSON_TRUCK_ROUTE';
                    $com->source_city=$row['source_city'];
                    $com->destination_city=$row['destination_city'];
                    $com->commission_type=$row['commission_type'];
                    $com->commission=$row['commission'];
                    $com->save(false);
                //echo '<pre>';print_r($custObj);echo '</pre>';
                    $custObj=Yii::app()->db->createCommand('select trp.id_truck_route_price from {{customer}} c,{{truck}} t,{{truck_route_price}} trp where c.id_customer=t.id_customer and t.truck_reg_no="'.$exp[3].'" and c.mobile="'.$exp[2].'" and c.fullname="'.$exp[0].'" and t.id_truck=trp.id_truck and c.type="'.$cTypes[$exp[1]].'" and trp.source_city like "'.$row['source_city'].'" and trp.destination_city like "'.$row['destination_city'].'"')->queryAll();
                    foreach ($custObj as $key => $value) {
                        if($value['id_truck_route_price']==""){                        continue;}
                        Truckrouteprice::model()->updateAll(array('commission_type'=>$row['commission_type'],'commission'=>$row['commission']),'id_truck_route_price="'.$value['id_truck_route_price'].'"');
                    }
                }
                //update point source and destination as it was made null before.
                
                $comRouteObj=Commission::model()->findAll('type="ROUTE"');
                foreach($comRouteObj as $comRouteObjRow){
                    if($comRouteObjRow->source_city!="" && $comRouteObjRow->destination_city!=""){
                        Truckrouteprice::model()->updateAll(array('commission_type'=>$comObjRow->commission_type,'commission'=>$comObjRow->commission),'commission_type="" and commission="" and source_city like "'.$comObjRow->source_city.'" and  destination_city like "'.$comObjRow->destination_city.'"');
                    }
                }
                
                $comObj=Commission::model()->findAll('type="POINT"');
                foreach($comObj as $comObjRow){
                    if($comObjRow->source_city!="" && $comObjRow->destination_city==""){
                        Truckrouteprice::model()->updateAll(array('commission_type'=>$comObjRow->commission_type,'commission'=>$comObjRow->commission),'commission_type="" and commission="" and source_city like "'.$comObjRow->source_city.'"');
                    }
                    if($comObjRow->destination_city!="" && $comObjRow->source_city==""){
                        Truckrouteprice::model()->updateAll(array('commission_type'=>$comObjRow->commission_type,'commission'=>$comObjRow->commission),'commission_type="" and commission="" and destination_city like "'.$comObjRow->destination_city.'"');
                    }
                }
            //exit;
        }
        $this->redirect('index');
    }
}