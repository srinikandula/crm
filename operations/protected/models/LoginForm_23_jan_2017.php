<?php


class LoginForm extends CFormModel
{
	public $username;
	public $password;
	private $_identity;


	public function rules()
	{
		if($_POST['type']=='customer'){
			return array(
				// username and password are required
				array('username, password', 'required'),
				// rememberMe needs to be a boolean
				array('password', 'customerauthenticate'),
			);
		}else{
			return array(
				// username and password are required
				array('username, password', 'required'),
				// rememberMe needs to be a boolean
				array('password', 'authenticate'),
			);
		}
	}

	public function attributeLabels()
	{
		return array(
		);
	}


	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

		public function customerauthenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->customerauthenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}


	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,0);
			return true;
		}
		else
			return false;
	}

	public function customerlogin()
	{

		if($this->_identity===null)
		{

			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->customerauthenticate();
			
		}
		
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{

			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,0);
			//exit("inside cl");
			return true;
		}
		else{
		
			return false;
		}
	}
}
