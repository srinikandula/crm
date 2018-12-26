<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Tracking' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1" style="height:580px">
               <?php $trucks=Trucktype::model()->findAll(array('select' => 'title,id_truck_type', 'condition' => 'status=1'));?>
                <div style="margin-left:500px">
                    <form method="post">
                      <select style="width:180px;" name="truck_list">
  <option selected="selected" value="" >Choose one</option>
  <?php
    foreach($trucks as $name) { $checked=$_POST['truck_list']==$name['id_truck_type']?"selected":""; ?>
      <option <?php echo $checked;?> value="<?= $name['id_truck_type'] ?>"><?= $name['title'] ?></option>
  <?php
    } ?>
</select>  
                    <input type="text" id="search" name="truck" value="<?php echo $_POST['truck'];?>" placeholder="Truck" style="width:180px;padding-right:10px">
                    <input id="hit" type="submit" value="Search" class="btn btn-info" style="margin-left:10px;margin-top:-7px">
                    </form>
                    </div>
                <div id="googleMap" style="width:97%;height:540px;margin:auto;top:-20px;border:2px solid black"></div>
          </div>
        </fieldset>
    </div>
</div>

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
for($i=0;$i<count($rows);$i++){
    $lat = $rows[$i]['lastValidLatitude'];
    $lng = $rows[$i]['lastValidLongitude']; 

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
<script src="http://maps.googleapis.com/maps/api/js"></script>

<script>

function initialize()
{
var mapProp = {
  center:new google.maps.LatLng(17.403551, 78.456536),
  zoom:8,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
var infowindow = new Array();
var marker = new Array();

    <?php 
    for($i=0;$i<count($rows);$i++){ //if($rows[$i]['lastValidLatitude']=="" || $rows[$i]['lastValidLongitude']=="" ){ continue;}  ?>
marker[<?php echo $i ?>]=new google.maps.Marker({
  position:new google.maps.LatLng(<?php echo $rows[$i]['lastValidLatitude'] ?>,<?php echo $rows[$i]['lastValidLongitude'] ?>),
  });
marker[<?php echo $i ?>].setMap(map);
//console.log(<?php echo $i ?>);

    google.maps.event.addListener(marker[<?php echo $i ?>], 'click', function() {
  infowindow[<?php echo $i ?>].open(map,marker[<?php echo $i ?>]);
  });
  infowindow[<?php echo $i ?>] = new google.maps.InfoWindow({
  content:"AccountID: <b><?php echo $rows[$i]['accountID'] ?></b>" + '</br>' +
          "Name: <b><?php echo $rows[$i]['contactName'] ?></b>" + '</br>' +
          "Phone: <b><?php echo $rows[$i]['contactPhone'] ?></b>" + '</br>' +
            "DeviceID: <b><?php echo $rows[$i]['deviceID'] ?></b>" + '</br>' +
            "VehicleModel: <b><?php echo $rows[$i]['vehicleModel'] ?></b>" + '</br>' +
            "Address: <b><?php echo $address[$i] ?></b>"
  });
<?php } ?>

    
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>