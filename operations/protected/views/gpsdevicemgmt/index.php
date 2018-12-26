<div class="span12">
    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
<div class="span12">
 <div class="note_box" style="position:unset">
	<div class="clr_red"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("damaged"=>1));?>" <?php if($_GET['damaged']){ echo 'class="selblock"';}?> target="_blank"><?php echo $info['damaged'];?> Damaged</a></div>
	<div class="clr_plan5"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("notworking"=>1));?>" <?php if($_GET['notworking']){ echo 'class="selblock"';}?>  target="_blank" ><?php echo $info['notworking'];?> Not Working</a></div> 
</div>
<?php
				//echo "value of ".Yii::app()->user->id;
				//echo '<pre>';print_r($_SESSION);echo '</pre>';
                if(in_array(Yii::app()->user->id,array(10,24))){
				$this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                    'id'=>'id_download_device',    
                    'label' => 'Download Device Report',
                    'visible' => 1,//$this->addPerm,
                    'type' => 'info',
                    'icon' => 'icon-white',
                    'url' =>'#'//('create')
                        )
                );}?>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover">
    <tr><td>Field Executive</td><td>Devices Inhand</td><td>Installed/Payment Pending</td><td>Installed/Payment Collected</td><td>Installed/Payment Deposited</td></tr>
	<?php foreach($installs as $k=>$v){?>
	<tr><td><a href="<?php echo $this->createUrl("gpsdevices/index",array("GpsDevice[installedById]"=>$k));?>" target="_blank"><?php echo $admin[$k]?></a></td>
	<td><a href="<?php echo $this->createUrl("gpsdevices/index",array("GpsDevice[combo]"=>"1","GpsDevice[accountID]"=>"santosh","GpsDevice[devicePaymentStatus]"=>"","GpsDevice[installedById]"=>$k));?>" target="_blank"><?php echo $v['Open'];?></a></td>
	<td><a href="<?php echo $this->createUrl("gpsdevices/index",array("GpsDevice[installedById]"=>$k,"GpsDevice[devicePaymentStatus]"=>"Pending"));?>" target="_blank"><?php echo (int)$v['Pending'];?></a>/<?php echo (int)$payments[$k]['Pending'];?></td>
	<td><a href="<?php echo $this->createUrl("gpsdevices/index",array("GpsDevice[installedById]"=>$k,"GpsDevice[devicePaymentStatus]"=>"Collected"));?>" target="_blank"><?php echo (int)$v['Collected'];?></a>/<?php echo (int)$payments[$k]['Collected'];?></td>
	<td><a href="<?php echo $this->createUrl("gpsdevices/index",array("GpsDevice[installedById]"=>$k,"GpsDevice[devicePaymentStatus]"=>"Deposited"));?>" target="_blank"><?php echo (int)$v['Deposited'];?></a>/<?php echo (int)$payments[$k]['Deposited'];?></td>
	</tr>
	<?php }?>
	<!-- <tr><td>Bhupal Reddy</td><td>10</td><td>2P,3C,4D</td></tr>
	<tr><td>Tarun</td><td>10</td><td>2P,3C,4D</td></tr> -->
  </table>
</div>
</div>
<!-- <div class="span3">
 <div class="note_box" style="position:unset">
	<div class="clr_red"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("damaged"=>1));?>" <?php if($_GET['damaged']){ echo 'class="selblock"';}?>>Damaged</a></div>
	<div class="clr_plan5"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("notworking"=>1));?>" <?php if($_GET['notworking']){ echo 'class="selblock"';}?>>Not Working</a></div> 
</div>
</div>
<div class="span3">
 <div class="note_box" style="position:unset">
	<div class="clr_red"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("damaged"=>1));?>" <?php if($_GET['damaged']){ echo 'class="selblock"';}?>>Damaged</a></div>
	<div class="clr_plan5"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("notworking"=>1));?>" <?php if($_GET['notworking']){ echo 'class="selblock"';}?>>Not Working</a></div> 
</div>
</div>
<div class="span3">
 <div class="note_box" style="position:unset">
	<div class="clr_red"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("damaged"=>1));?>" <?php if($_GET['damaged']){ echo 'class="selblock"';}?>>Damaged</a></div>
	<div class="clr_plan5"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("notworking"=>1));?>" <?php if($_GET['notworking']){ echo 'class="selblock"';}?>>Not Working</a></div> 
</div>
</div> -->
</div>
<script>
$("#id_download_device").click(function(){
    var w='900';
  var h='200';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open('<?php echo $this->createUrl("gpsdevices/downloadDevice");?>', 'Download Devices', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});
</script>