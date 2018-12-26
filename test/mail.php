<?php


$input=array('to'=>'sureshbabu.kokkonda@gmail.com','day'=>'24-05-2017','data'=>array(array('deviceID'=>'AP24K7271','km'=>'213 km','start_loc'=>'Miyapur, Hyderabad','end_loc'=>'Uppal, Hyderabad','start_time'=>'Jan 10 2017','end_time'=>'Jan 1 2017'),array('deviceID'=>'AP24K7271','km'=>'213 km','start_loc'=>'Miyapur, Hyderabad','end_loc'=>'Uppal, Hyderabad','start_time'=>'Jan 10 2017','end_time'=>'Jan 1 2017'),array('deviceID'=>'AP24K7271','km'=>'213 km','start_loc'=>'Miyapur, Hyderabad','end_loc'=>'Uppal, Hyderabad','start_time'=>'Jan 10 2017','end_time'=>'Jan 1 2017')));
//echo '<pre>';print_r($input);

 function sendDailyMail($data){
	//echo '<pre>';print_r($data);echo '</pre>';exit;
	//$to = 'sureshbabu.kokkonda@gmail.com';
	
	$to=$data['email'];
	$subject = "EasyGaadi Vehicle Daily Transit Report : ".$data['day'];
	$from = 'info@easygaadi.com';
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Create email headers
	$headers .= 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	$str="";
	$str="<html><body><table><tr><td>Vehicle No</td><td>KM</td><td>Start Loc</td><td>End Loc</td><td>Start Time</td><td>End Time</td></tr>";
	foreach($data['data'] as $k=>$row){
		$str.="<tr><td>".$row['deviceID']."</td><td>".$row['km']."</td><td>".$row['start_loc']."</td><td>".$row['end_loc']."</td><td>".$row['start_time']."</td><td>".$row['end_time']."</td></tr>";
	}
	$str.="</table></body></html>";
	// Sending email
	if(mail($to, $subject, $str, $headers)){
		echo 'Your mail has been sent successfully.';
	} else{
		echo 'Unable to send email. Please try again.';
	}
}
sendDailyMail($input);
exit("here");

$url = "https://www.drivetrackplus.com/DTPService/CustomerService.asmx/GetCardLimit?userName=2000106349&password=Sravan@123&customerID=2000106349&cardNumber=7100160002529861&limitType=All";        
            //$resp=$this->curl($url);
            //echo $resp;
            //$url = 'http://localhost:8080/stocks';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //$data = curl_exec($ch);
            $file_handler = fopen('somefile.log', 'a');
            curl_setopt($ch, CURLOPT_VERBOSE, $file_handler);
            $data = curl_exec($ch);
            
            //$info=curl_getinfo($ch);
            //$error=curl_error($ch);
            
            curl_close($ch);
            echo '<pre>';
			$xml = simplexml_load_string($data);
            print_r($xml);
            //echo $data."<br/>";
            //echo $info."<br/>";
            //print_r($info);
            //echo $error."<br/>";
            exit;


echo date('d-M H:i',strtotime('now')+720);
exit;
echo date('h:i A',1474989311)."<br/>";
echo time()." ".strtotime('now');exit;
$to = 'sureshbabu.kokkonda@gmail.com';
$subject = 'Orders On '.date('d-m-Y');
$headers = "From: sureshbabu.kokkonda@gmail.com\r\n";
//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message .= '<table border="1">
	<tr><td>S.No</td><td>Order Details</td><td>Load Details</td><td>Truck Details</td></tr>
	<tr>
	<td>1
	</td>
	<td>
		<table border="1">
			<tr><td>Trip</td><td>Ameenpur, Hyderabad, Telangana-Angadipet, Hyderabad, Telangana</td></tr>
			<tr><td>Load Owner</td><td>Tulip Logistics/9030007548</td></tr>
			<tr><td>Truck Owner</td><td>Truck owner/9030007548</td></tr>
			<tr><td>Truck No</td><td>Ap36k7271/21 feet</td></tr>
			<tr><td>Net Profit</td><td>2100</td></tr>
			<tr><td>Commission</td><td>1500</td></tr>
			<tr><td>Date Booked</td><td>16-02-2016</td></tr>
			<tr><td>OrdId</td><td>#Ord831</td></tr>
		</table>
	</td>
	<td>
		<table border="1">
                        <tr><td colspan="2"><b>Billing</b></td></tr>
			<tr><td>Booked Amount(+)</td><td>9300</td></tr>
			<tr><td><i>Grand Total</i></td><td>9300</td></tr>
                        <tr><td colspan="2"><b>Transaction</b></td></tr>
			<tr><td>Advance Paid(+)</td><td>9300</td></tr>
			<tr><td><i>Balance</i></td><td>9300</td></tr>
		</table>
	</td>
	<td>
		<table border="1">
                        <tr><td colspan="2"><b>Billing</b></td></tr>
			<tr><td>Booked Amount(+)</td><td>9300</td></tr>
			<tr><td><i>Grand Total</i></td><td>9300</td></tr>
                        <tr><td colspan="2"><b>Transaction</b></td></tr>
			<tr><td>Advance Paid(+)</td><td>9300</td></tr>
			<tr><td><i>Balance</i></td><td>9300</td></tr>
		</table>
	</td>
	</tr>
	</table>';


mail($to, $subject, $message, $headers);