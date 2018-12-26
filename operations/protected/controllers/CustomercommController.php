<?php
class CustomercommController extends Controller 
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
        $records['customer']=  Customer::model()->findAll('approved=1 and status=1');
        $this->render('index', array('records'=>$records,'model'=>  Commission::model()->findAll('type="PERSON"')));
    }
    
    public function actionUpdate(){
        if (Yii::app()->request->isPostRequest)
        {
            //echo '<pre>';print_r($_POST);echo '</pre>';
            Commission::model()->deleteAll('type="PERSON"');
            $cTypes=  array_flip(Library::getCustomerTypes());
            Customer::model()->updateAll(array('commission_type'=>'','commission'=>''));
            foreach($_POST['Commission']['Person'] as $key=>$row){
            //echo '<pre>';print_r($row);echo '</pre>';
                if($row['title']=="" || $row['commission']==""){
                    continue;
                }
                $exp=explode(",",$row['title']);
                //echo '<pre>';print_r($exp);echo '</pre>';
                $custObj=Customer::model()->find('type="'.$cTypes[$exp[1]].'" and mobile="'.$exp[2].'" and fullname like "'.$exp[0].'"');
                //echo "<br/>value of ".$custObj->id_customer;
                
                //echo $custObj->id_customer." cust id<br/>";
                if($custObj->id_customer){
                    $com=new Commission;
                    $com->title=$row['title'];
                    $com->type='PERSON';
                    $com->commission_type=$row['commission_type'];
                    $com->commission=$row['commission'];
                    $com->save(false);
                    //echo $row['commission_type']." ".$row['commission']." ".$custObj->id_customer."<br/>";
                    Customer::model()->updateAll(array('commission_type'=>$row['commission_type'],'commission'=>$row['commission']),'id_customer='.$custObj->id_customer);
                    //Yii::app()->db->createCommand('update {{customer}} set commission_type="'.$row['commission_type'].'",commission="'.$row['commission'].'" where id_customer="'.$custObj->id_customer.'"')->query();
                }
                
            }//exit;
        }
        $this->redirect('index');
    }
}