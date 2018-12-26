<?php $action = Yii::app()->controller->action->id; ?>
<style>
.form-horizontal .controls {
    position: relative;
    margin-left: 0px;
}
.row-fluid [class*="span"] {
    margin-left:0%;

}

.row-fluid .span5 {
    width: 100%;
}

.row {
	margin-left:10px;
}

.enble{
	padding-left:10px;
}
.form-horizontal select{
	width: 96%;
}
</style>
<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Gps Account' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
				<div class="row">
				<div class="span6">
                <div class="span5">
                    <?php
					$acidReadonly=false;
                    if($_SESSION['id_admin_role']!=1 && $_GET['ids']!=""){
                        $acidReadonly=true;
                    }
					//$acidReadonly=$_GET['ids']==""?false:true;
					echo $form->textFieldRow($model, 'accountID', array('readonly'=>$acidReadonly,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'onclick'=>'fnKeyDown("GpsAccount_accountID")',));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'contactName', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5"> 
			<?php 
                        if($_GET['ids']!=""){
                            $cObj=Customer::model()->find('gps_account_id="'.$_GET['ids'].'"');
                            $model->customer_type = in_array($cObj->type,array('T','TR','C'))?$cObj->type:"GPS";
                            $page=$this->customerTypeLinks[$cObj->type];
                            $link="<a href='".$this->createUrl($page."/update",array('id'=>$cObj->id_customer))."' target='_blank' >".$cObj->idprefix."</a>";
                        }
 			
			echo $form->dropDownListRow($model, 'customer_type', array('T'=>'Truck Owner','TR'=>'Transporter','C'=>'Commission Agent','GPS'=>'GPS'),array('prompt'=>'Select'));?> <?php echo $link;?>
		</div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'contactPhone', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
					echo "<b>".Library::isMobileDND($model->contactPhone)."</b>";
					?>
					
                </div>
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'privateLabelName', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));	?>
				</div>
		<div class="span5"> 
		<?php 
 		$model->vehicleType = $_GET['ids']!=""?$model->vehicleType:"";
		echo $form->dropDownListRow($model, 'vehicleType', array('TRUCK'=>'TRUCK','NONTRUCK'=>'NONTRUCK','BOTH'=>'BOTH'),array('prompt'=>'Select'));?>
		<!--<div class="control-group">
                <label class="control-label" for="GpsAccount_vehicleType">Vehicle Type</label><div class="controls">
                <?php $checked = $_GET['ids']!=""?$model['vehicleType']:"";?>
                <select name="GpsAccount[vehicleType]" id="GpsAccount_vehicleType">
				<option value="" >Select</option>
                <option value="TRUCK" <?php echo $checked =='TRUCK'?"selected":""; ?>>TRUCK</option>
                <option value="NONTRUCK" <?php echo $checked =='NONTRUCK'?"selected":""; ?>>NONTRUCK</option>
                <option value="BOTH" <?php echo $checked =='BOTH'?"selected":""; ?>>BOTH</option>
                </select><div class="help-inline error" id="GpsAccount_vehicleType_em_" style="display:none"></div></div></div>-->
		</div>
		</div>
				<div class="span6">
                <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model, 'password', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                </div>
                
                <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model, 'contactEmail', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                </div>
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'smsEnabled',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                </div>
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'isActive',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                </div>
				<div class="span5">
                    <?php echo $form->textAreaRow(
                                $model, 'contactAddress', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                    ?>
                </div>
				
								<div class="span5" id="field_ca" >  <div class="control-group"><label for="Customer_operating_destination_city" class="control-label">Operating Routes</label><div class="controls" id="input_fields_wrap"><button class="add_field_button" id="add_field_operating">+</button>
                                <?php
                                $i = 1;
								if($_GET['ids']!=""){
								foreach (GpsAccountOperatingDestinations::model()->findAll('accountID="'.$_GET['ids'].'"') as $codObj) {?>
                                    <div><input type="text" placeholder="source"  id="Customer_operating_source_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->source_address ?>"><input type="text" placeholder="destination" id="Customer_operating_destination_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][destination]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->destination_address ?>">
									<button class="remove_field" id="remove_field_operate"  href="#">-</button></div>
								<?php
                                    $i++;
									}
								}
                                ?>
                            </div></div></div>

                </div>
				</div>
				</div>
        </fieldset>
        
    </div>
</div>
<?php //if ($action == 'update') {echo $this->renderPartial('_form_device_block', array('form'=>$form,'model'=>$model),true);}?>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="GpsAccount[vehicleType]"]').click(function(){
        if(($(this).attr("value")=="TRUCK")||($(this).attr("value")=="BOTH")){
            $('#device_list_table').css('display', '');
           } else {
            $('#device_list_table').css('display', 'none');
            }

    });
    })
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Library::getGoogleMapsKey();?>&libraries=places"></script>
<!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
<script type='text/javascript'>
var input = document.getElementById('GpsAccount_contactAddress');
var autocomplete = new google.maps.places.Autocomplete(input);
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var max_fields = 20; //maximum input boxes allowed
        var wrapper = $("#input_fields_wrap"); //Fields wrapper
        var add_button = $("#add_field_operating"); //Add button ID

        var x = "<?php echo $i; ?>"; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
        $(wrapper).append('<div><input type="text" placeholder="Source"  id="Customer_operating_source_city_' + x + '" name="Customer[operating_city][' + x + '][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><input type="text"  id="Customer_operating_destination_city_' + x + '" name="Customer[operating_city][' + x + '][destination]" placeholder="Destination" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><button class="remove_field" id="remove_field_operate" >-</button></div>'); //add input box
                //initialize();
                var input2 = document.getElementById('Customer_operating_destination_city_' + x);
                var autocomplete2 = new google.maps.places.Autocomplete(input2);
                var input2 = document.getElementById('Customer_operating_source_city_' + x);
                var autocomplete2 = new google.maps.places.Autocomplete(input2);
            }
        });

        $(wrapper).on("click", "#remove_field_operate", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            //x--;
        })
    });
	</script>