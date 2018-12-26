<?php
class Library
{
   
    public function download($input)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($input['file']));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($input['path'].$input['file']));
        ob_clean();
        flush();
        readfile($input['path'].$input['file']);
        exit;
    }
    
    function fileUpload($data)
    {
        if (is_uploaded_file($data['tmp_name']))
        {
            $fileExt=substr(strrchr($data['name'], '.'), 1);//end(explode(".", $data['name']));
            //if(in_array($fileExt,explode(",",'png,jpg')))
			//if(1)
					
				//$types = Yii::app()->config->getData('CONFIG_WEBSITE_ALLOWED_FILE_TYPES');

				//exit($data['name']." ".substr(strrchr($data['name'], '.'), 1)." ".$fileExt);
				//echo '<pre>';
				//print_r(Yii::app()->config->getData('CONFIG_WEBSITE_ALLOWED_FILE_TYPES'));

				//exit($fileExt);
				if (in_array($fileExt, Yii::app()->config->getData('CONFIG_WEBSITE_ALLOWED_FILE_TYPES'))) {
				$file= $data['input']['prefix'].strtotime("now").'.'.$fileExt;
                copy($data['tmp_name'], $data['input']['path'].$file);
                if(isset($data['input']['prev_file']) && file_exists($data['input']['path'].$data['input']['prev_file']))
                {
                        @unlink($data['input']['path'].$data['input']['prev_file']);
                }
                return array('status'=>'1','file'=>$file,'msg'=>'upload successfull!!');
            }else
            {
				return  array('status'=>'0','file'=>$data['input']['prev_file'],'msg'=>'Invalid file extension');
            }
        }
        else
        {
            return array('status'=>'0','file'=>$data['input']['prev_file'],'msg'=>'No file to upload!!');
        }
    }
    
    public function getMiscUploadPath()
    {
        return Yii::app()->params['config']['document_root'].Yii::app()->params['config']['upload_path'].'misc/';
    }
    
    public function getMiscUploadLink()
    {
        return Yii::app()->params['config']['site_url'].Yii::app()->params['config']['upload_path'].'misc/';
    }

	public function getTruckUploadPath()
    {
        return Yii::app()->params['config']['document_root'].Yii::app()->params['config']['upload_path'].'trucks/';
    }
    
    public function getTruckUploadLink()
    {
        return Yii::app()->params['config']['site_url'].Yii::app()->params['config']['upload_path'].'trucks/';
    }

	public function getLoyalityGiftsUploadPath()
    {
        return Yii::app()->params['config']['document_root'].Yii::app()->params['config']['upload_path'].'loyality_gifts/';
    }
    
    public function getLoyalityGiftsUploadLink()
    {
        return Yii::app()->params['config']['site_url'].Yii::app()->params['config']['upload_path'].'loyality_gifts/';
    }
    
     
    public function getPageList($input)
    {
        //echo Yii::app()->getController()->getId()."<br/>"; //controller name
        //exit("inside");
        //echo "getparam ".Yii::app()->request->getParam('page')."<br/>";
        //echo "get".$_GET['page']."<br/>";
        $currentPage=Yii::app()->request->getParam('page',10);
        //exit;
        /*echo "page value  ".Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN');
        echo $currentPage;
        exit;*/
        $pageValues=array('5'=>'5','10'=>'10','30'=>'30','50'=>'50','100'=>'100','200'=>'200');
        if(min($pageValues)<$input['totalItemCount']):
            echo 'List '.CHtml::dropDownList('no-width','page',$pageValues,
            array('options' => array(Yii::app()->config->getData('CONFIG_WEBSITE_ITEMS_PER_PAGE_ADMIN')=>array('selected'=>true)),'class'=>'no-bg-color',
        'onchange'=>"if(this.value!='')". " {". " href=window.location.href;    if(href.search('".Yii::app()->getController()->getId()."/index')=='-1')"
                . "{url=href.replace('/page/".$currentPage."','')+'/index/page/'+this.value;}else { url=href.replace('/page/".$currentPage."','')+'/page/'+this.value;} "
                . "window.location=url;}")
        );	 
        endif;
    }
    
    public function AddButton($input)
    {
		//exit("value of ".$input['url']);
        $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                    'label' => $input['label'],
                    'visible' => $input['permission']==""?$this->addPerm:$input['permission'],//$this->addPerm,
                    'type' => 'info',
                    'icon' => 'icon-plus icon-white',
                    'url' =>$input['url']//('create')
                        )
                );
    }

    public function cancelButton($input)
    {
        $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                'label' => $input['label'],
                'type' => 'danger',
                'url' => $input['url'])
            );
    }
    
    public function saveButton($input)
    {
            $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                'label' => $input['label'],
                'buttonType' => 'submit',
                'visible' => $input['permission'],
                'type' => 'info',
                    )
            );
    }
    
    public function buttonBulk($input)
    {
        $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                    'buttonType' => 'Submit',
                    'label' => $input['label'],
                    'visible' => $input['permission'],
                    'type' => $input['type']==''?'danger':$input['type'],//'danger',
                    'icon' => 'icon-remove icon-white',
                    'htmlOptions' => array('onclick' => 'var flag=validateGridCheckbox("id[]");
        if(flag)
        {
                document.getElementById("gridForm").method="post";
                document.getElementById("gridForm").action="' . $input['url'] . '";
                document.getElementById("gridForm").submit();
        }'),
                        )
                );    
    }

	public function buttonBulkApprove($input)
    {
        $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                    'buttonType' => 'Submit',
                    'label' => $input['label'],
                    'visible' => $input['permission'],
                    'type' => $input['type']==""?'danger':$input['type'],
                    'icon' => 'icon-check icon-white',
                    'htmlOptions' => array('onclick' => 'var flag=validateGridCheckbox("id[]");
        if(flag)
        {
		       
                document.getElementById("gridForm").method="post";
                document.getElementById("gridForm").action="' . $input['url'] . '";
                document.getElementById("gridForm").submit();
        }'),
                        )
                );    
    }

	public function getStates(){
		/*return array('AP'=>'Andhra Pradesh','AR'=>'Arunachal Pradesh','AS'=>'Assam','BR'=>'Bihar','CT'=>'Chhattisgarh','GA'=>'Goa','GJ'=>'Gujarat','HR'=>'Haryana','HP'=>'Himachal Pradesh','JK'=>'Jammu and Kashmir','JH'=>'Jharkhand','KA'=>'Karnataka','KL'=>'Kerala','MP'=>'MadhyaPradesh','MH'=>'Maharashtra','MN'=>'Manipur','ML'=>'Meghalaya','MZ'=>'Mizoram','NL'=>'Nagaland','OR'=>'Odisha','PB'=>'Punjab','RJ'=>'Rajasthan','SK'=>'Sikkim','TN'=>'Tamil Nadu','TS'=>'Telangana State','TR'=>'Tripura','UT'=>'Uttarakhand','UP'=>'Uttar Pradesh','WB'=>'West Bengal','AN'=>'Andaman and Nicobar Islands','CH'=>'Chandigarh','DN'=>'Dadra and Nagar Haveli','DD'=>'Daman and Diu','DL'=>'Delhi','LD'=>'Lakshadweep','PY'=>'Puducherry');*/

		return array('Andhra Pradesh'=>'Andhra Pradesh','Arunachal Pradesh'=>'Arunachal Pradesh','Assam'=>'Assam','Bihar'=>'Bihar','Chhattisgarh'=>'Chhattisgarh','Goa'=>'Goa','Gujarat'=>'Gujarat','Haryana'=>'Haryana','Himachal Pradesh'=>'Himachal Pradesh','Jammu and Kashmir'=>'Jammu and Kashmir','Jharkhand'=>'Jharkhand','Karnataka'=>'Karnataka','Kerala'=>'Kerala','MadhyaPradesh'=>'MadhyaPradesh','Maharashtra'=>'Maharashtra','Manipur'=>'Manipur','Meghalaya'=>'Meghalaya','Mizoram'=>'Mizoram','Nagaland'=>'Nagaland','Odisha'=>'Odisha','Punjab'=>'Punjab','Rajasthan'=>'Rajasthan','Sikkim'=>'Sikkim','Tamil Nadu'=>'Tamil Nadu','Telangana State'=>'Telangana State','Tripura'=>'Tripura','Uttarakhand'=>'Uttarakhand','Uttar Pradesh'=>'Uttar Pradesh','West Bengal'=>'West Bengal','Andaman and Nicobar Islands'=>'Andaman and Nicobar Islands','Chandigarh'=>'Chandigarh','Dadra and Nagar Haveli'=>'Dadra and Nagar Haveli','Daman and Diu'=>'Daman and Diu','Delhi'=>'Delhi','Lakshadweep'=>'Lakshadweep','Pondicherry'=>'Pondicherry');
	}

	public function getState($val){
		$states=array('Andhra Pradesh'=>'Andhra Pradesh','Arunachal Pradesh'=>'Arunachal Pradesh','Assam'=>'Assam','Bihar'=>'Bihar','Chhattisgarh'=>'Chhattisgarh','Goa'=>'Goa','Gujarat'=>'Gujarat','Haryana'=>'Haryana','Himachal Pradesh'=>'Himachal Pradesh','Jammu and Kashmir'=>'Jammu and Kashmir','Jharkhand'=>'Jharkhand','Karnataka'=>'Karnataka','Kerala'=>'Kerala','MadhyaPradesh'=>'MadhyaPradesh','Maharashtra'=>'Maharashtra','Manipur'=>'Manipur','Meghalaya'=>'Meghalaya','Mizoram'=>'Mizoram','Nagaland'=>'Nagaland','Odisha'=>'Odisha','Punjab'=>'Punjab','Rajasthan'=>'Rajasthan','Sikkim'=>'Sikkim','Tamil Nadu'=>'Tamil Nadu','Telangana State'=>'Telangana State','Tripura'=>'Tripura','Uttarakhand'=>'Uttarakhand','Uttar Pradesh'=>'Uttar Pradesh','West Bengal'=>'West Bengal','Andaman and Nicobar Islands'=>'Andaman and Nicobar Islands','Chandigarh'=>'Chandigarh','Dadra and Nagar Haveli'=>'Dadra and Nagar Haveli','Daman and Diu'=>'Daman and Diu','Delhi'=>'Delhi','Lakshadweep'=>'Lakshadweep','Puducherry'=>'Puducherry');/*array('AP'=>'Andhra Pradesh','AR'=>'Arunachal Pradesh','AS'=>'Assam','BR'=>'Bihar','CT'=>'Chhattisgarh','GA'=>'Goa','GJ'=>'Gujarat','HR'=>'Haryana','HP'=>'Himachal Pradesh','JK'=>'Jammu and Kashmir','JH'=>'Jharkhand','KA'=>'Karnataka','KL'=>'Kerala','MP'=>'MadhyaPradesh','MH'=>'Maharashtra','MN'=>'Manipur','ML'=>'Meghalaya','MZ'=>'Mizoram','NL'=>'Nagaland','OR'=>'Odisha','PB'=>'Punjab','RJ'=>'Rajasthan','SK'=>'Sikkim','TN'=>'Tamil Nadu','TS'=>'Telangana State','TR'=>'Tripura','UT'=>'Uttarakhand','UP'=>'Uttar Pradesh','WB'=>'West Bengal','AN'=>'Andaman and Nicobar Islands','CH'=>'Chandigarh','DN'=>'Dadra and Nagar Haveli','DD'=>'Daman and Diu','DL'=>'Delhi','LD'=>'Lakshadweep','PY'=>'Puducherry');*/
		return $states[$val];
	}

	public function sendMail($data = array()) {
		$fromMail=$data['from']!=""?$data['from']:Yii::app()->config->getData('CONFIG_WEBSITE_SUPPORT_EMAIL_ADDRESS');
        //$mailmessage=" Your updated password is  : ".$password;
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $header .= 'From: MNC <'.$fromMail.'>';
        $result = mail($data['to'], $data['subject'], $data['message'], $header);
		/*
		$data=array();
		$data['to']='xyz@gmail.com';
		$data['from']='from@gmail.com';//not mandatory admin reply email will work here
		$data['subject']='welcome';
		$data['message']='hello';
		Library::sendMail();
		*/	
	
	}

	public function sendMobile($data){
	
	}

	public function getCustomerType($val){
		$type=array('L'=>'Load','T'=>'Truck','G'=>'Guest','C'=>'Commission Agent','TR'=>'Transporter');
		return $type[$val]; 
	}

	public function getCustomerTypes(){
		return array('L'=>'Manufacturer','T'=>'Truck Owner','G'=>'Guest','C'=>'Commission Agent','TR'=>'Transporter');
	}

	public function getGooglePlaceDetails($place){
		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($place)."&sensor=false";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = json_decode(curl_exec($ch), true);
		return $geoloc;
		//echo "value of ".$geoloc['results']['0']['formatted_address'].'<pre>';print_r($geoloc);
	}

	public function getGPDetails($place){
		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($place)."&sensor=false";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = json_decode(curl_exec($ch), true);
		
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}
		$return=array();
		$return['input']=$place; 
		$return['status']=$geoloc['status'];
		$return['address']=$geoloc['results']['0']['formatted_address'];
		$return['country']=$formatted_addr_exp_rev[0];
		$return['state']=$formatted_addr_exp_rev[1];
		$return['city']=$formatted_addr_exp_rev[2];
		$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
		$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		return $return;
	}

	public function getCommissionMethods(){
		return array('PERSON'=>'PERSON','PERSON_TRUCK'=>'PERSON_TRUCK','PERSON_TRUCK_ROUTE'=>'PERSON_TRUCK_ROUTE','ROUTE'=>'ROUTE','POINT'=>'POINT','GLOBAL'=>'GLOBAL');
	}

	public function getLeadStatuses(){
		/*return array('Duplicate'=>'Duplicate','Initiated'=>'Initiated','Attempted to Contact'=>'Attempted to Contact','Cold'=>'Cold','Contact in Future'=>'Contact in Future','Contacted'=>'Contacted','Hot'=>'Hot','Junk Lead'=>'Junk Lead','Lost Lead'=>'Lost Lead','Not Contacted'=>'Not Contacted','Pre Qualified'=>'Pre Qualified','Qualified'=>'Qualified','Warm'=>'Warm','Request For Approval'=>'Request For Approval');*/
		return array('Call Back'=>'Call Back','Duplicate'=>'Duplicate','Initiated'=>'Initiated','Junk Lead'=>'Junk Lead','Language Barrier'=>'Language Barrier','Not Interested'=>'Not Interested','Qualified'=>'Qualified','Request For Approval'=>'Request For Approval');
	}

	public function getLeadSources(){
		return array('Marketing Team'=>'Marketing Team','Cold Call'=>'Cold Call','Existing Customer'=>'Existing Customer','Self Generated'=>'Self Generated','Employee'=>'Employee','Partner'=>'Partner','Public Relations'=>'Public Relations','Direct Mail'=>'Direct Mail','Conference'=>'Conference','Trade Show'=>'Trade Show','Website'=>'Website','Word of mouth'=>'Word of mouth','Other'=>'Other');
	}

	function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 6; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return strtolower(implode($pass)); //turn the array into a string
	}

	function getMakeYears(){
		$year=array();
		$val=date(Y);
		for($i=0;$i<20;$i++){
			$k=$val-$i;
			$year[$k]=$k;
		}
		return $year;
	}

	function getMakeMonths(){
		return array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	}

	function getExperienceYear(){
		$year=array();
		$val=date(Y);
		for($i=0;$i<51;$i++){
			$k=$val-$i;
			$year[$k]=$k;
		}
		return $year;
	}

	public function getPaymentTypes(){
		return array('80% Cash Advanced'=>'80% Cash Advanced','20% Cash Advanced'=>'20% Cash Advanced','15 Day Credit'=>'15 Day Credit','30 Day Credit'=>'30 Day Credit');
	}

	public function getLTRStatuses(){
		return array('New'=>'New','Processing Query'=>'Processing Query','Raised Quotes'=>'Raised Quotes','Booked'=>'Booked','Denied'=>'Denied','Booking Requested'=>'Booking Requested','Accepted Booking'=>'Accepted Booking');
	}


	public function getIdPrefix($data){
		$code=array("C"=>"CA","T"=>"TO","TR"=>"TR","G"=>"GT","L"=>"LO");
		return $code[$data['type']].date(y).$data['id'];
	}

	public function sendSingleSms($data){
		$username="eggadi";
		$password="eggadi@198544pwc";
		//$from=Yii::app()->params['config']['sms_from'];//"EZGADI";//;"9550462330";
		$to=$data['to'];
		//$msg=$data['message'];
		$msg=urlencode($data['message']);
		//$url="http://www.smsstriker.com/API/sms.php?username=".$username."&password=".$password."&from=".$from."&to=".$to."&msg=".$msg."&type=1 ";
		// create a new cURL resource
		//$url="https://www.satoshisms.info/api.php?user=".$username."&pass=".$password."&to=".$to."&message=".$msg;
		//$url="https://www.satoshisms.in/api.php?user=".$username."&pass=".$password."&to=".$to."&message=".$msg;
		$url="http://trans.smsfresh.co/api/sendmsg.php?user=EasyGaadi&pass=sravan@123&sender=EZGADI&phone=".$to."&text=".$msg."&priority=ndnd&stype=normal";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
	}

	public function sendSingleSms_temp($data){
		$username=Yii::app()->params['config']['sms_username'];//"easygaadi";
		$password=Yii::app()->params['config']['sms_password'];//"9550462330";
		$from=Yii::app()->params['config']['sms_from'];//"EZGADI";//;"9550462330";
		$to=$data['to'];
		//$msg=$data['message'];
		$msg=urlencode($data['message']);
		$url="http://www.smsstriker.com/API/sms.php?username=".$username."&password=".$password."&from=".$from."&to=".$to."&msg=".$msg."&type=1 ";
		// create a new cURL resource
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
	}

	public function getStateZones(){
		return array('Andhra Pradesh'=>'si','Karnataka'=>'si','Telangana State'=>'si','Tamil Nadu'=>'si','Kerala'=>'si','Sikkim'=>'ei','West Bengal'=>'ei','Bihar'=>'ei','Jharkhand'=>'ei','Odisha'=>'ei','Assam'=>'ei','Manipur'=>'ei','Meghalaya'=>'ei','Mizoram'=>'ei','Nagaland'=>'ei','Goa'=>'wi','Rajasthan'=>'wi','Gujarat'=>'wi','Maharashtra'=>'wi','Madhya Pradesh'=>'ni','Chhattisgarh'=>'ni','Arunachal Pradesh'=>'ni','Punjab'=>'ni','Jammu and Kashmir'=>'ni','Haryana'=>'ni','Himachal Pradesh'=>'ni','Uttar Pradesh'=>'ni','Uttarakhand'=>'ni','Delhi'=>'ni');
	}

	public function getSmsEmailStatus(){
		return array("0"=>"Disable","1"=>"Enable","2"=>"Only Email","3"=>"Only SMS");
	}

	public function getCustomerDocTypes(){
		return array('Pan Card'=>'Pan Card','Driving Licence'=>'Driving Licence','Voter Card'=>'Voter Card','Adhaar Card'=>'Adhaar Card','Ration Card'=>'Ration Card','Electricity Bill'=>'Electricity Bill','Bank Pass Book'=>'Bank Pass Book');
	}

	function imagePopup($file,$path){
		echo '<div id="image-name-display-id">'.$file.'<div class="logo-img"><img src="'.$path.$file.'"></div></div>';
	}

	public function getGMDistanceDetails($input){
		
		$return=0;
		if($input['source']!="" && $input['destination']!=""){
			$details_url='http://maps.googleapis.com/maps/api/distancematrix/json?origins='.urlencode($input['source']).'&destinations='.urlencode($input['destination']).'&sensor=false';
			$ch = curl_init($details_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$data = json_decode(curl_exec($ch), true);//curl_exec($ch);
			curl_close($ch);
			$return=$data[rows][0][elements][0][distance][text];
		}

		return $return;
	}

	public function getQuoteDeleteAccess(){
		return array("1","12");
	}

	public function getPreApprovalRequests(){
		return array("0"=>"New","-1"=>"Request For Approval","-2"=>"Request Denied");
	}

	public function getCLApprovalAccess(){
		return array(1,12,14);
	}
	
	public function getLeadVerificationStatuses(){
		return array('Reject Approval'=>'Reject Approval','Accept Approval'=>'Accept Approval');
	}

	public function getGroupBy(){
		return array("year"=>"Years","month"=>"Months","week"=>"Weeks","day"=>"Days");
	}

	public function getBookingComments(){
		return array("-|Late Receivable"=>"Late Receivable(-)","+|Hamali Charges"=>"Hamali Charges(+)","+|Booked Amount"=>"Booked Amount(+)","-|Commission"=>"Commission(-)","-|Goods Damage"=>"Goods Damage(-)","-|Loading Charges"=>"Loading Charges(-)","-|Payment Mamul"=>"Payment Mamul(-)","-|Unloading Charges"=>"Unloading Charges(-)","+|Loading Charges"=>"Loading Charges(+)","+|Unloading Charges"=>"Unloading Charges(+)","+|Waiting Charges"=>"Waiting Charges(+)","-|Extra Charges"=>"Extra Charges(-)","+|Extra Charges"=>"Extra Charges(+)","-|Theft"=>"Theft(-)","+|Overload Charge"=>"Overload Charge(+)","-|Deduct TDS"=>"Deduct TDS(-)");

		/*return array("+|Booked Amount"=>"Booked Amount(+)","-|Goods Damage"=>"Goods Damage(-)","+|Loading Charges"=>"Loading Charges(+)","+|Unloading Charges"=>"Unloading Charges(+)","+|Waiting Charges"=>"Waiting Charges(+)","+|Extra Charges"=>"Extra Charges(+)","-|Theft"=>"Theft(-)","+|Overload Charge"=>"Overload Charge(+)","-|Deduct TDS"=>"Deduct TDS(-)","+|Others"=>"Others(+)");*/
	}

	public function getTransactionComments(){
		return array("+|Advance Received"=>"Advance Received(+)","+|Balance Amount Received"=>"Balance Amount Received(+)","+|Advance Paid"=>"Advance Paid(+)","+|Balance Amount Paid"=>"Balance Amount Paid(+)","-|Amount Received For Damage"=>"Amount Received For Damage(-)","+|Amount Paid For Damage"=>"Amount Paid For Damage(+)","+|Others"=>"Others(+)");
	}

	public function getTransactionCommentsTO(){
		return array("+|Advance Paid"=>"Advance Paid(+)","+|Balance Amount Paid"=>"Balance Amount Paid(+)","-|Amount Received For Damage"=>"Amount Received For Damage(-)","-|Others"=>"Others(-)");
	}

	public function getTransactionCommentsLO(){
		return array("+|Advance Received"=>"Advance Received(+)","+|Balance Amount Received"=>"Balance Amount Received(+)","-|Others"=>"Others(-)");
	}
        
        public function getOrderStatusMessages(){
            return array("Mismath_TR_Price"=>"Unable to match transporter price","TO_Reject_Load"=>"Truck owner rejected load","No_Confirm_TO"=>"No Confirmation from truck owner
                after booking","No_Route_Vehicle"=>"No vehicles available as per route","TR_Rejected_Vehicle"=>"Transporter rejected the vehicle","No_Confirm_TR"=>"Transporter didn’t confirm the vehicle");

        }

		public function allowOrderUpdateForUsers(){
			$allowedUsers=array(1,9,13,29,37);
			$return=false;
			if(in_array(Yii::app()->user->id,$allowedUsers)){
				$return=true;
			}
			return $return;
		}

	/*public function getGPBYLATLNGDetails($latlng){
		//$latlng="44.4647452,7.3553838";
		//$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($place)."&sensor=false";
		$details_url="http://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = json_decode(curl_exec($ch), true);
		
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}
		$return=array();
		$return['input']=$latlng; 
		$return['status']=$geoloc['status'];
		$return['address']=$geoloc['results']['0']['formatted_address'];
		$return['country']=$formatted_addr_exp_rev[0];
		$return['state']=$formatted_addr_exp_rev[1];
		$return['city']=$formatted_addr_exp_rev[2];
		$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
		$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		return $return;
	}*/

	
	public function getGPBYLATLNGDetailsForMobile($latlng){
		//https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E&sensor=false
		//http://maps.googleapis.com/maps/api/geocode/json?latlng=17.3700,78.4800&sensor=false&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E
		//$latlng="44.4647452,7.3553838";
		//$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($place)."&sensor=false";
		//$details_url="http://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false";
		//$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E&sensor=false";
		//$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E";//sureshbabu.kokkonda@gmail.com
		$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=AIzaSyB1zw5FeLsJLOQYXEnb-CZzgpYw7N9YVv4";//mahindra.mj@gmail.com
		//$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=AIzaSyCGxZ4rJdtex-AZvnn1H5EVcy04Tq-otqI"; //santosharjun@gmail.com
		//echo $details_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$geoloc = json_decode(curl_exec($ch), true);
		
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}
		$return=array();
		//echo '<pre>';print_r($ch);print_r($geoloc);echo '</pre>';exit;
		$return['input']=$latlng; 
		$return['status']=$geoloc['status'];
		$return['address']=$geoloc['results']['0']['formatted_address'];
		$return['country']=$formatted_addr_exp_rev[0];
		$return['state']=$formatted_addr_exp_rev[1];
		$return['city']=$formatted_addr_exp_rev[2];
		$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
		$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		return $return;
	}
	
	public function getGPBYLATLNGDetails($latlng){
		//https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E&sensor=false
		//http://maps.googleapis.com/maps/api/geocode/json?latlng=17.3700,78.4800&sensor=false&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E
		//$latlng="44.4647452,7.3553838";
		//$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($place)."&sensor=false";
		$details_url="http://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false";
		//$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&key=AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E&sensor=false";
		$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=AIzaSyB1zw5FeLsJLOQYXEnb-CZzgpYw7N9YVv4";//sureshbabu.kokkonda@gmail.com
		//$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=AIzaSyCGxZ4rJdtex-AZvnn1H5EVcy04Tq-otqI"; //santosharjun@gmail.com
		
		//echo $details_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$geoloc = json_decode(curl_exec($ch), true);
		
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}
		$return=array();
		//echo '<pre>';print_r($ch);print_r($geoloc);echo '</pre>';exit;
		$return['input']=$latlng; 
		$return['status']=$geoloc['status'];
		$return['address']=$geoloc['results']['0']['formatted_address'];
		$return['country']=$formatted_addr_exp_rev[0];
		$return['state']=$formatted_addr_exp_rev[1];
		$return['city']=$formatted_addr_exp_rev[2];
		$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
		$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		return $return;
	}

		public function getSmsBalace(){
		$password=Yii::app()->params['config']['sms_password'];
		$details_url='http://www.smsstriker.com/API/get_balance.php?username=easygaadi&password='.$password;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$result=curl_exec($ch);
		return $result;
	}

	public function isMobileDND($mobile){
		//$details_url='http://www.smsstriker.com/API/get_balance.php?username=easygaadi&password=9550462330';
		$password=Yii::app()->params['config']['sms_password'];
		$details_url='http://www.smsstriker.com/API/dnd_check.php?username=easygaadi&password='.$password.'&to='.$mobile;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$result=curl_exec($ch);
		return $result;
	}

	public function sendPushNotification($data){
		//echo '<pre>';print_r($data);exit;
		// API access key from Google API's Console
		define( 'API_ACCESS_KEY', 'AIzaSyCa8wMMIngMDg6npiBAatluPenOzajm9OI' );
		//$registrationIds = array( $_GET['id'] );
		//$registrationIds = array('APA91bHwfUAdR-otMVcVmBTrTR8FmSHfRhDyGef_w9GpwW87eicZe6tT4q4zWtPXX0VUQMRSelEzXW0Rvp94vQGNBaNDlyKkRxKjL_I5njXkH8lD6caE5-KQPeTEps6-5Uhcd9dAiJW6','APA91bEml1313f3qNh7NB-Iks-f9H0Wxmo1Ql7RRLLDyo1qtJz9E47nqaqVUwVZNH4mineGVeiGO7dRSZjDzl5ZNmmBaDdUCYXHO7ac02cz9XAc3meeIdmVBK36gTKfKoEKRrmWCT2Ni');
		
		/*$registrationIds=array('APA91bE36ynNM-vzTRftC26JLKMF12gSrN8yO-9cBccYUaz9FDWJxyR6I0w_zCpHYyMhMHphUpww11r-lrKylxCtIXR7UQBBM8-3xCT37CHVIFquW3TVGfgBWYCo_qAHzeFHOMD7gGLj','APA91bER4-sIcSmLsc5XXW7rulox3t_jkRhC1HH0PCOU5E10_8RlLMrFpusn9exAkXZeTu8zO-euFp8pPr23g5KNuBpbxibUTMgvxyLRhlnQu5MRVqUroW_1633Prg96yW2uY4dnb4zW','APA91bH1zpZgxCM2YYvipnWK39KIxwNO3w-fvrqSWPw4ZYaDqqYq1gWzxz6cLfRQhqLjjtU1uX4Z-olYcapMEaYvv97DLecN8wZmWYVLjXX4WKkKcl0UDyLAdsdDLNpuKwpbRq5OW39r','APA91bHiCJSIZZn9Tki7m4PAXIPZfPbenpABz3YzVfOpoTqTr4KXAye4iWXUeodr2fglIZpq82NaEldu3zqMCfRrSYj6ABSC2hBsVOWQ6Zg8WD7gtkogYbwM7_EGNB0WSJUKtV7V0DVR','APA91bFAJ1OmcMRVL2UzsX737dpMCyWzQjFF6psnKcOkMUuji6G_zZneNMDwjCMss9Nr1u6MRVnuAmnJWdIUIoa2I7S58whc6QO-e5wlohDfEClwAlgT_DjSRC4oB0HjGxiTzTnXmAlK','APA91bER4-sIcSmLsc5XXW7rulox3t_jkRhC1HH0PCOU5E10_8RlLMrFpusn9exAkXZeTu8zO-euFp8pPr23g5KNuBpbxibUTMgvxyLRhlnQu5MRVqUroW_1633Prg96yW2uY4dnb4zW','APA91bEPKnTYW4EehtDiRtvsV1fJgE08C5ZdPP7I7o-zmQ6TOQ4pJCXUO_4CBbMoxPLCzyRmSY3hS_a-b4ab0QNWn81SMk0RT6iTsw3PMzIEsqpkoALRXTb-yoDviK1y9FEs052FSqwt');*/
		//$registrationIds =array('APA91bH0gHjQMhlIq8CpX2SOPIYbEOvU-sHMLYIqAN-UUllvqzZ5u41f910D_g1-PRK0GR2Cxn4MnuIwuDpz9-7KFMHQFnk725k1StEsBwJnSYoa_uqo-1P-BzzAH6ui6DvyGmHpUI01');
		$registrationIds=array_unique($data['devices']);
		
		// prep the bundle
		$msg = array
		(
			//'message' 	=> 'here is a message. message',
			//'message' 	=> $data['message'],
			'message' 	=> json_encode($data['message']),
			'title'		=> 'This is a title. title',
			'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
			'vibrate'	=> 0,
			'sound'		=> 0,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		//echo '<pre>';print_r($registrationIds);exit;
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return  $result;
	}

	public function sendPushNotificationDCApp($data){
		//echo '<pre>';print_r($data);exit;
		// API access key from Google API's Console
		//define( 'API_ACCESS_KEY', 'AIzaSyCa8wMMIngMDg6npiBAatluPenOzajm9OI' );
		define( 'API_ACCESS_KEY', 'AIzaSyAHqZz4r7QM3tRRxvgULC9w0NwSrcGiyqU' ); //DCapp app
		$registrationIds=array_unique($data['devices']);
		//$registrationIds=array('celJS50yBJ4:APA91bGIa1JKgg9REiQ-qEooYxVd10c9yvTuIlcDAlb2RgOrtgbUDEGWAPc_MmUXGvUPY6DQquy2YYRL0CRw7cSdaMNXvzLeXbl_aTghMYFSCJo0FR8Bry2A2z0k23H5Sg89JF09hIno');
		// prep the bundle
		$msg = array
		(
			//'message' 	=> 'here is a message. message',
			'message' 	=> $data['message'],
			//'message' 	=> json_encode($data['message']),
			'title'		=> 'This is a title. title',
			'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);

		//echo '<pre>';print_r($registrationIds);exit;
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return  $result;
	}

	public function getTruckSellUploadPath()
    {
        return Yii::app()->params['config']['document_root'].Yii::app()->params['config']['upload_path'].'trucks_sell/';
    }
    
    public function getTruckSellUploadLink()
    {
        return Yii::app()->params['config']['site_url'].Yii::app()->params['config']['upload_path'].'trucks_sell/';
    }

	function getPlanMonths(){
		return array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12');
	}

	public function getGPBYLATLNGDetailsCloud($latlng){
		$exp=explode(",",$latlng);
		$password=Yii::app()->params['config']['sms_password'];
		$details_url='http://egnom.cloudapp.net/nominatim/reverse?format=json&lat='.$exp[0].'&lon='.$exp[1];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		//$result=curl_exec($ch);
		$geoloc = json_decode(curl_exec($ch), true);
		//echo '<pre>';	print_r($geoloc);echo '</pre>';
		$return=array();
		//echo '<pre>';print_r($ch);print_r($geoloc);echo '</pre>';exit;
		$return['input']=$latlng; 
		//$return['status']=$geoloc['status'];

		$addr=array();
		$addr=$geoloc['address'];
		unset($addr['county']);
		unset($addr['country']);
		unset($addr['country_code']);
		unset($addr['postcode']);
		/*$addr[$geoloc['address']['road']]=$geoloc['address']['road'];
		$addr[$geoloc['address']['neighbourhood']]=$geoloc['address']['neighbourhood'];
		$addr[$geoloc['address']['suburb']]=$geoloc['address']['suburb'];
		$addr[$geoloc['address']['town']]=$geoloc['address']['town'];
		$addr[$geoloc['address']['city']]=$geoloc['address']['city'];
		$addr[$geoloc['address']['state_district']]=$geoloc['address']['state_district'];
		$addr[$geoloc['address']['state']]=$geoloc['address']['state'];*/
		//echo implode(",",$addr).'<pre>';print_r($geoloc);print_r($addr);exit;
		//$return['address']=implode(",",$addr);
		
		$imp_addr=implode(",",$addr);
		$addr=str_replace("Unnamed Road,","",$imp_addr);
		$return['address']=$addr;
		
		$return['country']=$geoloc['address']['country'];
		$return['state']=$geoloc['address']['state'];
		$return['city']=$geoloc['address']['city'];
		$return['town']=$geoloc['address']['town'];
		$return['state_district']=$geoloc['address']['state_district'];
		$return['lat']=$geoloc['lat'];
		$return['lng']=$geoloc['lon'];
		$return['postcode']=$geoloc['address']['postcode'];
		$return['county']=$geoloc['address']['county'];
		$return['road']=$geoloc['address']['road'];
		$return['neighbourhood']=$geoloc['address']['neighbourhood'];
		$return['suburb']=$geoloc['address']['suburb'];
		//echo '<pre>';print_r($geoloc);print_r($return);echo '</pre>';exit;
		return $return;
		//return $result;
	}
	public function getOrderUploadPath()
    {
        return Yii::app()->params['config']['document_root'].Yii::app()->params['config']['upload_path'].'order/';
    }
    
    public function getOrderUploadLink()
    {
        return Yii::app()->params['config']['site_url'].Yii::app()->params['config']['upload_path'].'order/';
    }

	public function getBanks(){
		return array("Axis"=>"Axis","HDFC"=>"HDFC","ICICI"=>"ICICI");
	}

	public function trimToLower($field){
        $return=trim($field); //trim
        $return=str_replace(" ","",$return);         //replace empty space 
        $return=  strtolower($return); //change to lower case
        return $return;
    }

	public function getManufacturers(){
		return array("Mahindra"=>"Mahindra","Eicher"=>"Eicher","Tata"=>"Tata","Ashok Leyland"=>"Ashok Leyland");
	}

	public function getGoogleMapsKey(){
		return "AIzaSyDSpZNiIF6ZkICFqDJtz2uoS7nJAMzJLCM";
		//AIzaSyDSpZNiIF6ZkICFqDJtz2uoS7nJAMzJLCM sureshbabu.kokkonda@gmail.com
	}
	
	public function getGPBYLATLNGDetailsGoogle($latlng){
		
		$t=(date('H')*60)+date('i');
		$keyarray[]='AIzaSyB1zw5FeLsJLOQYXEnb-CZzgpYw7N9YVv4';
		//echo $t." value of ".date('H')." ".date('i');
	switch (true) {
		case  ($t>=1380 && $t<1440):  //11pm-12am
			$keyarray[]='AIzaSyB2Jb-Gf7zva77rqWI4W-Vixwr4HWSbEOc';//easygaadi34@gmail.com
			$keyarray[]='AIzaSyD8hU1DfIA1Q4xK56NOj5iFsmMFZxwRk5I';//easygaadi37@gmail.com
			$keyarray[]='AIzaSyCUKNepSHND5KsKtWwEKaOz2Tu8hbqWBgg';//easygaadi38@gmail.com
		break;
		
		case  ($t>=0 && $t<120):  //12am-2am
			$keyarray[]='AIzaSyCUKNepSHND5KsKtWwEKaOz2Tu8hbqWBgg';//easygaadi38@gmail.com
			$keyarray[]='AIzaSyAd2-z8VnLoJIDbmnzsiEQYMdJhgYJ1sS0';//easygaadi35@gmail.com
		break;
		
		case  ($t>=120 && $t<240):  //2am-4am
			$keyarray[]='AIzaSyBhIH6BllyDLxAqjh3pjxZd39pNeL_eF6s';//easygaadi36@gmail.com
			$keyarray[]='AIzaSyBhW1w_GxjZIdN6BtbrZI9xwPEQzeIPp70';//easygaadi39@gmail.com
		break;
		
		case  ($t>=240 && $t<360):  //4am-6am
			$keyarray[]='AIzaSyA4JLtaAiuC0DUIZ_J8uvqBhuMyPhICLTE';//easygaaadi40@gmail.com
			$keyarray[]='AIzaSyDmVosL1gRflNRE_YGGKts-20vfXNyqWes';//easygaadi41@gmail.com
		break;

		case  ($t>=360 && $t<480):  //6am-8am
			$keyarray[]='AIzaSyA2kEPrugsp5mwkbHfK3LDYUiiauJwR6IQ';//easygaadi001@gmail.com 
			$keyarray[]='AIzaSyCdSrzv1hxzEAK5V7sLnH3y_TIxg7wDrwE';//easygaadi002@gmail.com
			$keyarray[]='AIzaSyCuVzvCwJfOxYhCv-a_FxSuW8HoVIo2mEM';//easygaadi42@gmail.com
			$keyarray[]='AIzaSyCDqgoGwKN33cnNEOzRDft4ep0ed1b89wQ';//easygaadi21@gmail.com
		break;

		case  ($t>=480 && $t<540):  //8am-9am
			$keyarray[]='AIzaSyCDqgoGwKN33cnNEOzRDft4ep0ed1b89wQ';//easygaadi21@gmail.com
			$keyarray[]='AIzaSyAk1YG58P7NB3eHQ5teUXYSWWfntBF3CXw';//easygaadi24@gmail.com
			$keyarray[]='AIzaSyBFryKZ-5rIk7I7TyZ89a0KrhOjeypFjfM';//easygaadi44@gmail.com
			$keyarray[]='AIzaSyDoXYZAnYABhLrg4ZDk1YCQbA-kCsg6d-I';//easygaadi27@gmail.com
		break;

		case  ($t>=540 && $t<600):  //9am-10am
			$keyarray[]='AIzaSyDoXYZAnYABhLrg4ZDk1YCQbA-kCsg6d-I';//easygaadi27@gmail.com
			$keyarray[]='AIzaSyB6iBOmTft_fKuB7f-0a6fhMdj199uT6uA';// - easygaadi008@gmail.com
			$keyarray[]='AIzaSyBvXJmUQS1RLxHj23olw0CALDCrq9Z0PpI';// - easygaadi009@gmail.com
		break;

		case  ($t>=600 && $t<660):  //10am-11am
			$keyarray[]='AIzaSyBvXJmUQS1RLxHj23olw0CALDCrq9Z0PpI';// - easygaadi009@gmail.com
			$keyarray[]='AIzaSyBelJz9-yw46cp78-UvaEGhzi8jA4c-JfQ';// - easygaadi10@gmail.com
			$keyarray[]='AIzaSyAIdDAdqg6_F-7eS_a-M8TnGMc642Z2uPk';//easygaadi46@gmail.com
			$keyarray[]='AIzaSyCwYpYffxQe_lDI7PxN9GUPlyKJUQ-CUO4';// - easygaadi17@gmail.com
		break;

		case  ($t>=660 && $t<720):  //11am-12pm
					$keyarray[]='AIzaSyBhIH6BllyDLxAqjh3pjxZd39pNeL_eF6s';//easygaadi36@gmail.com
			$keyarray[]='AIzaSyBhW1w_GxjZIdN6BtbrZI9xwPEQzeIPp70';//easygaadi39@gmail.com
			$keyarray[]='AIzaSyCwYpYffxQe_lDI7PxN9GUPlyKJUQ-CUO4';// - easygaadi17@gmail.com
			$keyarray[]='AIzaSyBVN5oiavvpNBB1_lmLXtLYA1Y2RR1tYtM';// - easygaadi18@gmail.com
			$keyarray[]='AIzaSyBjpEGA_4eg4wzNQh_nMe1g4awDWYMO6pE';// -  easygaadi19@gmail.com
		break;
		
		case  ($t>=720 && $t<780):  //12pm-1pm
			$keyarray[]='AIzaSyBjpEGA_4eg4wzNQh_nMe1g4awDWYMO6pE';// -  easygaadi19@gmail.com
			$keyarray[]='AIzaSyCB0uCARszMAovXxzUstmZBysTuWtAEZFA';//easygaadi47@gmail.com
			$keyarray[]='AIzaSyDWhEC0B_BRWkRKKctppL8eNP2inr2kRbY';// - easygaadi11@gmail.com
			$keyarray[]='AIzaSyB30HDD7kJhgrsyg0JjlsjUURRjikJylMA';// - easygaadi14@gmail.com
		break;

		case  ($t>=780 && $t<840):  //1pm-2pm
			$keyarray[]='AIzaSyB30HDD7kJhgrsyg0JjlsjUURRjikJylMA';// - easygaadi14@gmail.com
			$keyarray[]='AIzaSyAHGo13K7lKqgoXgmsmLeyhRiHnE2Or-Bw';// - easygaadi007@gmail.com
			$keyarray[]='AIzaSyAcezj-r0GrFMoDeVsSGlytphXTdQeGMN8';// - easygaadi003@gmail.com
		break;
		case  ($t>=840 && $t<900):  //2pm-3pm
			$keyarray[]='AIzaSyAcezj-r0GrFMoDeVsSGlytphXTdQeGMN8';// - easygaadi003@gmail.com
			$keyarray[]='AIzaSyDKeqxP6jr5VBgOu2f6Ju2ufZYx3b1T-xg';//easygaadi48@gmail.com
			$keyarray[]='AIzaSyCXldy85sJDAeLBoyjHVyinvZzUBiuAew4';// - easygaadi004@gmail.com
			$keyarray[]='AIzaSyBbFUTMr_UuOH1zCqF6lMMJeWIg7zyNHKc';// - easygaadi005@gmail.com
		break;

		case  ($t>=900 && $t<960):  //3pm-4pm
			$keyarray[]='AIzaSyBbFUTMr_UuOH1zCqF6lMMJeWIg7zyNHKc';// - easygaadi005@gmail.com
			$keyarray[]='AIzaSyC57f77D1gQC2u0sU1Wx3YJlZjLq4Cm1bw';// - easygaadi12@gmail.com
			$keyarray[]='AIzaSyBg7sDLD08foapLP_T68CMA7lZx6MNSlFY';// - easygaadi13@gmail.com
		break;
		
		case  ($t>=960 && $t<1020):  //4pm-5pm
			$keyarray[]='AIzaSyBg7sDLD08foapLP_T68CMA7lZx6MNSlFY';// - easygaadi13@gmail.com
			$keyarray[]='AIzaSyCJENSw_HHrkaa0DP24wJDs4jiOmzlQv1I';//easygaadi49@gmail.com
			$keyarray[]='AIzaSyCD5AvEnBA_r_LGw9JZa4XClvvMfD8AFj4';// - easygaadi15@gmail.com
			$keyarray[]='AIzaSyAD4j1nEgfIeNDFR2ImafB2gw-R7gkB98M';// - easygaadi16@gmail.com
		break;

		case  ($t>=1020 && $t<1080):  //5pm-6pm
			$keyarray[]='AIzaSyAD4j1nEgfIeNDFR2ImafB2gw-R7gkB98M';// - easygaadi16@gmail.com
			$keyarray[]='AIzaSyAI7EOGIr8nEX5-0w8nLUf8wfD8_4bKngA';//easygaadi30@gmail.com
			$keyarray[]='AIzaSyAnr3ynwrsnGSAQawbxUvaiV881VWSD58k';//easygaadi31@gmail.com
		break;

		case  ($t>=1080 && $t<1140):  //6pm-7pm
			$keyarray[]='AIzaSyAnr3ynwrsnGSAQawbxUvaiV881VWSD58k';//easygaadi31@gmail.com
			$keyarray[]='AIzaSyDjdiOHU5Z28WAwkGIcWC-xzZSVXgXyVlc';//easygaadi32@gmail.com
			$keyarray[]='AIzaSyAbyuWKMNXo3GDpDg_Ky4j2MPhY68o90ao';//easygaadi51@gmail.com
			$keyarray[]='AIzaSyDk-esougL2gJFiSFpgXIzyHFPfjyZ6mhU';//easygaadi25@gmail.com
		break;

		case  ($t>=1140 && $t<1200):  //7pm-8pm
			$keyarray[]='AIzaSyDk-esougL2gJFiSFpgXIzyHFPfjyZ6mhU';//easygaadi25@gmail.com
			$keyarray[]='AIzaSyDOvanLNJIFwEUSfMPmCu94ZlbD9GGgz6I';//easygaadi22@gmail.com
			$keyarray[]='AIzaSyC9k71aUt4X86jSGkNuBYsVHtjzr5QqqM4';//easygaadi23@gmail.com
		break;

		case  ($t>=1200 && $t<1260):  //8pm-9pm
			$keyarray[]='AIzaSyC9k71aUt4X86jSGkNuBYsVHtjzr5QqqM4';//easygaadi23@gmail.com
			$keyarray[]='AIzaSyBgTQsqfoB9bUD4FDS5UQ0nC13Xrs7b_z8';//easygaadi26@gmail.com;
			$keyarray[]='AIzaSyATnORDmJI0CH4yLFD1bXZJjST4IMeGpe0';//easygaadi52@gmail.com
			$keyarray[]='AIzaSyDhjU3D5PQnUYDgVJU8-Q-YFy3n7a2U5No';//easygaadi29@gmail.com;
		break;

		case  ($t>=1260 && $t<1320):  //9pm-10pm
			$keyarray[]='AIzaSyDhjU3D5PQnUYDgVJU8-Q-YFy3n7a2U5No';//easygaadi29@gmail.com;
			$keyarray[]='AIzaSyBHAb-SKD-1IqlzRlETENKOQhdNxJ0m430';//easygaadi43@gmail.com
			$keyarray[]='AIzaSyBsye7EGxr-ZT3g_ixRPKcAghWUogGzoiA';//easygaadi28@gmail.com
		break;

		case  ($t>=1320 && $t<1380):  //10pm-11pm
			$keyarray[]='AIzaSyCGxZ4rJdtex-AZvnn1H5EVcy04Tq-otqI';//santosharjun@gmail.com
			$keyarray[]='AIzaSyBt5knl_tmKgcqmZiPqWZH_EV5fUWv6FyQ';//easygaadi54@gmail.com
			$keyarray[]='AIzaSyC2kqVASiPcNKDyOzplhdKjqPXQqnxXw2E';//sureshbabu.kokkonda@gmail.com
			$keyarray[]='AIzaSyDjXGtInKkbAXKR-WkzTsB-DftN_mCctd8';//mahindra.mj@gmail.com
		break;
		
		default:
			$keyarray[]='AIzaSyBmGfYcxj3CrlJFr7hPQ3sUWR0Q-yJ1W54';//easygaadi45@gmail.com
	}
		
		//echo $t.'<pre>';print_r($keyarray);echo '</pre>';
		$ch = curl_init();
		foreach($keyarray as $key){
		$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=".$key;//mahindra.mj@gmail.com
		
		//echo $details_url;
		//$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$geoloc = json_decode(curl_exec($ch), true);
			if($geoloc['results']['0']['formatted_address']!=""){
				//echo 'worked'.$key;
				break;
			}
		}
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}

		if($geoloc['results']['0']['formatted_address']==''){
			$return=Library::getGPBYLATLNGDetailsCloud($latlng);
		}else{
			$return=array();
			//echo '<pre>';print_r($ch);print_r($geoloc);echo '</pre>';exit;
			$return['input']=$latlng; 
			$return['status']=$geoloc['status'];
			$return['address']=str_replace("Unnamed Road, ","",$geoloc['results']['0']['formatted_address']);
			$return['country']=$formatted_addr_exp_rev[0];
			$return['state']=$formatted_addr_exp_rev[1];
			$return['city']=$formatted_addr_exp_rev[2];
			$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
			$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		}
		return $return;
	}

	public function getGPBYLATLNGDetailsGoogle_feb_23_2017($latlng){
		/*AIzaSyA2kEPrugsp5mwkbHfK3LDYUiiauJwR6IQ - easygaadi001@gmail.com 
AIzaSyCdSrzv1hxzEAK5V7sLnH3y_TIxg7wDrwE - easygaadi002@gmail.com
AIzaSyAcezj-r0GrFMoDeVsSGlytphXTdQeGMN8 - easygaadi003@gmail.com
AIzaSyCXldy85sJDAeLBoyjHVyinvZzUBiuAew4 - easygaadi004@gmail.com
AIzaSyBbFUTMr_UuOH1zCqF6lMMJeWIg7zyNHKc - easygaadi005@gmail.com
AIzaSyDWhEC0B_BRWkRKKctppL8eNP2inr2kRbY - easygaadi11@gmail.com
AIzaSyC57f77D1gQC2u0sU1Wx3YJlZjLq4Cm1bw - easygaadi12@gmail.com
AIzaSyBg7sDLD08foapLP_T68CMA7lZx6MNSlFY - easygaadi13@gmail.com
AIzaSyB30HDD7kJhgrsyg0JjlsjUURRjikJylMA - easygaadi14@gmail.com
AIzaSyCD5AvEnBA_r_LGw9JZa4XClvvMfD8AFj4 - easygaadi15@gmail.com
AIzaSyAD4j1nEgfIeNDFR2ImafB2gw-R7gkB98M - easygaadi16@gmail.com
AIzaSyAHGo13K7lKqgoXgmsmLeyhRiHnE2Or-Bw - easygaadi007@gmail.com
AIzaSyAD9b6iNH1DhCRZzoeYaNBfdaQGhQFcXLM - easygaadi006@gmail.com
AIzaSyB6iBOmTft_fKuB7f-0a6fhMdj199uT6uA - easygaadi008@gmail.com
AIzaSyBvXJmUQS1RLxHj23olw0CALDCrq9Z0PpI - easygaadi009@gmail.com
AIzaSyBelJz9-yw46cp78-UvaEGhzi8jA4c-JfQ - easygaadi10@gmail.com
AIzaSyCwYpYffxQe_lDI7PxN9GUPlyKJUQ-CUO4 - easygaadi17@gmail.com
AIzaSyBVN5oiavvpNBB1_lmLXtLYA1Y2RR1tYtM - easygaadi18@gmail.com
AIzaSyBjpEGA_4eg4wzNQh_nMe1g4awDWYMO6pE -  easygaadi19@gmail.com*/
		$keyarray=array('AIzaSyBg7sDLD08foapLP_T68CMA7lZx6MNSlFY','AIzaSyB30HDD7kJhgrsyg0JjlsjUURRjikJylMA','AIzaSyCD5AvEnBA_r_LGw9JZa4XClvvMfD8AFj4','AIzaSyB1zw5FeLsJLOQYXEnb-CZzgpYw7N9YVv4','AIzaSyAD4j1nEgfIeNDFR2ImafB2gw-R7gkB98M','AIzaSyAHGo13K7lKqgoXgmsmLeyhRiHnE2Or-Bw','AIzaSyAD9b6iNH1DhCRZzoeYaNBfdaQGhQFcXLM','AIzaSyB6iBOmTft_fKuB7f-0a6fhMdj199uT6uA','AIzaSyBvXJmUQS1RLxHj23olw0CALDCrq9Z0PpI','AIzaSyBelJz9-yw46cp78-UvaEGhzi8jA4c-JfQ','AIzaSyCwYpYffxQe_lDI7PxN9GUPlyKJUQ-CUO4','AIzaSyBVN5oiavvpNBB1_lmLXtLYA1Y2RR1tYtM','AIzaSyBjpEGA_4eg4wzNQh_nMe1g4awDWYMO6pE','AIzaSyCGxZ4rJdtex-AZvnn1H5EVcy04Tq-otqI','AIzaSyA2kEPrugsp5mwkbHfK3LDYUiiauJwR6IQ','AIzaSyCdSrzv1hxzEAK5V7sLnH3y_TIxg7wDrwE','AIzaSyAcezj-r0GrFMoDeVsSGlytphXTdQeGMN8','AIzaSyCXldy85sJDAeLBoyjHVyinvZzUBiuAew4','AIzaSyBbFUTMr_UuOH1zCqF6lMMJeWIg7zyNHKc','AIzaSyDWhEC0B_BRWkRKKctppL8eNP2inr2kRbY','AIzaSyC57f77D1gQC2u0sU1Wx3YJlZjLq4Cm1bw');

		foreach($keyarray as $key){
		$details_url="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($latlng)."&sensor=false&key=".$key;//mahindra.mj@gmail.com
		
		//echo $details_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		$geoloc = json_decode(curl_exec($ch), true);
			if($geoloc['results']['0']['formatted_address']!=""){
				break;
			}
		}
		$formatted_addr=$geoloc['results']['0']['formatted_address'];
		if($formatted_addr!=""){
			$formatted_addr_exp=explode(", ",$formatted_addr);
			$formatted_addr_exp_rev=array_reverse($formatted_addr_exp);
		}
		$return=array();
		//echo '<pre>';print_r($ch);print_r($geoloc);echo '</pre>';exit;
		$return['input']=$latlng; 
		$return['status']=$geoloc['status'];
		$return['address']=str_replace("Unnamed Road, ","",$geoloc['results']['0']['formatted_address']);
		$return['country']=$formatted_addr_exp_rev[0];
		$return['state']=$formatted_addr_exp_rev[1];
		$return['city']=$formatted_addr_exp_rev[2];
		$return['lat']=$geoloc['results']['0']['geometry']['location']['lat'];
		$return['lng']=$geoloc['results']['0']['geometry']['location']['lng'];
		return $return;
	}
}
