<?php

class SiteController extends Controller {

    public $layout = "//layouts/guest";

    public function actions() {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex() {
        //exit("here");
        //$this->redirect($this->createUrl('site/login'));
		if(!isset(Yii::app()->user->id)){
			$this->redirect($this->createUrl('site/customer'));
		}else{
			$this->redirect($this->createUrl('load/index'));
		}
        //$this->render('index'); //commented temporarly for transporter    
    }

    public function filters() {
        return array(
            'ajaxOnly + editable'
        );
    }

    public function actionforgotpassword() {
        $this->IsLogedInRedirect();
        $model = new Admin();

        if (Yii::app()->request->isPostRequest) {
            $id_admin = Admin::validateEmail($_POST['Admin']['email']);

            if ($id_admin) {
                $password = Admin::randomPassword();

                $result = Admin::model()->updateAll(array("password" => Admin::hashPassword($password)), "id_admin='" . (int) $id_admin . "' and email='" . $_POST['Admin']['email'] . "'");

                $admin = Admin::model()->find(array("condition" => "id_admin=" . $id_admin));
                /*$data = array('id' => '8', 'replace' => array('%username%' => $admin['first_name'] . $admin['last_name'], '%password%' => $password, '%store_name%' => Yii::app()->config->getData('CONFIG_STORE_NAME')),
                    'mail' => array("to" => array($_POST['Admin']['email'] => $admin['first_name'] . $admin['last_name'],),
                        "from" => array(Yii::app()->config->getData('CONFIG_STORE_SUPPORT_EMAIL_ADDRESS') => Yii::app()->config->getData('CONFIG_STORE_NAME')),
                        "reply" => array(Yii::app()->config->getData('CONFIG_STORE_REPLY_EMAIL') => Yii::app()->config->getData('CONFIG_STORE_NAME')),)
                );
                Mail::send($data);*/
                $data=array();
		$data['to']=$_POST['Admin']['email'];
		//$data['from']='from@gmail.com';//not mandatory admin reply email will work here
		$data['subject']='Forgot Password';
		$data['message']='hello ,<br/>This is your new password:'.$password;
		Library::sendMail();
                

                if ($result)
                    Yii::app()->user->setFlash('success', Yii::t('login', 'text_forgot_success'));
                else
                    Yii::app()->user->setFlash('success', Yii::t('login', 'text_forgot_warning'));
            }else {
                Yii::app()->user->setFlash('success', Yii::t('login', 'text_forgot_invalid'));
            }
        }
        $this->render('forgotpassword', array('model' => $model));
    }

    public function actionError() {
        $this->layout = isset(Yii::app()->user->id) ? '//layouts/column1' : '//layouts/guest';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /* public function one(&$total,&$tax)
      {
      $total[]="100";
      $tax[]="200";
      }

      public function two(&$total,&$tax)
      {
      $total[]=500;//"100";
      $tax[]=1000;//"200";
      } */
    
    public function actionCustomer() {
        $this->IsLogedInRedirect();
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->customerlogin())
                //exit("inside");
                if (Yii::app()->session['id_admin_role'] != "") {
                    $row = AdminPermissions::getWelcomePage((int) Yii::app()->session['id_admin_role']);
                    $this->redirect(array($row->file_name . '/index'));
                } else {
                    $this->redirect(array('site/index'));
                }
        }
        $this->render('customerlogin', array('model' => $model));
    }

    public function actionLogin() {
        /* $total=array();
          $tax=array();
          echo '<pre>';
          //echo $total." total : tax :".$tax."<br/>";
          print_r($total);print_r($tax);
          $this->one($total,$tax);
          //echo $total." total : tax :".$tax."<br/>";
          print_r($total);print_r($tax);
          $this->two($total,$tax);
          //echo $total." total : tax :".$tax."<br/>";
          print_r($total);print_r($tax);
          exit; */
        //echo "value of ".Yii::app()->baseUrl;
        //exit;
        //exit(Yii::app()->user->returnUrl);
        //exit(Yii::app()->user->isGuest);
        $this->IsLogedInRedirect();
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
            //$this->redirect(Yii::app()->user->returnUrl);
            //exit(Yii::app()->homeUrl);
            //$this->redirect(array('productinfo/admin'));
                if (Yii::app()->session['id_admin_role'] != "") {
                    $row = AdminPermissions::getWelcomePage((int) Yii::app()->session['id_admin_role']);
                    //echo '<pre>';print_r($row);
                    //exit("value of ".Yii::app()->session['id_admin_role']);
                    $this->redirect(array($row->file_name . '/index'));
                } else {
                    $this->redirect(array('site/index'));
                }
        }
        // display the login form
        //Yii::beginProfile('view');
        $this->render('login', array('model' => $model));
        //Yii::endProfile('view');
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    protected function IsLogedInRedirect() {
        if (isset(Yii::app()->user->id)):
            $this->redirect(Yii::app()->user->getReturnUrl());
        endif;
    }

    public function actionEditable() {
        if (Yii::app()->request->getIsAjaxRequest() && isset($_POST) && isset($_POST['model'])) {
            Yii::import('ext.bootstrap.widgets.TbEditableSaver'); //or you can add import 'ext.editable.*' to config
            $es = new TbEditableSaver($_POST['model']);  // 'User' is classname of model to be updated
            $es->update();
        }
    }

    public function actiongetStates($id) {
        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array("states" => State::getStates(array('condition' => 'id_country=' . $id))));
        }
    }
    
    public function actionGetEncPassword() {
        $json=array("status"=>0,"password"=>"");
        $password=trim($_POST['password']);
        if (Yii::app()->request->isPostRequest && $password!="") {
            $json["status"]=1;
            $json["password"]=Admin::hashPassword($password);
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionValidatePassword() {
        $json=array("status"=>0);
        $encrypt=trim($_POST['encrypt']);
        $password=trim($_POST['password']);
        if (Yii::app()->request->isPostRequest && $password!="" && $encrypt!="") {
            $json["status"]=CPasswordHelper::verifyPassword($password,$encrypt)?1:0;
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
    
    public function actionGetInBoundUser(){
        $json=array("status"=>0);
        if (Yii::app()->request->isPostRequest) {
                $row = Yii::app()->db->CreateCommand("select a.id_admin,(select count(*) from {{customer}} c,{{customer_access_permission}} cl where c.id_customer=cl.id_customer and c.islead=1 and cl.id_admin=a.id_admin and cl.status=1 and c.date_created>'" . date('Y-m-d', strtotime("-2 days")) . "') as rows from {{admin}} a where a.status=1 and a.id_admin_role=9 order by rows asc")->queryRow();
                $json['status']=1;
                $json['id_admin']=$row['id_admin'];
                
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}