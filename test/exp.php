<?php
exit;
/*******EDIT LINES 3-8*******/
$DB_Server = "localhost"; //MySQL Server    
$DB_Username = "root"; //MySQL Username     
$DB_Password = "09asd8iadoadspflasdkjasdfbNasdfmasfd";             //MySQL Password     
$DB_DBName = "easygaadi_crm";         //MySQL Database Name  

/*$DB_Server = "eggps.cloudapp.net"; //MySQL Server    
$DB_Username = "root"; //MySQL Username     
$DB_Password = "098isdfospsdfslkjsdfsbNmsfsf";             //MySQL Password     
$DB_DBName = "gts";         //MySQL Database Name  */



$DB_TBLName = "tablename"; //MySQL Table Name   
$filename = "eg_customer";         //File Name
/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
//create MySQL connection   
//$sql = "Select * from $DB_TBLName";
//$sql="select distinct(c.mobile) as mobile,c.fullname,CONCAT( SUBSTRING( c.mobile, 1, 7 ) ,  '***' ) AS mob,c.alt_mobile_1,c.alt_mobile_2,c.alt_mobile_3,email,c.year_in_service,c.no_of_vechiles,c.company,c.address,c.city,c.state,c.pincode,c.landline,(select ifnull(group_concat(v.title),'') from eg_customer_vechile_types v where v.id_customer=c.id_customer) as vehicle_type from	eg_customer c  where c.type='T'";

//$sql="select a.accountID,a.contactName,a.contactPhone,(select group_concat(deviceID) from Device d where d.accountID=a.accountID) as vehicle,(select count(*) from Device d where d.accountID=a.accountID) as total from Account a where (accountID!='santosh' or accountID!='accounts')";

/*$sql="select a.accountID,a.contactName,a.contactPhone,d.deviceID from Account a,Device d where (a.accountID=d.accountID) and (a.accountID!='santosh' or a.accountID!='accounts') order by a.accountID asc";*/

//$sql="select  c.fullname,c.mobile,t.truck_reg_no from eg_truck t left join eg_customer c on t.id_customer=c.id_customer";
//$sql="select distinct(lower(truck_reg_no)),customer_fullname,customer_mobile from eg_order order by truck_reg_no asc";
$sql="select truck_reg_no,customer_fullname,customer_mobile from eg_order group by lower(truck_reg_no) order by truck_reg_no asc";

$Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
//select database   
$Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
//execute query 
$result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }   
?>