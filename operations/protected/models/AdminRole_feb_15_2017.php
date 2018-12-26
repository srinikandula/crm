<?php

class AdminRole extends CActiveRecord
{
	public function tableName()
	{
		return '{{admin_role}}';
	}

	public function rules()
	{
		return array(
            array('role', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('role', 'length', 'max'=>100),
			array('date_added, date_modified,status', 'safe'),
			array('date_created','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>true,'on'=>'insert'),
            array('date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'update'),
            array('date_created,date_modified','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('id_admin_role, role, date_created, date_modified, status', 'safe', 'on'=>'search'),
			
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_admin_role' => 'Admin Role',
			'role' => 'Role',
			'date_created' => 'Date Added',
			'date_modified' => 'Date Modified',
			'status' => 'Status',
		);
	}


	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('role',$this->role,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' =>Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN'),
            ),
			'sort' => array(
                'defaultOrder' => 'id_admin_role DESC',
			),
		));
	}
        


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	public function getAdminRole(){
			/* $cache=Yii::app()->cache;
            $adminroles=$cache->get('a_adminroles');
            if($adminroles===false)
            {
                    $adminroles=AdminRole::model()->findAll();
                    $cache->set('a_adminroles',$adminroles , Yii::app()->config->getData('CONFIG_WEBSITE_CACHE_LIFE_TIME'), new CDbCacheDependency('SELECT concat(MAX(date_modified),"-",count(id_admin_role)) as date_modified FROM {{admin_role}}'));
            }*/
			$cond=$_SESSION['id_franchise']==1?'id_franchise="'.$_SESSION['id_franchise'].'"':'id_admin_role="2" or id_franchise="'.$_SESSION['id_franchise'].'"';
			$adminroles=AdminRole::model()->findAll($cond);
            return $adminroles;
    }
}