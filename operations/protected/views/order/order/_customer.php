<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div><b>#Ord<?php echo $_GET['id'];?></b></div>
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
                        <div class="control-group"><label class="control-label" for="Order_customer_fullname">Name</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[customer_fullname]" id="Order_customer_fullname" type="text" value="<?php echo $model['0']->customer_fullname ?>" data-original-title="" title=""><div class="help-inline error" id="Order_customer_fullname_em_" style="display:none"></div></div></div>                    </div>
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

        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                    <div class="span11"><?php echo $model['0']->booking_type=='T'?'Load Owner Details':'Truck Owner Details';?> </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line2">
                    <div class="span5"><div class="control-group"><label for="Customer_fullname" class="control-label required">Id </label><div class="controls"><b><a href=""><?php echo Library::getIdPrefix(array('id'=>$model['0']->id_customer_ordered,'type'=>$model['0']->orderperson_type==""?'TR':$model['0']->orderperson_type)); ?></a></b></div></div></div>
                    <div class="span8">
                        <div class="control-group"><label class="control-label" for="Customer_fullname">Name</label><div class="controls"><input rel="tooltip" data-toggle="tooltip" data-placement="right" name="Order[orderperson_fullname]" id="Order_orderperson_fullname" type="text" value="<?php echo $model['0']->orderperson_fullname ?>" data-original-title="" title=""></div></div></div>
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
         
         



    </div>

</div>
