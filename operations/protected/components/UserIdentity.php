<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$fran=Franchise::model()->find(array('select'=>'id_franchise','condition'=>'status=1 and account like "'.$_POST['LoginForm']['account'].'"'));
		$id_franchise=(int)$fran->id_franchise;
		$user=Admin::model()->find('id_franchise='.$id_franchise.' and status=1 and LOWER(email)=?',array(strtolower($this->username)));
		/*echo "<pre>";
		print_r($user);
		exit;*/
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id_admin;
		    //$this->setState('first_name', $user->first_name);
			//$this->setState('last_name', $user->last_name);
			Yii::app()->session['first_name'] =$user->first_name;
			Yii::app()->session['franchise'] =$_POST['LoginForm']['account'];
			Yii::app()->session['id_franchise'] =$id_franchise;
            Yii::app()->session['id_admin_role'] =$user->id_admin_role;
			Yii::app()->session['last_name'] =$user->last_name;
			Yii::app()->session['email'] =$user->email;
			Yii::app()->session['mobile'] =$user->phone;
			//Yii::app()->session->set('user.first_name',$user->first_name);
			//Yii::app()->session->set('user.last_name',$user->last_name);
 			$this->username=$user->email;
			$this->errorCode=self::ERROR_NONE;
			Yii::app()->db->createCommand('UPDATE {{admin}} SET last_visit_date = present_visit_date,present_visit_date = NOW( ) WHERE id_admin ="'.$user->id_admin.'"')->query();
			$cookie = new CHttpCookie('cNmC', $user->present_visit_date);
			$cookie->expire = time()+60*60;
			Yii::app()->request->cookies['cNmC'] = $cookie;
			//exit("inside");
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	public function customerauthenticate()
	{
		//$user=Admin::model()->find('status=1 and LOWER(email)=?',array(strtolower($this->username)));
		$user=Customer::model()->find('islead=0 and trash=0 and type="TR" and status=1 and approved=1 and LOWER(mobile)=?',array(strtolower($this->username)));
		/*echo "<pre>";
		print_r($user);
		exit;*/
		if($user===null){
		//exit("inside");	
		$this->errorCode=self::ERROR_USERNAME_INVALID;
		}else if(!$user->validatePassword($this->password)){
			//exit("inside else if ");
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			
			$this->_id=$user->id_customer;
		    //$this->setState('first_name', $user->first_name);
			//$this->setState('last_name', $user->last_name);
			Yii::app()->session['first_name'] =$user->fullname;
            Yii::app()->session['id_admin_role'] =8;
			Yii::app()->session['last_name'] ='';//$user->last_name;
			//Yii::app()->session->set('user.first_name',$user->first_name);
			//Yii::app()->session->set('user.last_name',$user->last_name);
 			$this->username=$user->email;
			$this->errorCode=self::ERROR_NONE;
			Yii::app()->db->createCommand('UPDATE {{admin}} SET last_visit_date = present_visit_date,present_visit_date = NOW( ) WHERE id_admin ="'.$user->id_customer.'"')->query();
			
			/*$cookie = new CHttpCookie('cNmC', $user->present_visit_date);
			$cookie->expire = time()+60*60;
			Yii::app()->request->cookies['cNmC'] = $cookie;*/
			//exit("inside");
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}

	 
}