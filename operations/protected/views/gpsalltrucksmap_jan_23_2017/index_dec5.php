<html>
    <head> 
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            body { font: normal 10pt Helvetica, Arial; }
            #map { width: 1400px; height: 600px; border: 0px; padding: 0px; }
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
		
					
			
//$lat1=Yii::app()->db->createCommand('SELECT lat from details;')->queryAll();
//$long1=Yii::app()->db->createCommand('SELECT lng from details;')->queryAll();
//$name1=Yii::app()->db->createCommand('SELECT name from details;')->queryAll();
//echo ($lat1[0]['lat']);exit;
             for($i=0;$i<count($rows);$i++) { //you could replace this with your while loop query?> 
          //     addMarker(<?php echo ($lat1[$i]['lat']);?> ,  <?php echo ($long1[$i]['lng']);?>  );
			    var pt = new google.maps.LatLng(<?php echo $rows[$i]['lastValidLatitude']?> ,  <?php echo $rows[$i]['lastValidLongitude']?>);
            bounds.extend(pt);
             marker[<?php echo $i ?>] = new google.maps.Marker(
            {
                position: pt,
                icon: icon,
                map: map
            });
		infowindow[<?php echo $i ?>] = new google.maps.InfoWindow({
  content:"AccountID: <b><?php echo $rows[$i]['accountID'] ?></b>" + '</br>' +
          "Name: <b><?php echo $rows[$i]['contactName'] ?></b>" + '</br>' +
          "Phone: <b><?php echo $rows[$i]['contactPhone'] ?></b>" + '</br>' +
            "DeviceID: <b><?php echo $rows[$i]['deviceID'] ?></b>" + '</br>' +
            "VehicleModel: <b><?php echo $rows[$i]['vehicleModel'] ?></b>"
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
                map.panTo(center);
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
	   <div id="map"></div>
	      <div class="span12">
        <fieldset class="portlet">
            
            <div class="portlet-content" id="hide_box_line1" >
               <?php $trucks=Trucktype::model()->findAll(array('select' => 'title,id_truck_type', 'condition' => 'status=1'));?>
                <div style="margin-left:500px">
                    <form method="post">
                      <select style="width:180px;" name="truck_list">
  <option selected="selected" value="">Choose one</option>
  <?php
    foreach($trucks as $name) { $checked=$_POST['truck_list']==$name['id_truck_type']?"selected":""; ?>
      <option <?php echo $checked;?> value="<?= $name['id_truck_type'] ?>"><?= $name['title'] ?></option>
  <?php
    } ?>
</select>  
                    <input type="text" id="search" name="truck" value="<?php echo $_POST['truck'];?>" placeholder="Truck" style="width:180px;padding-left:10px">
                    <input id="hit" type="submit" value="Search" class="btn btn-info" style="margin-right:10px;margin-top:-7px">
                    </form>
                    </div>
               
          </div>
        </fieldset>
    </div>
</html>