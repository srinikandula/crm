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

		return array('Andhra Pradesh'=>'Andhra Pradesh','Arunachal Pradesh'=>'Arunachal Pradesh','Assam'=>'Assam','Bihar'=>'Bihar','Chhattisgarh'=>'Chhattisgarh','Goa'=>'Goa','Gujarat'=>'Gujarat','Haryana'=>'Haryana','Himachal Pradesh'=>'Himachal Pradesh','Jammu and Kashmir'=>'Jammu and Kashmir','Jharkhand'=>'Jharkhand','Karnataka'=>'Karnataka','Kerala'=>'Kerala','MadhyaPradesh'=>'MadhyaPradesh','Maharashtra'=>'Maharashtra','Manipur'=>'Manipur','Meghalaya'=>'Meghalaya','Mizoram'=>'Mizoram','Nagaland'=>'Nagaland','Odisha'=>'Odisha','Punjab'=>'Punjab','Rajasthan'=>'Rajasthan','Sikkim'=>'Sikkim','Tamil Nadu'=>'Tamil Nadu','Telangana State'=>'Telangana State','Tripura'=>'Tripura','Uttarakhand'=>'Uttarakhand','Uttar Pradesh'=>'Uttar Pradesh','West Bengal'=>'West Bengal','Andaman and Nicobar Islands'=>'Andaman and Nicobar Islands','Chandigarh'=>'Chandigarh','Dadra and Nagar Haveli'=>'Dadra and Nagar Haveli','Daman and Diu'=>'Daman and Diu','Delhi'=>'Delhi','Lakshadweep'=>'Lakshadweep','Puducherry'=>'Puducherry');
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
		return array('L'=>'Load','T'=>'Truck','G'=>'Guest','C'=>'Commission Agent','TR'=>'Transporter');
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
		return array('Initiated'=>'Initiated','Attempted to Contact'=>'Attempted to Contact','Cold'=>'Cold','Contact in Future'=>'Contact in Future','Contacted'=>'Contacted','Hot'=>'Hot','Junk Lead'=>'Junk Lead','Lost Lead'=>'Lost Lead','Not Contacted'=>'Not Contacted','Pre Qualified'=>'Pre Qualified','Qualified'=>'Qualified','Warm'=>'Warm');
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
		return implode($pass); //turn the array into a string
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
		for($i=0;$i<20;$i++){
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
}