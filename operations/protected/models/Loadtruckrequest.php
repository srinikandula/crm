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
	public $system_min_price;
	public $system_avg_price;
	public $source_address;
	public $destination_address;
	public $date_created_from;
	public $date_created_to;
	public $group_by;
	public function tableName()
	{
		return '{{load_truck_request}}';
	}

	public function rules()
	{
		return array(
			array('title,id_customer,source_address,destination_address', 'required'),
			array('id_customer,approved,truck_type,tracking,id_goods_type,id_truck_type,insurance,id_load_type', 'numerical', 'integerOnly'=>true),
			array('push_message,push_notified,id_load_truck_request,expected_price,pickup_point,status,type,fullname,truck_type,goods_type,price_from,price_to,date_required,date_modified,date_created,destination_state,destination_city,source_state,source_city,source_address,destination_address,title,comment,source_lat,source_lng,destination_lat,destination_lng,tonnes,cancel_reason,expected_price_comment,modified_fields,date_created_from,date_created_to,group_by,no_of_trucks','safe'),
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
		$criteria->compare('t.id_load_truck_request',$this->id_load_truck_request);
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

	/*public function getBookedRequests(){
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_load_truck_request) as title,count(t.id_load_truck_request) as id_load_truck_request";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}
		
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');
		}

		$criteria->condition='t.id_order!=0';

		return Loadtruckrequest::model()->find($criteria);
	}

	public function getCanceledRequests(){
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_load_truck_request) as title,count(t.id_load_truck_request) as id_load_truck_request";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}

		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.isactive','0');
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');
		}
		return Loadtruckrequest::model()->find($criteria);
	}*/

	public function getTruckRequestBlock($input){
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_load_truck_request) as title,count(t.id_load_truck_request) as id_load_truck_request";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}
		
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');
		}
		
		switch($input['type']){
				case 'booked_requests':
 					$criteria->condition='t.id_order!=0';
							   break;
				case 'canceled_requests':
 					$criteria->compare('t.isactive','0');
							   break;
		}


		return Loadtruckrequest::model()->find($criteria);
	}

	public function truckRequestReport()
	{
        $group_by=$this->group_by==""?"week(t.date_created)":$this->group_by."(t.date_created)";    
		$criteria=new CDbCriteria;
		$criteria->select="group_concat(t.id_load_truck_request) as title,count(t.id_load_truck_request) as id_load_truck_request,min(t.date_created) as date_created_from,max(t.date_created) as date_created_to";
		
		$date_created_from=$this->date_created_from;
		$date_created_to=$this->date_created_to;

		if($date_created_from!="" && $date_created_to==""){
			$criteria->compare('date(t.date_created)>',$date_created_from);	
		}else if($date_created_from=="" && $date_created_to!=""){
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}else if($date_created_from!="" && $date_created_from!=""){
			$criteria->compare('date(t.date_created)>',$date_created_from);
			$criteria->compare('date(t.date_created)<',$date_created_to);
		}
		
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.id_truck_type',$this->id_truck_type);
		if($this->source_address!=""){
			$criteria->compare('t.source_city',$this->source_address,true,'or');
			$criteria->compare('t.source_state',$this->source_address,true,'or');
			$criteria->compare('t.source_address',$this->source_address,true,'or');
		}

		if($this->destination_address!=""){
			$criteria->compare('t.destination_city',$this->destination_address,true,'or');
			$criteria->compare('t.destination_state',$this->destination_address,true,'or');
			$criteria->compare('t.destination_address',$this->destination_address,true,'or');
		}

		$criteria->group=$group_by;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 't.date_created DESC',
				),
		));
	}

	public function getTruckRequestDetails(){
		$criteria=new CDbCriteria;
		$criteria->select="t.title,t.source_address,t.destination_address,t.expected_price,t.date_created,t.date_required,t.pickup_point,t.comment,tt.title as id_truck_type,gt.title as id_goods_type";
		$criteria->join="left join {{truck_type}} tt on t.id_truck_type=tt.id_truck_type left join {{goods_type}} gt on t.id_goods_type=gt.id_goods_type";
		$criteria->condition="t.id_load_truck_request in (".base64_decode($_GET['data']).")";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
            'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 't.date_created DESC',
				),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getSystemQuotes($input){
		$expTT=explode(" ",$input['id_truck_type']);
		$ton=(int)end($expTT);
		$tonCond=$ton>10?"tonnes>10":"tonnes<=10";
		
		$ttRow=Yii::app()->db->createCommand("select group_concat(id_truck_type) as id_truck_type from {{truck_type}} where (".$tonCond.")")->queryRow();
		
		$id_truck_types=$ttRow['id_truck_type'];
		
		$source_address="source_address like '%".$input['source']."%'";
		
		$destination_address="destination_address like '%".$input['destination']."%'";
		

		if($ton>10){ //if above 10 then divide amount by tonnes as we need per ton price for above 10tonnes
			$ordQry='(select o.source_address,o.destination_address,o.amount as price from eg_order o where  o.'.$source_address.' and  o.'.$destination_address.' and o.id_truck_type in ('.$id_truck_types.')';
		}else{
			$ordQry='(select o.source_address,o.destination_address,o.amount/tt.tonnes as price from eg_order o,eg_truck_type tt where tt.id_truck_type=o.id_truck_type and  o.'.$source_address.' and  o.'.$destination_address.' and o.id_truck_type in ('.$id_truck_types.')';
		}

		$qry='select source_address,destination_address,MIN(NULLIF(truncate(price,2), 0)) as system_min_price,truncate(avg(truncate(price,2)),2) as system_avg_price from ('.$ordQry.') union all (select ltr.source_address,ltr.destination_address,ltrq.quote as price from eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.'.$source_address.' and  ltr.'.$destination_address.' and  ltr.id_truck_type in ('.$id_truck_types.'))) as tab';

		$result=Yii::app()->db->createCommand($qry)->queryRow();
		$return=array();
		$return['min']=$result['system_min_price'];
		$return['avg']=$result['system_avg_price'];
	return $return;	
	}

	/*public function getSystemQuotes($input){
	
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

	$ordRow=Yii::app()->db->createCommand("select MIN(NULLIF(amount, 0)) as min_amount,avg(amount) as avg_amount from {{order}} where id_truck_type in(".$ttRow['id_truck_type'].") and (source_address like '%$source_address%' or source_city like '%$source_city%' or source_state like '%$source_state%') and (destination_address like '%$destination_address%' or destination_city like '%$destination_city%' or destination_state like '%$destination_state%') ")->queryRow();

	$trqRow=Yii::app()->db->createCommand("select MIN(NULLIF(ltrq.quote, 0)) as  min_quote,avg(ltrq.quote) as  avg_quote from {{load_truck_request}} ltr,{{load_truck_request_quotes}} ltrq where (ltr.id_load_truck_request=ltrq.id_load_truck_request) and (ltr.id_truck_type in (".$ttRow['id_truck_type'].")) and (ltr.source_address like '%$source_address%' or ltr.source_city like '%$source_city%' or ltr.source_state like '%$source_state%') and (ltr.destination_address like '%$destination_address%' or ltr.destination_city like '%$destination_city%' or ltr.destination_state like '%$destination_state%')")->queryRow();

	$return=array();
	$return['min']=(int)min($trqRow['min_quote'],$ordRow['min_amount']);
	$return['avg']=$ton>10?(int)(($ordRow['avg_amount']/$ton)+($trqRow['avg_quote']))/2:(int)(($ordRow['avg_amount'])+($trqRow['avg_quote']))/2;
	return $return;	
	}*/

	public function getSystemPriceReport($input){
	
		$tonCond=$input['id_truck_type']>10?"tonnes>10":"tonnes<=10";
		
		$ttRow=Yii::app()->db->createCommand("select group_concat(id_truck_type) as id_truck_type from {{truck_type}} where (".$tonCond.")")->queryRow();
		$id_truck_types=$ttRow['id_truck_type'];
		
		if($input['source_address']!=''){
			$source_address="source_address like '%".$input['source_address']."%'";
		}else{
			$source_address='source_address!=""';
		}

		if($input['destination_address']!=''){
			$destination_address="destination_address like '%".$input['destination_address']."%'";
		}else{
			$destination_address='destination_address!=""';
		}

		if($input['id_truck_type']=="" || $input['id_truck_type']==10){ //if above 10 then divide amount by tonnes as we need per ton price for above 10tonnes
			$ordQry='(select o.source_address,o.destination_address,o.amount as price from eg_order o where  o.'.$source_address.' and  o.'.$destination_address.' and o.id_truck_type in ('.$id_truck_types.')';
		}else{
			$ordQry='(select o.source_address,o.destination_address,o.amount/tt.tonnes as price from eg_order o,eg_truck_type tt where tt.id_truck_type=o.id_truck_type and  o.'.$source_address.' and  o.'.$destination_address.' and o.id_truck_type in ('.$id_truck_types.')';
		}


		$qry='select source_address,destination_address,MIN(NULLIF(price, 0)) as system_min_price,truncate(avg(price),2) as system_avg_price from ('.$ordQry.') union all (select ltr.source_address,ltr.destination_address,ltrq.quote as price from eg_load_truck_request ltr,eg_load_truck_request_quotes ltrq where ltr.id_load_truck_request=ltrq.id_load_truck_request and ltr.'.$source_address.' and  ltr.'.$destination_address.' and  ltr.id_truck_type in ('.$id_truck_types.'))) as tab group by source_address,destination_address';

		$count=Yii::app()->db->createCommand("select count(*) from (".$qry.") as tab")->queryScalar();
		//echo $count."value of ".Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN');
		//exit;
		$sql=Yii::app()->db->createCommand($qry);
		$sqlDataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>$count,
			'sort'=>array(
				'attributes'=>array(
					 'id_load_truck_request', 'source_address', 'destination_address',
				),
				'defaultOrder' => array(
                            'source_address' => CSort::SORT_ASC, //default sort value
                        ),
			),
			'pagination'=>array(
				'pageSize'=>50,
			),
		));
		
		return $sqlDataProvider;	
	}
}