<style>
.table{
	font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 10px;
    line-height: 2;	
}
</style>
<link  type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></link>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<div id="googleMap" style="float:left;width:60%;height:400px;margin-left:10px;border:2px solid black"></div>
 <div style="width:50%;display:inline;">
 <?php //echo strtotime(date('Y-m-d'))." ".date('Y-m-d');?>
 <table class="table" style="width:38%;padding-left:10px;">
	<tr><td colspan="2" style="text-align:center">Customer Details</td></tr>
	<tr><td>Customer Name</td><td><?php echo $account->contactName;?></td></tr>
	<tr><td>Mobile</td><td><?php echo $account->contactPhone;?></td></tr>
	<tr><td>Email</td><td><?php echo $account->contactEmail;?></td></tr>
	<tr><td>Address</td><td><?php echo $account->contactAddress;?></td></tr>
	<tr><td>Vehicle Type</td><td><?php echo $account->vehicleType;?></td></tr>
	<tr><td>OverSpeed Limit</td><td><?php echo $account->overSpeedLimit;?>Km/Hr</td></tr>
	<tr><td>Stop Duration</td><td><?php echo $account->stopDurationLimit;?> Min</td></tr>
	<tr><td>Sms Enabled</td><td><?php echo $account->smsEnabled==1?"Yes":"No";?></td></tr>
	<tr><td>IsActive</td><td><b><?php echo $account->isActive==1?"Yes":"No";?></b></td></tr>
	<tr><td>Date Created</td><td><?php echo date('Y-m-d H:i',$account->creationTime);?></td></tr>
</table>
</div>
   <table class="table table-striped table-bordered table-hover table-condensed table-responsive">
	<tr><td>Truck No</td><td>Truck Type</td><td>Location</td><td>Speed</td><td>Last Updated</td><td>ImeiNo</td><td>Sim No</td><td>Installed By</td><td>Date Created</td></tr>
	<?php foreach($model as $row){
		$addrArr=Library::getGPBYLATLNGDetailsCloud($row->lastValidLatitude.",".$row->lastValidLongitude);
	    $addr=trim($addrArr['address']);
	?>
		<tr <?php if($row->lastGPSTimestamp+43200<strtotime(date('Y-m-d H:i:s'))){ echo "style='font-weight:bold;background-color:red'";}?>><td><?php echo $row->deviceID;?></td><td><?php echo $row->vehicleModel;?></td><td><?php echo $addr;?></td><td><?php echo floor($row->lastValidSpeedKPH);?> Km/Hr</td><td><?php echo date('Y-m-d H:i',$row->lastGPSTimestamp);?></td><td><?php echo $row->imeiNumber;?></td><td><?php echo $row->simPhoneNumber;?></td><td><?php echo $row->installedBy;?></td><td><?php echo date('Y-m-d H:i',$row->creationTime);?></td></tr>
	<?php }?>
    </table>  

<!-- <script src="http://maps.googleapis.com/maps/api/js"></script>
 --><script>
/*function initialize()
{
var mapProp = {
  center:new google.maps.LatLng(20.5937, 78.9629),
  zoom:8,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
var infowindow = new Array();
var marker = new Array();
var redicon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/red-dot.png",
        new google.maps.Size(30, 30), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32));

var greenicon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/green-dot.png",
        new google.maps.Size(30, 30), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32));
    <?php 
	$i=0;
    foreach($model as $row){?>
if(<?php echo $row->lastValidSpeedKPH; ?>>0){
		currenticon=greenicon;
	}else{
		currenticon=redicon;
	}
marker[<?php echo $i ?>]=new google.maps.Marker({
		icon:currenticon,
  position:new google.maps.LatLng(<?php echo $row->lastValidLatitude; ?>,<?php echo $row->lastValidLongitude; ?>),
  });
marker[<?php echo $i ?>].setMap(map);
//console.log(<?php echo $i ?>);

    google.maps.event.addListener(marker[<?php echo $i ?>], 'click', function() {
  infowindow[<?php echo $i ?>].open(map,marker[<?php echo $i ?>]);
  });
  infowindow[<?php echo $i ?>] = new google.maps.InfoWindow({
  content:"DeviceID: <b><?php echo $row->deviceID; ?></b>" + '</br>' +
            "VehicleModel: <b><?php echo $row->vehicleModel; ?></b>"
  });
<?php $i++;} ?>
}
google.maps.event.addDomListener(window, 'load', initialize);*/
</script>

<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script>
var map;
var markersArray = [];
var image = 'img/';
var bounds = new google.maps.LatLngBounds();
var loc;
var urlPrefix="<?php echo Yii::app()->params['config']['site_url'];?>";	
var redicon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/red-dot.png",
        new google.maps.Size(30, 30), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32));

var greenicon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/green-dot.png",
        new google.maps.Size(30, 30), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32));


var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
function init(){
    var mapOptions = { mapTypeId: google.maps.MapTypeId.ROADMAP };
    map =  new google.maps.Map(document.getElementById("googleMap"), mapOptions);
    
    <?php
/*$rows = $data['devices'];
for ($i = 0; $i < count($rows); $i++) {*/
foreach($model as $row){
    if ($row->lastValidLatitude == "" || $row->lastValidLongitude == "") {
        continue;
    }
    ?>
    loc = new google.maps.LatLng("<?php echo $row->lastValidLatitude;?>","<?php echo $row->lastValidLongitude;?>");
    bounds.extend(loc);
    addMarker(loc,"<?php echo $row->deviceID ?>","<?php echo $row->vehicleModel; ?>","<?php echo round($row->lastValidSpeedKPH) ?>","","<?php echo $row->lastValidLatitude;?>,<?php echo $row->lastValidLongitude;?>" );
<?php }?>
	
    map.fitBounds(bounds);
    map.panToBounds(bounds);    
}

function addMarker(location, deviceID,vehiclemodel,speed,odo,latLng) {          
    var currenticon;
	if(speed>0){
	currenticon=greenicon;
	}else{
	currenticon=redicon;
	}
	var marker = new google.maps.Marker({
        icon:currenticon,
        position: location,
        map: map,
        status: "active"
    });

	/*var infowindow = new google.maps.InfoWindow({
    content: "<i class='fa fa-spinner fa-spin fa-lg' style='color: #FFA46B;' title='Loading...'></i> Loading..."
  });*/
		var iw = new google.maps.InfoWindow();
		iw.open(map, marker);
		iw.setContent(deviceID+"</b>,<b>"+vehiclemodel+"</b>");
}

$(function(){ init(); });
</script>