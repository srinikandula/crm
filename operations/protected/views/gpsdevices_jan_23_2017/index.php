<style>
.selblock{
	font-weight:bold;
}

</style>
<div class="span12 top_box_fixed">
    <?php    $this->widget('ext.Flashmessage.Flashmessage'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm',
            array(
        'id' => 'gridForm',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid grid-menus span12 pull-left ">
        <div class="span12">
            <div class="span10 buttons_top">
            <?php Library::addButton(array('label'=>'Create','url'=> $this->createUrl('create')));  ?>
            <?php Library::buttonBulk(array('label'=>'Delete','permission'=>$this->deletePerm,'url'=> $this->createUrl($this->uniqueid . "/delete")));?>
            <?php 
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                    'id'=>'id_assign_devices',    
                    'label' => 'Assign Devices',
                    'visible' => $this->editPerm,//$this->addPerm,
                    'type' => 'info',
                    'icon' => 'icon-white',
                    'url' =>'#'//('create')
                        )
                );?>
				<?php 
                $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                    'id'=>'id_transfer_devices',    
                    'label' => 'Transfer Devices',
                    'visible' => $this->editPerm,//$this->addPerm,
                    'type' => 'info',
                    'icon' => 'icon-white',
                    'url' =>'#'//('create')
                        )
                );?>
				<?php Library::buttonBulkApprove(array('type'=>'info','label'=>'Confirm Device Payment','permission'=>$this->editPerm,'url'=> $this->createUrl($this->uniqueid . "/confirmDevicePayment")));?>
            </div>
            <div class="span2 dropdown_cut_main pull-right">
                <div class="span7 pull-right">
                    <?php Library::getPageList(array('totalItemCount' => $dataSet->totalItemCount)); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="note_box" style="float:right">
	<div class="clr_red"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("damaged"=>1));?>" <?php if($_GET['damaged']){ echo 'class="selblock"';}?>>Damaged</a></div>
	<div class="clr_plan5"></div>
	<div class="note"><a href="<?php echo $this->createUrl("gpsdevices/index",array("notworking"=>1));?>" <?php if($_GET['notworking']){ echo 'class="selblock"';}?>>Not Working</a></div> 
</div>
<div class="row-fluid">
    <div class="span12 top_box_margin">

        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView',
                array(
            'type' => 'striped bordered condensed',
            'template' => "{summary}{pager}{items}",
            'summaryText' => 'Displaying {start}-{end} of {count} Results.',
            'enablePagination' => true,
            'pager' => array('class' => 'CListPager', 'header' => '&nbsp;Page', 'id' => 'no-widthdesign_left'),
            'ajaxUpdate' => false,
            'id' => 'productinfo-grid',
            'dataProvider' => $dataSet,
            'filter' => $model,
            'bulkActions' => array(
                'actionButtons' => array(
                ),
                'checkBoxColumnConfig' => array(
                    'name' => 'id',
                    'id' => 'id',
                    'value' => '$data->accountID."##".$data->deviceID."##".$data->devicePaymentStatus."##".$data->creationTime',
                ),
            ),
            'rowCssClassExpression' => '( $row%2 ? $this->rowCssClass[1] : $this->rowCssClass[0] ).( ($data->isDamaged == 1 ) ?" alert":"").( ($data->accountID!="santosh") && ($data->lastGPSTimestamp <time()-41400 ) ?" plan_exp5":"")',        
            'columns' => array(
                array(
                    'header' => 'S.No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                ),
                array('name' => 'accountID', 'header' => 'Account ID', 'value' => '$data->accountID'),
                 array('name' => 'deviceID', 'header' => 'Device ID','type'=>'raw', 'value' => array($this,'grid')),
                array('name' => 'vehicleModel', 'header' => 'Vehicle Model', 'value' => 'substr($data->vehicleModel,0,8)'),
		/*array('name' => 'vehicleType', 'header' => 'Vehicle Type', 'value' => array($this,'grid'),'filter' => CHtml::dropDownList('GpsDevice[vehicleType]',
                            $_GET['GpsDevice']['vehicleType'],
                            array('' => 'All','TK' => 'Truck', 'TR' => 'Transporter', 'NTK' => 'Non Truck')
                    )),*/
				//array('name' => 'lastOdometerKM', 'header' => 'Vehicle Type', 'value' => '$data->lastOdometerKM'),
				array('name' => 'simID', 'header' => 'SIM ID', 'value' => '$data->simID'),
				array('name' => 'simPhoneNumber', 'header' => 'SIM Phone No', 'value' => '$data->simPhoneNumber'),
                array('name' => 'imeiNumber', 'header' => 'imeiNumber', 'value' => '$data->imeiNumber'),
                array('name' => 'lastGPSTimestamp', 'header' => 'Last GPS Update', 'value' => array($this,'grid')),
				array('name' => 'expiryTime', 'header' => 'Plan Expiry Date', 'value' => array($this,'grid'),"type"=>"raw"),
				array('name' => 'creationTime', 'header' => 'Date Created', 'value' => array($this,'grid')),
				array('name' => 'installedBy', 'header' => 'Installed By', 'value' => 'substr($data->installedBy,0,6)'),
				array('name' => 'devicePaymentStatus', 'header' => 'Device Payment',
                    'filter' => CHtml::dropDownList('GpsDevice[devicePaymentStatus]',
                            $_GET['GpsDevice']['devicePaymentStatus'],
                            array('' => 'All', 'Pending' => 'Pending','Deposited'=>'Deposited','Collected'=>'Collected')
                    ), 'value' => array($this,'grid')),
                                array('name' => 'isActive', 'header' => 'Status',
                    'filter' => CHtml::dropDownList('GpsDevice[isActive]',
                            $_GET['GpsDevice']['isActive'],
                            array('' => 'All', '1' => 'Enable', '0' => 'Disable',)
                    ), 'value' => '$data->isActive==1?"<span class=\"icon-enable\"></span>":"<span class=\"icon-disable\"></span>"','type'=>'raw'),
                array('class' => 'bootstrap.widgets.TbButtonColumn',
                    'htmlOptions' => array('style' => 'min-width:50px;'),
                    'template' => $this->gridPerm['template'],
                    'buttons' => array('update' => array('label' => $this->gridPerm[buttons][update][label],
                            'url' => 'Yii::app()->createUrl(Yii::app()->controller->id."/update/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))'),
                        'delete' => array('url' => ' Yii::app()->createUrl(Yii::app()->controller->id."/delete/",array("ids"=>$data->primaryKey,"backurl"=>base64_encode(urldecode(Yii::app()->request->requestUri)),"id"=>$data->primaryKey))')),
                ),
            ),
        ));
        ?>
        <input type="hidden" name="backurl" value="<?php echo base64_encode(urldecode(Yii::app()->request->requestUri)); ?>">
<?php $this->endWidget(); ?>
    </div>
</div>
<div class="clearfix"></div>
<script>
function fnupdate(id){
    //alert(id);
    $.ajax({url: "<?php echo $this->createUrl('gpsdevices/renewalPaid');?>",
            type:'POST',
            data:{id:id}, 
            success: function(result){
                if(result["status"]){
                    //$('#'+field).css('border','1px solid green');
                    $('#'+id).removeClass();
                    $('#'+id).addClass('icon-right');
                }
            },
            dataType:'json'});

}

</script>
<script>
/*function fnassignDevices(id){
  var w='900';
  var h='600';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open('<?php echo $this->createUrl("gpsdevices/assignDevices");?>', 'Assign Devices', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}*/
$("#id_assign_devices").click(function(){
    var w='900';
  var h='600';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open('<?php echo $this->createUrl("gpsdevices/assignDevices");?>', 'Assign Devices', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});

$("#id_transfer_devices").click(function(){
    var w='900';
  var h='600';
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open('<?php echo $this->createUrl("gpsdevices/transferDevices");?>', 'Transfer Devices', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
});

function getDeviceDetails(id){
	 $.post('<?php echo $this->createUrl("gpsdevices/getDeviceDetails");?>',
    {
        deviceID: id
    },
    function(data, status){
        alert(data);
    });
}
</script>