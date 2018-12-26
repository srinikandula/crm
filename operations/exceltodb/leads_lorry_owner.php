<?php
//ini_set("display_errors",1);
require_once 'excel_reader2.php';
require_once 'db.php';
$id_admin=38;
//38 maheshwari
//39 vaishnavi
/*$countMobile=mysqli_fetch_array(mysqli_query($connection,"select count(*) as total from eg_customer where mobile like '9848750094'"));
$insertFlag=$countMobile[total];
exit("value of ".$insertFlag);*/
$data = new Spreadsheet_Excel_Reader("lorrydb_mah11.xls");

echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";
exit;
$html="<table border='1'>";
for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
{	
	if(count($data->sheets[$i][cells])>0) // checking sheet not empty
	{
		echo "Sheet $i:<br /><br />Total rows in sheet $i  ".count($data->sheets[$i][cells])."<br />";
		for($j=1;$j<=count($data->sheets[$i][cells]);$j++) // loop used to get each row of the sheet
		{ 
			$html.="<tr>";
			for($k=1;$k<=count($data->sheets[$i][cells][$j]);$k++) // This loop is created to get data in a table format.
			{
				$html.="<td>";
				$html.=$data->sheets[$i][cells][$j][$k];
				$html.="</td>";
			}
			$data->sheets[$i][cells][$j][1];
			if($data->sheets[$i][cells][$j][2]==""){ continue;}//mobile null then ignore
			$name = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][1]);
			$mobile = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][2]));
			//$trucktype = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][3]);
			//$nooftrucks = (int)mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][4]);
			$address = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][3]);
			//$alt_mobile_1 = mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][6]);
			
			$expMobile=explode("/",$mobile);
			$mainMobile=trim($expMobile[0]);
			$alt_mobile_1=trim($expMobile[1]);
			$alt_mobile_2=trim($expMobile[2]);
			$alt_mobile_3=trim($expMobile[3]);

			if($mainMobile!=""){
				$countMobile=mysqli_fetch_array(mysqli_query($connection,"select count(*) as total from eg_customer where type!='G' and mobile like '".$mainMobile."'"));
				$insertFlag=$countMobile[total];
			}else{
				continue;
				$insertFlag=1;
			}

			if(!$insertFlag){
			$query_eg_customer = "insert into eg_customer(type,fullname,mobile,no_of_vechiles,address,alt_mobile_1,alt_mobile_2,alt_mobile_3,date_created) values('T','".$name."','".$mobile."','".$nooftrucks."','".$address."','".$alt_mobile_1."','".$alt_mobile_2."','".$alt_mobile_3."','".date('Y-m-d')."')";
			mysqli_query($connection,$query_eg_customer);
			
			$id_customer=mysqli_insert_id($connection);
			
			//trucktype
			/*if($trucktype!=""){
				$exp=explode(",",$trucktype);
				foreach($exp as $v){
					if((int)$trucktypeidArray[$v]){
						$query_eg_customer_vechile_types="insert into eg_customer_vechile_types(id_customer,title,tonnes,id_truck_type) values('".$id_customer."','".$title."','".$v."','".$trucktypeidArray[$v]['id_truck_type']."')";
			mysqli_query($connection,$query_eg_customer_vechile_types);		
					}		
				}
			}*/
			
			$query_eg_customer_lead="insert into eg_customer_lead(id_admin_created,lead_status,lead_source,id_customer) values('".$id_admin."','Initiated','Marketing Team','".$id_customer."')";
			mysqli_query($connection,$query_eg_customer_lead);
			
			$query_eg_customer_access_history="insert into eg_customer_access_history(id_admin,message,id_customer) values('".$id_admin."','Created Lead','".$id_customer."')";
			mysqli_query($connection,$query_eg_customer_access_history);

			$query_eg_customer_access_permission="insert into eg_customer_access_permission(date_created,id_admin,id_customer) values('".date('Y-m-d')."','".$id_admin."','".$id_customer."')";
			mysqli_query($connection,$query_eg_customer_access_permission);	
			}
						
			//exit;
			$html.="</tr>";
		}
	}
	
}

$html.="</table>";
echo $html;
echo "<br />Data Inserted in dababase";
?>