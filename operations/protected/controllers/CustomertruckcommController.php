<?php
class CustomertruckcommController extends Controller 
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
        //echo '<pre>';print_r($records);echo '</pre>';
        //exit;
        $this->render('index', array('records'=>$records,'model'=>  Commission::model()->findAll('type="PERSON_TRUCK"')));
    }
    
    public function actionUpdate(){
        if (Yii::app()->request->isPostRequest)
        {
            //echo '<pre>';print_r($_POST);echo '</pre>';exit;
            Commission::model()->deleteAll('type="PERSON_TRUCK"');
            $cTypes=  array_flip(Library::getCustomerTypes());
            Truck::model()->updateAll(array('commission_type'=>'','commission'=>''));
            foreach($_POST['Commission']['Person'] as $key=>$row){
            //echo '<pre>';print_r($row);echo '</pre>';
                if($row['title']=="" || $row['commission']==""){
                    continue;
                }
                $exp=explode(",",$row['title']);
                //echo '<pre>';print_r($exp);echo '</pre>';
                //$custObj=Customer::model()->find('type="'.$cTypes[$exp[1]].'" and mobile="'.$exp[2].'" and fullname like "'.$exp[0].'"');
                //echo "<br/>value of ".$custObj->id_customer;
                $custObj=Yii::app()->db->createCommand('select c.id_customer,t.id_truck from {{customer}} c,{{truck}} t where c.id_customer=t.id_customer and t.truck_reg_no="'.$exp[3].'" and c.mobile="'.$exp[2].'" and c.fullname="'.$exp[0].'" and c.type="'.$cTypes[$exp[1]].'"')->queryRow();
                
                if($custObj['id_truck']){
                    $com=new Commission;
                    $com->title=$row['title'];
                    $com->type='PERSON_TRUCK';
                    $com->commission_type=$row['commission_type'];
                    $com->commission=$row['commission'];
                    $com->save(false);
                    Truck::model()->updateAll(array('commission_type'=>$row['commission_type'],'commission'=>$row['commission']),'id_truck='.$custObj['id_truck']);
                    
                }
            }
        }
        $this->redirect('index');
    }
}