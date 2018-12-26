<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div><b>#Ord<?php 

$arr=explode("-",substr($model['0']->date_ordered,0,10));
//$ord=$arr[2].$arr[1].$arr[0].$model['0']->id_order;
$ord=$arr[0].$arr[1].$arr[2].$model['0']->id_order;
echo $ord;
//echo $_GET['id'];?></b></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo $model['0']->booking_type!='T'?'Load Owner Details':'Truck Owner Details';?> </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                    <div class="span5"><div class="control-group"><label for="Customer_idprefix" class="control-label required">Id </label><div class="controls"><b><?php echo Library::getIdPrefix(array('id'=>$model['0']->id_customer,'type'=>$model['0']->customer_type)); ?></b></div></div></div>
                    <div class="span8">
                        <div class="control-group"><label class="control-label" for="Order_customer_fullname">Name</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" onkeydown="fnKeyDown('Order_customer_fullname')" name="Order[customer_fullname]" id="Order_customer_fullname" type="text" value="<?php echo $model['0']->customer_fullname ?>" data-original-title="" title=""><div class="help-inline error" id="Order_customer_fullname_em_" style="display:none"></div></div></div>                    </div>
                    <div class="span8">
                        <div class="control-group"><label class="control-label" for="Order_customer_mobile">Mobile</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[customer_mobile]" id="Order_customer_mobile" type="text" value="<?php echo $model['0']->customer_mobile ?>" data-original-title="" title=""><div class="help-inline error" id="Order_customer_mobile_em_" style="display:none"></div></div></div>                    </div>
                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Type </label><div class="controls"><b><?php echo Library::getCustomerType($model['0']->customer_type); ?></b></div></div></div>
                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->customer_email; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->customer_company; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->customer_address; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->customer_city; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->customer_state); ?></b></div></div></div>

                </div>
                  
            </fieldset>


        </div>

        <div class="span6" style="margin-left:0px">
            <fieldset class="portlet" style="height:342px">
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                    <div class="span11"><?php echo $model['0']->booking_type=='T'?'Load Owner Details':'Truck Owner Details';?> </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line2">
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Id </label><div class="controls"><b><a href=""><?php echo Library::getIdPrefix(array('id'=>$model['0']->id_customer_ordered,'type'=>$model['0']->orderperson_type==""?'TR':$model['0']->orderperson_type)); ?></a></b></div></div></div>
                    <div class="span8">
                        <div class="control-group"><label class="control-label" for="Customer_fullname">Name</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[orderperson_fullname]" id="Order_orderperson_fullname" onkeydown="fnKeyDown('Order_orderperson_fullname')" type="text" value="<?php echo $model['0']->orderperson_fullname ?>" data-original-title="" title=""></div></div></div>
                    <div class="span8">
                        <div class="control-group"><label class="control-label" for="Customer_fullname">Mobile</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[orderperson_mobile]" id="Order_orderperson_mobile" type="text" value="<?php echo $model['0']->orderperson_mobile ?>" data-original-title="" title=""><div class="help-inline error" id="Order_orderperson_mobile_em_" style="display:none"></div></div></div></div>
                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->orderperson_email; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->orderperson_company; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->orderperson_address; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->orderperson_city; ?></b></div></div></div>

                    <div class="span8"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->orderperson_state); ?></b></div></div></div>

<div class="span12">
                
                </div>
                </div>
            </fieldset>


        </div>

        <div class="span12" style="margin-left:0px;">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line3').slideToggle();">
                    <div class="span11"><?php echo $model['0']->booking_type == 'T' ? 'Truck Booking' : 'Load Booking'; ?>Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Source </label><div class="controls">
										<input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[source_address]" id="Order_source_address" type="text" value="<?php echo $model['0']->source_address ?>" data-original-title="" title="">
					<!-- <b><?php //echo $model['0']->source_address; ?></b> --></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Destination </label><div class="controls">
					<input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[destination_address]" id="Order_destination_address" type="text" value="<?php echo $model['0']->destination_address ?>" data-original-title="" title="">
					<!-- <b><?php //echo $model['0']->destination_address; ?></b> --></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Ordered </label><div class="controls"><b><?php echo $model['0']->date_ordered_format; ?></b></div></div></div>

<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Reg No </label><div class="controls">
                                <?php if($_SESSION['id_admin_role']!=8){
                                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                    'attribute' => 'truck_reg_no',
                                    'model' => $model['0'],
                                    'sourceUrl' => array('truck/AutocompleteTruck'),
                                    'name' => 'truck_reg_no',
                                    'options' => array(
                                        'minLength' => '2',
                                    ),
                                    'htmlOptions' => array(
                                        'value' => $model['0']->truck_reg_no,
                                        'size' => 45,
                                        'maxlength' => 45,
                                    ),
                                ));
                                ?>                 
                    <button class="icon-pencil" type="button" id="modify_truck"></button><span id="truck_reg_no_alert"></span><?php }else{ echo "<b>".$model['0']->truck_reg_no."</b>";}?></div></div></div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'driver_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'driver_mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Pickup Point </label><div class="controls"><b><?php echo $model['0']->pickup_point; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Pickup Date/Time </label><div class="controls"><b><?php echo $model['0']->pickup_date_time; ?></b></div></div></div>
                    <!-- <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Type </label><div class="controls"><b><?php echo $model['0']->truck_type; ?></b></div></div></div> -->

					<div class="span5">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('select'=>'id_truck_type,title','condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model['0'], 'id_truck_type', $list); ?>
                        </div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Tracking Available </label><div class="controls"><b><?php echo $model['0']->tracking_available == 1 ? 'Yes' : 'No'; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Insurance Available </label><div class="controls"><b><?php echo $model['0']->insurance_available == 1 ? 'Yes' : 'No'; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Available </label><div class="controls"><b><?php echo $model['0']->date_available_format; ?></b></div></div></div>

                    <!--<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Actual Amount </label><div class="controls"><b><?php echo $model['0']->amount; ?></b></div></div></div>-->

                    <!-- <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Payable Amount </label><div class="controls"><b><?php echo $model['0']->payable_amount; ?></b></div></div></div> -->
					<div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['0'], 'payable_amount', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Order Status </label><div class="controls"><b><?php echo $model['0']->order_status_name; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Goods Type </label><div class="controls"><b><?php echo $model['0']->goods_type; ?></b></div></div></div>

                    <!--<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Load Type </label><div class="controls"><b><?php //echo $model['0']->load_type; ?></b></div></div></div>-->
               </div>
            
            </fieldset>
            

        </div>
         
         



    </div>

</div>
<script type="text/javascript">
    //function fnKeyDownCustTruck(id){
function fnKeyDown(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($model['customer'] as $row){ echo $pre.'"'.$row->fullname.','.Library::getCustomerType($row->type).','.$row->mobile.','.$row->idprefix.'"';$pre=",";}?>
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
select: function (event, ui) {
		$(event.target).val(ui.item.label);
                fnUpdateDetailsSelect(id,ui.item.value);
		//alert(ui.item.value);
                //window.location = ui.item.value;
		return false;
	}

});
});
}
</script>

<script>
    function fnUpdateDetailsSelect(field,value){
    var id="<?php echo (int)$_GET['id'];?>";
    $.ajax({url: "<?php echo $this->createUrl('order/updateCustomerDetails')?>",
            type:'POST',
            data:{field:field, val:value,id:id}, 
            success: function(result){
                if(result["status"]){
                    $('#'+field).css('border','1px solid green');
                }
            },
            dataType:'json'});
    }
</script>
<script type='text/javascript'>
var input1 = document.getElementById('Order_source_address');
var autocomplete = new google.maps.places.Autocomplete(input1);

var input2 = document.getElementById('Order_destination_address');
var autocomplete = new google.maps.places.Autocomplete(input2);
</script>
