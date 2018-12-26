<?php

class DashBoardController extends Controller 
{
    public function accessRules()
    {
        return $this->addActions(array('bestsellers','bestcustomers','bestcategories','productsbymanufacturers','productsbycategories','orderschart'));
    }
    
    public function actionorderschart()
    {
        $data=Order::getOrderChartInfo(array('range'=>$_GET['range']));
        $this->renderPartial('orderschart',array('data'=>$data));
    }
	public function actionbestcustomers()
    {
        $bestCustomers=new Order(bestCustomers);
        $this->renderPartial('bestcustomers', array('model' =>array( 'bestCustomers'=>$bestCustomers)));
    }
	
	public function actionbestcategories()
    {
        $bestCategories=new OrderProduct(bestCategories);
        /*echo '<pre>';
        print_r($bestCategories);
        echo '</pre>';*/
        $this->renderPartial('bestcategories', array('model' =>array( 'bestCategories'=>$bestCategories)));
    }
	
	public function actionproductsbymanufacturers()
    {
        $productsByManufacturers=new Manufacturer(productsByManufacturers);
        $this->renderPartial('productsbymanufacturers', array('model' =>array( 'productsByManufacturers'=>$productsByManufacturers)));
    }
	
	public function actionproductsbycategories()
    {
        $productsByCategories=new Category(productsByCategories);
        $this->renderPartial('productsbycategories', array('model' =>array( 'productsByCategories'=>$productsByCategories)));
    }
	
    
    public function actionbestsellers()
    {
        $bestSellers=new OrderProduct(bestSellers);
         $this->renderPartial('bestsellers', array('model' =>array( 'bestSellers'=>$bestSellers)));
    }
    
	
    public function actionIndex() 
    {


        $this->render('index');
    }
    
     protected function gridBestCategories($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'name':
                $return = Library::shortString(array('str'=>$data['name'],'len'=>20));
                break;
        }
        return $return;
    }
    
    
    protected function gridBestSellers($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'name':
                $return = Library::shortString(array('str'=>$data->name,'len'=>20));
                break;
            
            case 'model':
                $return = Library::shortString(array('str'=>$data->model,'len'=>20));
                break;
        }
        return $return;
    }
    
    protected function gridBestCustomers($data, $row, $dataColumn) {
        switch ($dataColumn->name) {
            case 'customer':
                $return = Library::shortString(array('str'=>$data->customer,'len'=>20));
                break;
			case 'total':
                $return = Yii::app()->currency->format($data->total,Yii::app()->config->getData('CONFIG_STORE_DEFAULT_CURRENCY'));
                break;
        }
        return $return;
    }
}
