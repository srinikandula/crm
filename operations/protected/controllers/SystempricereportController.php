<?php

class SystempricereportController extends Controller {

    public function actionIndex() {
        /*$searchResults = array();
        $cache = Yii::app()->cache;
        $md5=md5(implode("-",$_POST['search']));

            //$searchData=$cache->get($md5);
            //if($searchData===false){
            if (1) {

                $rows = Loadtruckrequest::model()->getSystemPriceReport($_POST['search']);
                //echo '<pre>';print_r($rows);echo '</pre>';//exit;
                foreach ($rows as $rowsK => $rowsV) {
                    foreach ($rowsV as $array) {
                        $searchResults[] = $array;
                    }
                }

                $cache->set($md5, $searchResults, 100, new CDbCacheDependency('SELECT MAX( date_modified ) AS dm FROM (SELECT date_modified FROM {{load_truck_request}} UNION ALL SELECT date_modified FROM {{order}} ) AS tab'));
                $searchData = $searchResults;
            }
        

        //exit;
        $searchData=array();
        $searchData[]=array("source_address"=>"Hyderabad","destination_address"=>"Warangal","min_price"=>"100","avg_price"=>"1200"); 
        $searchData[]=array("source_address"=>"Hyderabad","destination_address"=>"Warangal","min_price"=>"100","avg_price"=>"1200");
        $searchData[]=array("source_address"=>"Hyderabad","destination_address"=>"Warangal","min_price"=>"100","avg_price"=>"1200");
        $searchData[]=array("source_address"=>"Hyderabad","destination_address"=>"Warangal","min_price"=>"100","avg_price"=>"1200");
        //echo '<pre>';print_r($searchData);echo '</pre>';
        $arrayDataProvider = new CArrayDataProvider($searchData, array(
            'pagination' => array(
                'pageSize' => 2,//Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
        ));*/
        $model=New Loadtruckrequest();
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Loadtruckrequest']))
            $model->attributes = $_GET['Loadtruckrequest'];
        $sqlDataProvider=Loadtruckrequest::model()->getSystemPriceReport($_GET['Loadtruckrequest']);
        
        //$model=$arrayDataProvider;
        $this->render('index', array('dataProvider' => $sqlDataProvider,'model'=>$model));
    }

    public function loadModel($id) {
        $model = Loadtype::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'country-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}