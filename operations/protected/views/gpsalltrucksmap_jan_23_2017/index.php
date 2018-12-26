<?php 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
      
    <body >
	<div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Tracking' ?>
                </div>
                <div class="span1 Dev-arrow">
                    
                </div> 
                <div class="clearfix"></div>
            </div>
	  
	      <div class="span12">
                  
            
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
        <div id="map" style=" width: 1300px; height: 500px; border: 0px; padding: 0px;margin-top:-8px"></div>        

 <style type="text/css">
   .labels {
     color: white;
     background-color: blue;
     font-family: "Lucida Grande", "Arial", sans-serif;
     font-size: 10px;
     font-weight: bold;
     text-align: center;
     width: auto;
     margin-top: 50px;
     white-space: nowrap;
   }
 </style>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script> 
<script src="https://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/src/markerwithlabel.js"></script>-->
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
    map =  new google.maps.Map(document.getElementById("map"), mapOptions);
    
    <?php
for ($i = 0; $i < count($rows1); $i++) {
    if ($rows1[$i]['lastValidLatitude'] == "" || $rows1[$i]['lastValidLongitude'] == "") {
        continue;
    }
    ?>
    loc = new google.maps.LatLng("<?php echo $rows1[$i]['lastValidLatitude'];?>","<?php echo $rows1[$i]['lastValidLongitude'];?>");
    bounds.extend(loc);
    addMarker(loc,"<?php echo $rows1[$i]['deviceID'] ?>","<?php echo $rows1[$i]['date_time'] ?>","<?php echo round($rows1[$i]['speed']) ?>","<?php echo $rows1[$i]['accountID']; ?>","<?php echo $rows1[$i]['contactName'];?>,<?php echo $rows1[$i]['contactPhone'];?>,<?php echo $rows1[$i]['vehicleModel'];?>" );
<?php }?>
	
    map.fitBounds(bounds);
    map.panToBounds(bounds);    
}

function addMarker(loc, deviceID,date_time,speed,accountid,contactname,contactphone,model) { 

	var currenticon;
	if(speed>0){
		currenticon=greenicon;
	}else{
		currenticon=redicon;
	}
	//new MarkerWithLabel
    var marker = new google.maps.Marker({
        icon:currenticon,
        position: loc,
        map: map,
        status: "active",
        /*labelContent: deviceID,
        labelAnchor: new google.maps.Point(30, -10),
        labelClass: "labels",*/ // the CSS class for the label
    });

	/*var infowindow = new google.maps.InfoWindow({
    content: "<i class='fa fa-spinner fa-spin fa-lg' style='color: #FFA46B;' title='Loading...'></i> Loading..."
  });*/
var loc=loc.toString();        
var result = loc.substring(1, loc.length-1);
var result = loc.slice(1, -1).replace(" ", "");
	marker.addListener('click', function() {
    //infowindow.open(map, marker);
        				iw.setContent('Loading..');
					iw.open(map, marker);
			    	//				iw.setContent(deviceID+"</b>,<b>"+speed+"Km/Hr</b>,<b>"+accountid+"</b>,"+contactname+"<br/><b>"+contactphone+"</b><br/><b>"+model+"</b>");
			$.ajax({
  				url: 'http://egcrm.cloudapp.net/operations/index.php/site/getGMapContent?latlng='+result,
  				success: function(data) {
    					iw.setContent(deviceID+"</b>,<b>"+speed+"Km/Hr</b>,<b>"+accountid+"</b>,"+contactname+"<br/><b>"+contactphone+"</b><br/><b>"+model+"</b><br/>"+data);
					//iw.open(map, marker);
  				}
			});
  });
}

$(function(){ init(); });
</script>
<script type="text/javascript">
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($rows1 as $row){ echo $pre.'"'.$row['deviceID'].','.$row['accountID'].'"';$pre=",";}?>
];
//alert(<?php //echo $records['customer']['0']['id_customer'] ?>);
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$( "#"+id )
//.css({ 'backgroundColor':'yellow'})
// don't navigate away from the field on tab when selecting an item
.bind( "keydown", function( event ) {
//alert(event.keyCode +'==='+$.ui.keyCode.TAB);
//alert(event.keyCode);
console.log($.ui.keyCode.TAB);
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
          
        
    </div>
 </body>


