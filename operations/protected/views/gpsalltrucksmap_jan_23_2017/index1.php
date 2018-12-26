<?php
Yii::import('ext.jquery.*');
Yii::import('ext.autocomplete.*');
?>
<html>
    <head> 
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            body { font: normal 10pt Helvetica, Arial; }
            #map { width: 1300px; height: 500px; border: 0px; padding: 0px;margin-top:-8px }
        </style>
        <script src="http://maps.google.com/maps/api/js?v=3&sensor=false" type="text/javascript"></script>
        <script type="text/javascript">

        var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/red.png",
        new google.maps.Size(30, 30), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32));
        var center = null;
        var map = null;
        var currentPopup;
        var bounds = new google.maps.LatLngBounds();
        var infowindow = new Array();
        var marker=Array();
        
        function initMap()
        {
            map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(0, 0),
            zoom: 0,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            mapTypeControlOptions:
            {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
            },
            navigationControl: true,
            navigationControlOptions:
            {
                style: google.maps.NavigationControlStyle.SMALL
            }
            });
			
<?php
function getaddress($lat,$lng)
{
$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
$json = @file_get_contents($url);
$data=json_decode($json);
$status = $data->status;
if($status=="OK")
return $data->results[0]->formatted_address;
else
return false;
}
$address = array();
for($i=0;$i<count($rows1);$i++){
    $lat = $rows1[$i]['lastValidLatitude'];
    $lng = $rows1[$i]['lastValidLongitude']; 

    $address[$i]= getaddress($lat,$lng);
if($address[$i])
{
$address[$i];
}
else
{
$address[$i] =  "Not found";
}
//echo $i;
}
//exit;
?>

			<?php
		
					
			
//$lat1=Yii::app()->db->createCommand('SELECT lat from details;')->queryAll();
//$long1=Yii::app()->db->createCommand('SELECT lng from details;')->queryAll();
//$name1=Yii::app()->db->createCommand('SELECT name from details;')->queryAll();
//echo ($lat1[0]['lat']);exit;
             for($i=0;$i<count($rows1);$i++) { //you could replace this with your while loop query?> 
          //     addMarker(<?php echo ($lat1[$i]['lat']);?> ,  <?php echo ($long1[$i]['lng']);?>  );
			    var pt = new google.maps.LatLng(<?php echo $rows1[$i]['lastValidLatitude']?> ,  <?php echo $rows1[$i]['lastValidLongitude']?>);
            bounds.extend(pt);
             marker[<?php echo $i ?>] = new google.maps.Marker(
            {
                position: pt,
                icon: icon,
                map: map
            });
		infowindow[<?php echo $i ?>] = new google.maps.InfoWindow({
  content:"AccountID: <b><?php echo $rows1[$i]['accountID'] ?></b>" + '</br>' +
          "Name: <b><?php echo $rows1[$i]['contactName'] ?></b>" + '</br>' +
          "Phone: <b><?php echo $rows1[$i]['contactPhone'] ?></b>" + '</br>' +
            "DeviceID: <b><?php echo $rows1[$i]['deviceID'] ?></b>" + '</br>' +
            "VehicleModel: <b><?php echo $rows1[$i]['vehicleModel'] ?></b>"+ '</br>' +
			 "Address: <b><?php echo $address[$i] ?></b>"
         });
		  google.maps.event.addListener(marker[<?php echo $i ?>], 'click', function() {
			  if (currentPopup != null)
                {
                    currentPopup.close();
                    currentPopup = null;
                }
				 infowindow[<?php echo $i ?>].open(map,marker[<?php echo $i ?>]);
				 currentPopup = infowindow[<?php echo $i ?>];
  });
     google.maps.event.addListener(infowindow[<?php echo $i ?>], "closeclick", function()
            {
              currentPopup = null;
            });
   <?php   } ?>
			 
            center = bounds.getCenter();
            map.fitBounds(bounds);
        }
        </script>

    </head>
    <body onload="initMap()" >
	<div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Tracking' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div> 
                <div class="clearfix"></div>
            </div>
	  
	      <div class="span12">
        <fieldset class="portlet">
            
            <div class="portlet-content" id="hide_box_line1" >
               <?php $trucks=Trucktype::model()->findAll(array('select' => 'title,id_truck_type', 'condition' => 'status=1'));?>
                <div style="margin-left:510px">
                    <form method="post">
                      <select style="width:180px;" name="truck_list">
  <option selected="selected" value="">Choose one</option>
  <?php
    foreach($trucks as $name) { $checked=$_POST['truck_list']==$name['id_truck_type']?"selected":""; ?>
      <option <?php echo $checked;?> value="<?= $name['id_truck_type'] ?>"><?= $name['title'] ?></option>
  <?php
    } ?>
</select>  
<input type="text" id="search" onkeydown="fnKeyDown('search')" name="truck" value="<?php echo $_POST['truck'];?>" placeholder="Truck" style="width:180px;padding-left:10px">
                    <input id="hit" type="submit" value="Search" class="btn btn-info" style="margin-right:10px;margin-top:-7px">
                    </form>
                    </div>
        <div id="map"></div>        
          </div>
        </fieldset>
    </div>
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php $rows=Gpsdevice::model()->findAll(array('select' => 'accountID,deviceID', 'condition' => 'isActive=1'));?>
<script type="text/javascript">
    //function fnKeyDownCustTruck(id){
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($rows as $row){ echo $pre.'"'.$row->deviceID.','.$row->accountID.'"';$pre=",";}?>
];

	function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$( "#"+id )
// don't navigate away from the field on tab when selecting an item
.bind( "keydown", function( event ) {

//alert(event.keyCode +'==='+$.ui.keyCode.TAB)
if ( event.keyCode === $.ui.keyCode.TAB &&
$( this ).data( "ui-autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
minLength: 0,
source: function( request, response ) {
//	alert(extractLast( request.term ))
//stop concatination after ,
if(extractLast( request.term )=="")
{
	return false;
}
//stop concatination after ,

// delegate back to autocomplete, but extract the last term
response( $.ui.autocomplete.filter(
availableTags, extractLast( request.term ) ) );
},
focus: function() {
// prevent value inserted on focus
return false;
},

});
});
}

</script>       
        
</html>


