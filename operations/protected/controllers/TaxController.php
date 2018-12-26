<?php
class TaxController extends Controller 
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
        
        //echo sizeof(Library::getStateZones()).'<pre>';print_r(Library::getStateZones());exit;
        /*$model=new Tax;
        $model->state='test';
        $model->state_code='te';
        $model->tax='10';
        $model->save(false);
        echo $model->state." state ".$model->state_code." state code ".$model->tax.' tax <pre>';print_r($model);exit;*/
        $model=  Tax::model()->findAll(array('order'=>'state asc'));
        $this->render('index', array('model'=>  $model));
    }
    
    public function actionUpdate(){
        if (Yii::app()->request->isPostRequest)
        {
            //echo '<pre>';print_r($_POST);echo '</pre>';exit;
            //Tax::model()->deleteAll();
            yii::app()->db->createCommand('truncate table {{tax}}')->query();
            foreach($_POST['Commission']['Person'] as $key=>$row){
                $com=new Tax;
                $com->state=$row['state'];
                $com->state_code=$row['state_code'];
                $com->tax=$row['tax'];
                $com->save(false);
            }
        }
        $this->redirect('index');
    }
}