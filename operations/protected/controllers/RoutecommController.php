<?php
class RoutecommController extends Controller 
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
        $source=Truckrouteprice::model()->findAll(array('select'=>'distinct(source_city)','condition'=>'route_allowed=1'));
        $destination=Truckrouteprice::model()->findAll(array('select'=>'distinct(destination_city)','condition'=>'route_allowed=1'));
        //echo '<pre>';print_r($source);echo '</pre>';
        //exit;
        
        
        $this->render('index', array('destination'=>$destination,'source'=>$source,'records'=>$records,'model'=>  Commission::model()->findAll('type="ROUTE"')));
    }
    
    public function actionUpdate(){
        if (Yii::app()->request->isPostRequest)
        {
            //echo '<pre>';print_r($_POST);echo '</pre>';exit;
            Commission::model()->deleteAll('type="ROUTE"');
            foreach($_POST['Commission']['PersonTruckRoute'] as $key=>$row){
            //echo '<pre>';print_r($row);echo '</pre>';
                if($row['commission']=="" || $row['source_city']=="" || $row['destination_city']==""){
                    continue;
                }
               $com=new Commission;
               //$com->title=$row['title'];
               $com->type='ROUTE';
               $com->source_city=$row['source_city'];
               $com->destination_city=$row['destination_city'];
               $com->commission_type=$row['commission_type'];
               $com->commission=$row['commission'];
               $com->save(false);
                
            }
        }
        Truckrouteprice::model()->updateCommission();
        $this->redirect('index');
    }
}