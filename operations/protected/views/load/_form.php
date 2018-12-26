<?php
//echo Yii::app()->controller->id." and ".Yii::app()->controller->action->id."<br/>";exit;
$action = Yii::app()->controller->action->id;
$this->widget('ext.Flashmessage.Flashmessage');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div id="notification"></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <?php /*
          if($_SESSION['id_admin_role']==8){
          echo $form->textFieldRow(
          $model['ltr'], 'title', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'value'=>$records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email)
          );} */ ?>
        <div class="span<?php echo $_SESSION['id_admin_role'] == 10 ? '12' : '12'; ?>"  >
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details<?php if ($action == 'update') {
            echo " :#TRID" . $_GET['id'];
        } ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
<?php if (!$_GET['id']) { ?>
    <?php if ($_SESSION['id_admin_role'] != 8) { ?>
                            <div class="span4">  <div class="control-group"><label for="Admin_id_admin_role" class="control-label required">Customer Type <span class="required">*</span></label><div class="controls">
                                        <select id="Loadtruckrequest_type" name="Loadtruckrequest[type]" onchange="fncustomertype(this);">
                                            <option value="1">Registered</option>
                                            <option value="0">UnRegistered</option>

                                        </select><div style="display:none" id="Admin_id_admin_role_em_" class="help-inline error"></div></div></div> </div>
                            <div id="registered">
                                <div class="span6">
                                    <?php
                                    echo $form->textFieldRow(
                                            $model['ltr'], 'title', array('onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                                    );
                                    if ($action == 'create') {
                                        //echo '<b>Note:Truck request from unregistered customer will be rejected.Customer available in the drop down will only be accepted</b>';
                                    }
                                    ?>
                                </div>
                            <?php
                            } else {
                                if ($action == 'create') {
                                    echo $form->textFieldRow(
                                            $model['ltr'], 'title', array('readonly' => true, 'onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right", 'value' => $records['customer'][0]->idprefix . "," . $records['customer'][0]->fullname . "," . $records['customer'][0]->type . "," . $records['customer'][0]->mobile . "," . $records['customer'][0]->email . "," . $records['customer'][0]->landline)
                                    );
                                }
                                ?>
                                <?php } ?>
                        </div><?php } ?> 
                    <div id="unregistered" style="display:none" >
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'fullname', array('value' => 'empty', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'mobile', array('value' => '0000000000', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'email', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>



                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textAreaRow(
                                    $model['c'], 'address', array('rows' => 3, 'cols' => 30)
                            );
                            ?></div>

                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span5">  <?php
                            echo $form->dropDownListRow($model['c'], 'state', Library::getStates());
                            ?></div>  
                        <div class="span5">  <?php
                            echo $form->textFieldRow(
                                    $model['c'], 'landline', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                    </div>


<?php if ($action != 'create') { ?>
                <?php 
                $exp=explode(",",$model['ltr']->modified_fields);
                $modFld='style="color:red"';?>
                    
                        <div class="span4" <?php echo in_array('source_address',$exp)? $modFld:""; ?>>  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span4" <?php echo in_array('destination_address',$exp)? $modFld:""; ?>>  <?php
                        echo $form->textFieldRow(
                                $model['ltr'], 'destination_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span4" <?php echo in_array('expected_price',$exp)? $modFld:""; ?>>  <div class="control-group"><label for="Loadtruckrequest_expected_price" class="control-label">Expected Price<label style="color:red"><i><?php echo $model['ltr']->expected_price_comment ?></i></label>
                                </label><div class="controls">
                                    <input type="text" value="<?php echo $model['ltr']->expected_price ?>" id="Loadtruckrequest_expected_price" name="Loadtruckrequest[expected_price]" tabindex="1" data-placement="left" data-toggle="tooltip" rel="tooltip" title="" data-original-title="">
                                    <div style="display:none" id="Loadtruckrequest_expected_price_em_" class="help-inline error"></div></div></div></div>
                        <!--<div class="span4">  <?php
                            /* echo $form->textFieldRow(
                              $model['ltr'], 'expected_price', array('title'=>'hell','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "left","tabindex"=>1)
                              ); */
                            ?></div>-->

                        <div class="span4" <?php echo in_array('id_goods_type',$exp)? $modFld:""; ?>>   <?php
                            $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span4" <?php echo in_array('id_truck_type',$exp)? $modFld:""; ?>>   <?php
                            $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                            ?> </div>


                        <div class="span4" <?php echo in_array('tracking',$exp)? $modFld:""; ?>>  <?php
                            echo $form->dropDownListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span4" <?php echo in_array('date_required',$exp)? $modFld:""; ?>>   <?php
                        echo $form->textFieldRow(
                                $model['ltr'], 'date_required', array( 'class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                            ?></div>
                        <div class="span4" <?php echo in_array('pickup_point',$exp)? $modFld:""; ?>>   <?php
                        echo $form->textFieldRow(
                                $model['ltr'], 'pickup_point', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                            ?></div>
							<div class="span4">  <?php
						echo $form->textFieldRow(
						$model['ltr'], 'loading_charge', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
						);
						?></div>

					<div class="span4">  <?php
					echo $form->textFieldRow(
					$model['ltr'], 'unloading_charge', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
					);
					?></div>
                        <div class="span4" <?php echo in_array('insurance',$exp)? $modFld:""; ?>>  <?php
                        echo $form->dropDownListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'));
                        ?></div>
                        <div class="span4">  <?php
                        echo $form->dropDownListRow($model['ltr'], 'status', Library::getLTRStatuses());
                        ?></div>
                            <div class="span4" <?php echo in_array('comment',$exp)? $modFld:""; ?>>   <?php
                        echo $form->textAreaRow(
                                $model['ltr'], 'comment', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                            ?></div>
							<div class="span4">   <?php
                        echo $form->textAreaRow(
                                $model['ltr'], 'push_message', array('id'=>'push_message','onblur'=>'fnUpdateDetails(this)','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                            ?></div>
                            <div class="span4">   <div class="control-group"><label for="Loadtruckrequest_date_required" class="control-label">Distance/System Price</label><div class="controls"><?php echo $model['info']['distance'] . '[' . $model['info']['system_price']['min'] . '/' . $model['info']['system_price']['avg'] . ']'; ?>
                                </div></div></div>
                            <div style="font-weight:bold;color:red;position: absolute;left: 540px;
    top: -26px;
    z-index: 1;"> <?php echo $model['ltr']->cancel_reason ?></div> <?php if ($model['ltr']->id_load_truck_request == $model['ltrh'][0]->id_load_truck_request)  { ?> <div style="position:absolute;top:-67px;left:750px"><strong>Note:</strong> Red color labels indicates latest modified fields.</div> <?php } ?>
    <?php if($model['c']->approved==0) { ?>
                    <div id="notapproved_cust">Customer Not Approved</div>
    
                 <?php } ?>  
                    <?php if($model['ltr']->isactive==0) { ?>
                    <div id="cancel_ord"><span style="color:red;font-size:40px;margin-left:5px"><p>Request Canceled</p></span></div>
    
                 <?php } ?> 
    
    <?php if ($model['ltr']->id_customer) { ?>
                            <div class="span12" id="book">
                                <table border="0" id="table_truck_book" class="table truck_book">
                                    <thead>
                                        <tr>
                                            <th>Truck Provider</th>
                                            <th>Truck Reg No</th>
                                            <th>Driver Name</th>
                                            <th>Driver Mobile</th>
                                            <th>Pickup Point</th>
                                            <th>Pickup Date/Time</th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th><input type="text" name="Order[truck_provider]" id="Order_truck_provider"></th>
                                            <th><input type="text" name="Order[truck_reg_no]" id="Order_truck_reg_no" ></th>
                                            <th><input type="text" name="Order[driver_name]" id="Order_driver_name" ></th>
                                            <th><input type="text" name="Order[driver_mobile]" id="Order_driver_mobile" ></th>
                                            <th><input type="text" name="Order[pickup_point]" id="Order_pickup_point" value="<?php echo $model['ltr']->pickup_point; ?>" ></th>
                                            <th><input type="text" name="Order[pickup_date_time]" id="Order_pickup_date_time" ></th>
                                            <th><input type="text" name="Order[amount]" id="Order_amount"></th>
                                            <th>
                                                <?php
                                                echo CHtml::ajaxButton("Book", $this->createUrl('load/Addorder', array('id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(json){
	//alert(json);
	if(json["errors"]){
            //alert("in if");	
            var error="";
            for (var key in json["errors"]["message"]) 
            {
                error+="<div class=\'alert in fade alert-danger\' id=\'success\'>"+ json["errors"]["message"][key] +"!!<a style=\'cursor:pointer\' class=\'close\' data-dismiss=\'alert\'>×</a></div>";
            }
            //alert(error);    
            $("#notification").html(error);
	}
        if(json["status"]){ location="' . $this->createAbsoluteUrl('load/sendordersms') . '/"+json["id_order"];}
	//$("#uploadBox").dialog("open");
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                                ?>
                                                <!--<button name="book" type="button" id="book" class="btn btn-primary">Book</button>-->
                                            </th>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>    

                            </div>
    <?php } ?>    
            <?php if (!$model['ltr']->approved) { ?>
                            <div class="span5">  <?php
                echo $form->radioButtonListRow($model['ltr'], 'approved', array('1' => 'Yes', '0' => 'No'));
                ?></div>
            <?php }
        }
        ?></div>
            </fieldset>
        </div>

<?php  if ($action != 'create') { 
 if ($model['ltr']->id_load_truck_request == $model['ltrh'][0]->id_load_truck_request)  { 
        echo $this->renderPartial('_form_request_history_block', array('form' => $form, 'model' => $model), true);
}} ?>
<?php if ($action != 'create') {
    echo $this->renderPartial('_form_comments_block', array('form' => $form, 'model' => $model), true);
} ?>
<?php if ($action != 'create') {
    echo $this->renderPartial('_form_quotes_block', array('form' => $form, 'model' => $model), true);
} ?>
        
<?php if ($action != 'create') {
    echo $this->renderPartial('_form_search_block', array('form' => $form, 'model' => $model), true);
} ?>
<?php
if ($action != 'create') {
    Yii::app()->runController('load/searchResults');
    //echo $this->renderPartial('_form_search_results_block', array('form'=>$form,'model'=>$model,'dataProvider'=>$dataProvider),true);
}
?>
<?php if ($action == 'create') {
    echo $this->renderPartial('_form_truck_block', array('form' => $form, 'model' => $model), true);
} ?>
    </div></div>
<!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Library::getGoogleMapsKey();?>&libraries=places"></script>
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
                                            $('#Customer_fullname').val('empty');
                                            $('#Customer_mobile').val('0000000000');
                                            $('#Loadtruckrequest_title').val('');

                                        } else {
                                            $('#registered').hide();
                                            $('#unregistered').show();
                                            $('#Customer_fullname').val('');
                                            $('#Customer_mobile').val('');
                                            $('#Loadtruckrequest_title').val('empty');
                                        }
                                    }
</script>
</script>
<script>
    function fnUpdateDetails(obj){
    //alert($(obj).attr('id'));
    var field=$(obj).attr('id');
    var value=$(obj).attr('value');
    var id="<?php echo (int)$_GET['id']?>";
    $.ajax({url: "<?php echo $this->createUrl('load/updateFields');?>",
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