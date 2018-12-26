<?php


class Customer extends CActiveRecord
{
	public $confirm;
	public function tableName()
	{
		return '{{customer}}';
	}

	public function rules()
	{
		return array(
            array('firstname,username,email','required'),
            array('email', 'email'),
			array('username','unique'),
			array('password', 'length', 'min'=>6),
			array('password', 'required','on'=>'insert'),
			//array('password, confirm', 'length', 'min'=>6),
			//array('password, confirm', 'required','on'=>'insert'),
			//array('confirm', 'compare', 'compareAttribute'=>'password'),
			array('telephone', 'numerical', 'integerOnly'=>true),
			array('telephone', 'length', 'max'=>12),
			array('status, approved', 'numerical', 'integerOnly'=>true),
			array('newsletter', 'length', 'max'=>1),
			array('status,approved','default','value'=>1,'setOnEmpty'=>true,'on'=>'insert'),
			array('date_created,date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('id_customer, gender,company,designation,mobile,company_address,company_website,company_tinno, firstname, lastname, email,  ip, telephone, password, newsletter, status, approved', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'gender' => 'Gender',
			'firstname' => 'Customer Name',
			'lastname' => 'Last Name',
			'telephone' => 'Office Number',
			'email' => 'Email',
			'password' => 'Password',
			'confirm' => 'Confirm Password',
			'newsletter' => 'Newsletter',
			'status' => 'Status',
			'approved' => 'Approved',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;
	
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('company_website',$this->company_website,true);
		$criteria->compare('telephone',$this->telephone,true);
		//$criteria->compare('id_customer_group',$this->id_customer_group);
		$criteria->compare('status',$this->status);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('date_created',$this->date_created,true);
		//$criteria->order='id_customer desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
                ),
				'sort' => array(
					'defaultOrder' => 'id_customer DESC',
					),
		));
	}
    
 	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getCustomerInfo($input)
    {
        $criteria=new CDbCriteria;
        
        switch ($input['data'])
        {
            case 'PendingApproval':
								if(Yii::app()->config->getData('CONFIG_STORE_APPROVE_NEW_CUSTOMER')){
									$criteria->select="count(approved) as approved";
									$criteria->condition='approved=0';
									$model=Customer::model()->find($criteria);
									$return=$model->approved;
								}
                            break;
                        
            case 'TotalCustomers':
                                $criteria->select="count(id_customer) as id_customer";
                                //$criteria->condition='approved=0';
                                $model=Customer::model()->find($criteria);
                                $return=$model->id_customer;
                            break;
                        
            case 'RegisteredToday':
                                $criteria->select="count(id_customer) as id_customer";
                                $criteria->condition='date(date_created)="'.date(Y)."-".date(m)."-".date(d).'"';
                                $model=Customer::model()->find($criteria);
                                $return=$model->id_customer;
                            break;
        }
        return $return;
    }
	
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}


	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
	
	public function getCustomerName($id)
	{
		$name=Yii::app()->db->createCommand("select concat(firstname,' ',lastname) as customer from {{customer}} where id_customer=".$id)->queryScalar();
		return $name;
	}
}
