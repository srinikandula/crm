<?php

class Loadtruckrequest extends CActiveRecord
{
	public $truck_type;
	public $goods_type;
	public $fullname;
	public $type;
	public $status;
	public $least_quote;
	public $tonnes;
	public $approved;
	public function tableName()
	{
		return '{{load_truck_request}}';
	}

	public function rules()
	{
		return array(
			array('title,id_customer,source_address,destination_address', 'required'),
			array('id_customer,approved,truck_type,tracking,id_goods_type,id_truck_type,insurance,id_load_type', 'numerical', 'integerOnly'=>true),
			array('expected_price,pickup_point,status,type,fullname,truck_type,goods_type,price_from,price_to,date_requried,date_modified,date_created,destination_state,destination_city,source_state,source_city,source_address,destination_address,title,comment,source_lat,source_lng,destination_lat,destination_lng,tonnes,cancel_reason,expected_price_comment,modified_fields','safe'),
			);
	}

	public function relations()
	{
		return array();
	}
	
        public function attributeLabels()
	{
		return array(
			'title'=>'Customer',
			'tracking'=>'Tracking Required',
			'insurance'=>'Insurance Required',
			'id_load_type'=>'Load Type',
			'id_truck_type'=>'Truck Type',
			'id_goods_type'=>'Goods Type',
			'expected_price_comment'=>'Reason For Price Change',
			'cancel_reason'=>'Reason For Cancel',
			);
	}


        
    /*public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.state',$this->state,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'source DESC,destination DESC',
				),
		));
	}*/


	public function Assignment(){
		
		$criteria=new CDbCriteria;
        $criteria->select="t.*";
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('isactive','1');
		$criteria->compare('id_order','0');
		//$criteria->condition='type="T"';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 't.date_created DESC',
					),
		));
	}

	public function searchLoad()
	{
            
		$criteria=new CDbCriteria;
		$criteria->select="c.type,c.fullname,t.*,tt.title as truck_type,gt.title as goods_type";
		$criteria->compare('t.approved',$this->approved);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('c.fullname',$this->fullname,true);
		$criteria->compare('c.type',$this->type);
		$criteria->compare('t.source_city',$this->source_city,true);
		$criteria->compare('t.destination_city',$this->destination_city,true);
		$criteria->compare('t.tracking',$this->tracking);
		$criteria->compare('t.insurance',$this->insurance);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('date(t.date_required)',$this->date_required,true);
		$criteria->compare('tt.title',$this->truck_type,true);
                if($_SESSION['id_admin_role']==8){//if transporter than hide canceled request ie isactive=0
                    $criteria->compare('t.isactive',1);
                }
		$criteria->compare('gt.title',$this->goods_type,true);
		$criteria->compare('t.status',$this->status,true);
		
	
		$criteria->join="inner join {{customer}} c on t.id_customer=c.id_customer inner join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type inner join {{goods_type}} gt on gt.id_goods_type=t.id_goods_type";	
		
		if((int)$_GET['cid']){ //if navigated from truck
			//$criteria->condition='t.id_customer="'.$_GET['cid'].'"';
			$criteria->compare('t.id_customer',$_GET['cid']);
		}

		if($_SESSION['id_admin_role']==8){ //for transporter
			//$criteria->condition='t.id_customer="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_customer',(int)Yii::app()->user->id);
		}

		/*if($_SESSION['id_admin_role']==10){ //for outbound calling team
			//$criteria->condition='t.id_admin_assigned="'.(int)Yii::app()->user->id.'"';
			$criteria->compare('t.id_admin_assigned',(int)Yii::app()->user->id);
		}*/
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_load_truck_request DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getSystemQuotes($input){
	
	$source=Library::getGPDetails($input['source']);
	$destination=Library::getGPDetails($input['destination']);
	$source_city=$source['city']==""?$source['input']:$source['city'];
	$source_state=$source['state']==""?$source['input']:$source['state'];
	$source_address=$source['input'];

	$destination_city=$destination['city']==""?$destination['input']:$destination['city'];
	$destination_state=$destination['state']==""?$destination['input']:$destination['state'];
	$destination_address=$destination['input'];

	$expTT=explode(" ",$input['id_truck_type']);
	$ton=(int)end($expTT);
	$tonCond=$ton>10?"tonnes>10":"tonnes<=10";
	$ttRow=Yii::app()->db->createCommand("select group_concat(id_truck_type) as id_truck_type from {{truck_type}} where (".$tonCond.")")->queryRow();

	$ordRow=Yii::app()->db->createCommand("select avg(amount) as avg_amount from {{order}} where id_truck_type in(".$ttRow['id_truck_type'].") and (source_address like '%$source_address%' or source_city like '%$source_city%' or source_state like '%$source_state%') and (destination_address like '%$destination_address%' or destination_city like '%$destination_city%' or destination_state like '%$destination_state%') ")->queryRow();

	$trqRow=Yii::app()->db->createCommand("select min(ltrq.quote) as  min_quote,avg(ltrq.quote) as  avg_quote from {{load_truck_request}} ltr,{{load_truck_request_quotes}} ltrq where (ltr.id_load_truck_request=ltrq.id_load_truck_request) and (ltr.id_truck_request in (".$ttRow['id_truck_type'].")) and (ltr.source_address like '%$source_address%' or ltr.source_city like '%$source_city%' or ltr.source_state like '%$source_state%') and (ltr.destination_address like '%$destination_address%' or ltr.destination_city like '%$destination_city%' or ltr.destination_state like '%$destination_state%')")->queryRow();

	$return=array();
	$return['min']=$trqRow['min_quote'];
	$return['avg']=$ton>10?(($ordRow['avg_amount']/$ton)+($trqRow['avg_quote']))/2:(($ordRow['avg_amount'])+($trqRow['avg_quote']))/2;
	}
}