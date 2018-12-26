<?php

class PointcommController extends Controller {
    /* public function accessRules() {
      return $this->addActions(array('Autosuggest'));
      } */



    /* public function actionIndex()
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
      } */

    public function actionIndex() {
        $source = Truckrouteprice::model()->findAll(array('select' => 'distinct(source_city)', 'condition' => 'route_allowed=1'));
        $destination = Truckrouteprice::model()->findAll(array('select' => 'distinct(destination_city)', 'condition' => 'route_allowed=1'));
        //echo '<pre>';print_r($source);echo '</pre>';
        //exit;


        $this->render('index', array('destination' => $destination, 'source' => $source, 'records' => $records, 'model' => Commission::model()->findAll('type="POINT"')));
    }

    public function actionUpdate() {
        if (Yii::app()->request->isPostRequest) {
            //echo '<pre>';print_r($_POST);echo '</pre>';//exit;
            Commission::model()->deleteAll('type="POINT"');
            foreach ($_POST['Commission']['RouteSource'] as $key => $row) {
                //echo '<pre>';print_r($row);echo '</pre>';
                if ($row['commission'] == "" || $row['source_city'] == "") {
                    continue;
                }
                $com = new Commission;
                $com->type = 'POINT';
                $com->source_city = $row['source_city'];
                $com->commission_type = $row['commission_type'];
                $com->commission = $row['commission'];
                $com->save(false);
            }

            foreach ($_POST['Commission']['RouteDestination'] as $key => $row) {
                //echo '<pre>';print_r($row);echo '</pre>';
                if ($row['commission'] == "" || $row['destination_city'] == "") {
                    continue;
                }
                $com = new Commission;
                $com->type = 'POINT';
                $com->destination_city = $row['destination_city'];
                $com->commission_type = $row['commission_type'];
                $com->commission = $row['commission'];
                $com->save(false);
            }
            //exit;
        }
        Truckrouteprice::model()->updateCommission();
        $this->redirect('index');
    }
}
