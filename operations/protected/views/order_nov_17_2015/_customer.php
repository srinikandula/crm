<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div>Order Id:<b>#Ord<?php echo $_GET['id'];?></b></div>
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

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Full Name </label><div class="controls"><b><?php echo $model['0']->customer_fullname; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Type </label><div class="controls"><b><?php echo Library::getCustomerType($model['0']->customer_type); ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Mobile </label><div class="controls"><b><?php echo $model['0']->customer_mobile; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->customer_email; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->customer_company; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->customer_address; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->customer_city; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->customer_state); ?></b></div></div></div>

                </div>
                <div class="span12">
                <?php if($_SESSION['id_admin_role']!=8){ //truck owner payments hidden from transporter
                echo $this->renderPartial('_customer_left_block', array('form' => $form, 'model' => $model), true);
                }?></div>
            </fieldset>


        </div>

        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                    <div class="span11"><?php echo $model['0']->booking_type=='T'?'Load Owner Details':'Truck Owner Details';?> </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line2">
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Full Name </label><div class="controls"><b><?php echo $model['0']->orderperson_fullname; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Mobile </label><div class="controls"><b><?php echo $model['0']->orderperson_mobile; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->orderperson_email; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->orderperson_company; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->orderperson_address; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->orderperson_city; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->orderperson_state); ?></b></div></div></div>

<div class="span12">
                <?php echo $this->renderPartial('_customer_right_block', array('form' => $form, 'model' => $model), true);?></div>
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
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Source </label><div class="controls"><b><?php echo $model['0']->source_address; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Destination </label><div class="controls"><b><?php echo $model['0']->destination_address; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Ordered </label><div class="controls"><b><?php echo $model['0']->date_ordered; ?></b></div></div></div>

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
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Driver Name </label><div class="controls"><b><?php echo $model['0']->driver_name; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Driver Mobile </label><div class="controls"><b><?php echo $model['0']->driver_mobile; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Pickup Point </label><div class="controls"><b><?php echo $model['0']->pickup_point; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Pickup Date/Time </label><div class="controls"><b><?php echo $model['0']->pickup_date_time; ?></b></div></div></div>
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Type </label><div class="controls"><b><?php echo $model['0']->truck_type; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Tracking Available </label><div class="controls"><b><?php echo $model['0']->tracking_available == 1 ? 'Yes' : 'No'; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Insurance Available </label><div class="controls"><b><?php echo $model['0']->insurance_available == 1 ? 'Yes' : 'No'; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Available </label><div class="controls"><b><?php echo $model['0']->date_available; ?></b></div></div></div>

                    <!--<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Actual Amount </label><div class="controls"><b><?php echo $model['0']->amount; ?></b></div></div></div>-->

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Payable Amount </label><div class="controls"><b><?php echo $model['0']->payable_amount; ?></b></div></div></div>
                    
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Order Status </label><div class="controls"><b><?php echo $model['0']->order_status_name; ?></b></div></div></div>

                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Goods Type </label><div class="controls"><b><?php echo $model['0']->goods_type; ?></b></div></div></div>

                    <!--<div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Load Type </label><div class="controls"><b><?php //echo $model['0']->load_type; ?></b></div></div></div>-->
               </div>
            
            </fieldset>
            

        </div>
        <?php if ($_SESSION['id_admin_role'] != 8) {
            echo $this->renderPartial('_form_additional_charges', array('form' => $form, 'model' => $model), true);
        } ?>
<?php /*if ($_SESSION['id_admin_role'] != 8) {
    echo $this->renderPartial('_form_payments_received', array('form' => $form, 'model' => $model), true);
}*/ ?>           <?php if ($_SESSION['id_admin_role'] != 8) {
    echo $this->renderPartial('_form_status', array('form' => $form, 'model' => $model), true);
} ?>         



    </div>

</div>


<script type="text/javascript">
    $('#modify_truck').live('click', function() {
        $.ajax({
            url: '<?echo $this->createUrl("order/modifyTruck");?>',
            dataType: 'json',
            type: 'post',
            data: 'id_order=<?php echo (int) $_GET['id']; ?>&truck_reg_no=' + $('#truck_reg_no').val(),
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(json) {

                if (json['success']) {
                    $('#truck_reg_no_alert').html('Done!!');
                } else {
                    $('#truck_reg_no_alert').html('Failed!!');
                }
            }
        });
    });
</script>    