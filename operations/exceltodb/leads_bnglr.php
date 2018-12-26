<?php
ini_set("display_errors",1);
require_once 'excel_reader2.php';
require_once 'db.php';
/*$trucktypeidArray=Array
(
    1 => array("id"=>8,"title"=>"Tata Ace-1 Ton"),
    2 => array("id"=>9,"title"=>"Tata 407- 3 Ton"),
    4 => array("id"=>10,"title"=>"14 Feet DCM"),
    5 => array("id"=>12,"title"=>"17 Feet DCM"),
    7 => array("id"=>29,"title"=>"30 Feet Single Axle"),
    10 => array("id"=>16,"title"=>"6 Tyres-10 Ton"),
    16 => array("id"=>41,"title"=>"Oil Tanker"),
    21 => array("id"=>18,"title"=>"12 Tyre-21 Ton"),
    25 => array("id"=>38,"title"=>"14 Tyres 25Ton"),
    20 => array("id"=>26,"title"=>"40 Feet High Bed"),
    14 => array("id"=>42,"title"=>"Tanker"),
    6 => array("id"=>36,"title"=>"32 Feet Single Axle-"),
    40 => array("id"=>37,"title"=>"60 Feet Trailer"),
    35 => array("id"=>40,"title"=>"22 Tyres 35 Ton")
);*/
exit;
$id_admin=38;
$data = new Spreadsheet_Excel_Reader("leads_mah_bnglr11.xls");

echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";

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
			$name = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][1]));
			$mobile = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][2]));
			$trucktype = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][3]));
			$nooftrucks = (int)mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][4]);
			$address = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][5]));
			$alt_mobile_1 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][6]));
			$customer_type = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][7]));
			$landline = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][8]));
			$email = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][9]));
			$from_loc="Bangalore";
			$loc1 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][11]));
			$loc2 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][12]));
			$loc3 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][13]));
			$loc4 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][14]));
			$loc5 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][15]));
			$loc6 = trim(mysqli_real_escape_string($connection,$data->sheets[$i][cells][$j][16]));
			$query_eg_customer = "insert into eg_customer(fullname,mobile,no_of_vechiles,address,alt_mobile_1,date_created,city,state,type,email,landline) values('".$name."','".$mobile."','".$nooftrucks."','".$address."','".$alt_mobile_1."','".date('Y-m-d')."','Bangalore','Karnataka','".$customer_type."','".$email."','".$landline."')";
			mysqli_query($connection,$query_eg_customer);
			
			$id_customer=mysqli_insert_id($connection);
			
			if($loc1!=""){
				$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$loc1."','".$loc1."')";
				mysqli_query($connection,$query_eg_or);
			}

			if($loc2!=""){
				$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$loc2."','".$loc2."')";
				mysqli_query($connection,$query_eg_or);
			}

			if($loc3!=""){
				$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$loc3."','".$loc3."')";
				mysqli_query($connection,$query_eg_or);
			}

			if($loc4!=""){
				$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$loc4."','".$loc4."')";
				mysqli_query($connection,$query_eg_or);
			}

			if($loc5!=""){
				$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$loc5."','".$loc5."')";
				mysqli_query($connection,$query_eg_or);
			}

			if($loc6!=""){
				$explocs=explode(",",$loc6);
				foreach($explocs as $exploc){
					$query_eg_or="insert into eg_customer_operating_destinations(id_customer,source_address,source_city,destination_address,destination_city) values('".$id_customer."','".$from_loc."','".$from_loc."','".$exploc."','".$exploc."')";
					mysqli_query($connection,$query_eg_or);
				}
			}

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
						
			//exit;
			$html.="</tr>";
		}
	}
	
}

$html.="</table>";
echo $html;
echo "<br />Data Inserted in dababase";
?>