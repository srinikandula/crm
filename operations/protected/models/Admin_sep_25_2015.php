<?php

class Admin extends CActiveRecord
{
	public $admin;
	public $admin_role;
	public $confirm;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{admin}}';
	}

	public function rules()
	{
		return array(
			array('id_admin_role, first_name, last_name, email,city,state', 'required'),
			array('email', 'unique'),
			array('email', 'email'),
			array('password, confirm', 'length', 'min'=>6),
			array('password, confirm', 'required','on'=>'insert'),
			array('confirm', 'compare', 'compareAttribute'=>'password','on'=>'insert'),
			array('confirm', 'compare', 'compareAttribute'=>'password','on'=>'update'),
			array('id_admin_role, status,phone', 'numerical', 'integerOnly'=>true),
			array('status','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('phone', 'length', 'max'=>12),
			array('first_name, last_name, email', 'length', 'max'=>50),
			array('date_created','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>true,'on'=>'insert'),
            array('date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'update'),
            array('date_created,date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('admin,admin_role,id_admin, id_admin_role, password, first_name, last_name,admin_role, phone, email, date_created, present_visit_date, last_visit_date, date_modified, status,city,state', 'safe'),
		);
	}
	

	public function relations()
	{
		return array(
			'adminrole'=>array(self::BELONGS_TO,'AdminRole','id_admin_role'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_admin_role' => 'Admin Role',
			'password' => 'Password',
			'confirm' => 'Confirm Password',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'phone' => 'Phone',
			'email' => 'Email',
			'status' => 'Status',
		);
	}

	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}


	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
	
	public function validateEmail($email)
	{
		$email=Yii::app()->db->createCommand("select id_admin from {{admin}} where email='".$email."'")->queryScalar();
		return $email;
	}
	public function randomPassword()
	{	
		$length = 6;
		$chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
		shuffle($chars);
		$password = implode(array_slice($chars, 0, $length));
		return $password;
	}
        
    public function search()
	{
		
		$criteria=new CDbCriteria;
		$criteria->compare('adminrole.role',$this->admin_role,true);
		//$criteria->compare('concat(t.first_name," ",t.last_name)',$this->admin);
		//$criteria->select='CONCAT( t.first_name," ", t.last_name) AS admin ,t.*';
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		//$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('t.status',$this->status,true);
		$criteria->with=array('adminrole');
		//$criteria->order='id_admin desc';

		if($_SESSION['id_admin_role']!=1){ //other users should view only there details
			$criteria->condition='id_admin='.Yii::app()->user->id;
		}
           

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_admin DESC',
			),
		));
	}

	public function getLeastAssigmentId() {
        $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{customer}} c,{{customer_lead}} cl where c.id_customer=cl.id_customer and c.islead=1 and cl.id_admin_assigned=a.id_admin and c.date_created>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and a.id_admin_role=10 order by rows asc")->queryRow(); //10=Outbound Calling Team
        return $row;
    }

	public function getLeastAssigmentIdSearch() {
        $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{load_truck_request}} ltr where ltr.id_admin_assigned=a.id_admin and ltr.date_created>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and  a.id_admin_role=10 order by rows asc")->queryRow(); //10=Outbound Calling Team
        return $row;
    }

	public function getLeastAssigmentForOrders() {
        $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{order}} o where o.id_admin_assigned=a.id_admin and o.date(date_ordered)>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and  a.id_admin_role=4 order by rows asc")->queryRow(); //10=Outbound Calling Team
        return $row;
    }
}
