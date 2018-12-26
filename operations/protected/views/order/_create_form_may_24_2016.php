<?php
$action = Yii::app()->controller->action->id;
$this->widget('ext.Flashmessage.Flashmessage');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div id="notification"></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
        <div class="span6">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" >
                    <div class="span11">Load Owner Details</div>
                    
                    <div class="clearfix"></div>
                </div>
                
                            <div class="span6">  <div class="control-group"><label for="Admin_id_admin_role" class="control-label required">Load Owner Type <span class="required">*</span></label><div class="controls">
                                        <select id="loadowner_type" name="loadowner[type]" onchange="fncustomertype(this);">
                                            <option value="1">Registered</option>
                                            <option value="0">UnRegistered</option>
                                        </select><div style="display:none" id="Admin_id_admin_role_em_" class="help-inline error"></div></div></div> </div>
                            <div id="registered">
                                <div class="span6">
                                    <?php
                                    echo $form->textFieldRow(
                                            $model['o'], 'orderperson_fullname_search', array('id'=>'Order_orderperson_fullname_search','onkeydown' => 'fnKeyDown("Order_orderperson_fullname_search")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                                    );
                                    ?>
                                </div>
              
                        </div>
                    <div id="unregistered" style="display:none" >
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'orderperson_fullname', array('value' => 'empty', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'orderperson_mobile', array('value' => '0000000000', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        
                            <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'orderperson_email', array('value' => 'example@gmail.com', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        
                            <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'orderperson_company', array('value' => 'empty', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span5">
                        <?php 
                        $model['o']->orderperson_type='TR';
                        echo $form->radioButtonListRow($model['o'], 'orderperson_type', array('C'=>"Commission Agent",'TR'=>"Transporter","L"=>"Load Onwer"));?>
                        </div>
   </div>
                
            </fieldset></div>
            <div class="span6">

            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" >
                    <div class="span11">Truck Owner Details</div>
                    <div class="clearfix"></div>
                </div>
                
                            <div class="span6">  <div class="control-group"><label for="Admin_id_admin_role" class="control-label required">Truck Owner Type <span class="required">*</span></label><div class="controls">
                                        <select id="truckowner_type" name="truckowner[type]" onchange="fncustomertypetruck(this);">
                                            <option value="1">Registered</option>
                                            <option value="0">UnRegistered</option>
                                        </select><div style="display:none" id="Admin_id_admin_role_em_" class="help-inline error"></div></div></div> </div>
                            <div id="truckregistered">
                                <div class="span6">
                                    <?php
                                    echo $form->textFieldRow(
                                            $model['o'], 'customer_fullname_search', array('id'=>'Order_customer_fullname_search','onkeydown' => 'fnKeyDown("Order_customer_fullname_search")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                                    );
                                    ?>
                                </div>
              
                        </div>
                    <div id="truckunregistered" style="display:none" >
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'customer_fullname', array('value' => 'empty', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'customer_mobile', array('value' => '0000000000', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        
                            <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'customer_email', array('value' => 'example@gmail.com', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        
                            <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'customer_company', array('value' => 'empty', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
   </div>
                
            </fieldset></div></div>
        <div class="span12">

            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" >
                    <div class="span11">Order Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                
                    
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'source_address', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'destination_address', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'date_ordered', array( 'class'=>'datetimepicker','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <!--<div class="span5">  <?php
                            /*echo $form->textFieldRow(
                                    $model['o'], 'pickup_point', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );*/
                            ?></div>-->
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'pickup_date_time', array('class'=>'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model['o'], 'id_truck_type', $list,array("prompt"=>"Select")); ?>
                        </div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'truck_reg_no', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'driver_name', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'driver_mobile', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">
                    <?php
                    echo $form->dropdownlistRow($model['o'], 'apply_tds', array("0"=>"No","1"=>"Yes"));
                    ?>
                </div>
                
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'truck_booked_amount', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'truck_loading_amount', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'truck_unloading_amount', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'truck_advance_payment', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
				<div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'load_advance_payment', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'amount', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                
                <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['o'], 'commission', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                <div class="span5">
                    <?php echo $form->textAreaRow($model['o'], 'comment', array('maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
                </div>
            </fieldset></div>
        
        </div>

    </div>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type='text/javascript'>
                                            var input = document.getElementById('Loadtruckrequest_source_address');
                                            var autocomplete = new google.maps.places.Autocomplete(input);

                                            var input1 = document.getElementById('Loadtruckrequest_destination_address');
                                            var autocomplete = new google.maps.places.Autocomplete(input1);

                                            var input = document.getElementById('search_source_address');
                                            var autocomplete = new google.maps.places.Autocomplete(input);

                                            var input1 = document.getElementById('search_destination_address');
                                            var autocomplete = new google.maps.places.Autocomplete(input1);


</script>

<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d H:i',
        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
    });
    function fncustomertype(id) {
        if (id.value == '1') {
            $('#unregistered').hide();
            $('#registered').show();
            $('#Order_orderperson_fullname_search').val('');
            $('#Order_orderperson_fullname').val('empty');
            $('#Order_orderperson_mobile').val('0000000000');
            $('#Order_orderperson_email').val('email@gmail');
            $('#Order_orderperson_company').val('company');
            //$('#Order_orderperson_type').val('TR');
            $('#Order_orderperson_fullname_search').val('');

        } else {
            $('#registered').hide();
            $('#unregistered').show();
            $('#Order_orderperson_fullname_search').val('empty');
            $('#Order_orderperson_fullname').val('');
            $('#Order_orderperson_mobile').val('');
            $('#Order_orderperson_email').val('');
            $('#Order_orderperson_company').val('');
            //$('#Order_orderperson_type').val('TR');
            $('#Order_orderperson_fullname_search').val('empty');        }
    }
    
    function fncustomertypetruck(id) {
        if (id.value == '1') {
            $('#truckunregistered').hide();
            $('#truckregistered').show();
            $('#Order_customer_fullname_search').val('');
            $('#Order_customer_fullname').val('empty');
            $('#Order_customer_mobile').val('0000000000');
            $('#Order_customer_email').val('email@gmail');
            $('#Order_customer_company').val('company');
            $('#Order_customer_fullname_search').val('');
        } else {
            $('#truckregistered').hide();
            $('#truckunregistered').show();
            $('#Order_customer_fullname_search').val('empty');
            $('#Order_customer_fullname').val('');
            $('#Order_customer_mobile').val('');
            $('#Order_customer_email').val('');
            $('#Order_customer_company').val('');
            $('#Order_customer_fullname_search').val('empty');
        }
    }
</script>