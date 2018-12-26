
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
				
                <!-- <div>Note:1.Automatic password will be generated and mailed when approved.</div> -->
                <div class="portlet-content" id="hide_box_line1">
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label required" for="Customer_idprefix">
                                ID
                                <span class="required">*</span>
                            </label>
                            <div class="controls">
                                <?php echo $model['c']->idprefix; ?>
                            </div>
                        </div>
                    </div>
					<div class="span5">  <?php
                        $cTypes = Library::getCustomerTypes();
                        unset($cTypes['G']);
                        //echo '<pre>';print_r($cTypes);echo '</pre>';
                        //echo $form->radioButtonListRow($model['c'], 'type', $cTypes);
						echo $form->dropdownListRow($model['c'], 'type', $cTypes,array('prompt'=>'select'));
                        ?></div>
                    <?php //echo '<pre>';print_r($model['cod']);echo '</pre>';?>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'fullname', array('rel' => 'tooltip','onkeydown' => 'fnKeyDown("Customer_fullname")', 'data-toggle' => "tooltip", 'data-placement' => "right","title"=>"(check if record exists in dropdown,duplicates will be rejected)")
                        );
                        ?></div>
                    <!-- <div class="span5 uploading-img-main"> <?php
                        /*echo $form->fileFieldRow(
                                $model['c'], 'profile_image', array('name' => 'image', 'rel' => 'tooltip',
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'), array('hint' => '<div class="logo-img"><img src="' . Library::getMiscUploadLink() . $model['c']->profile_image . '"><input type="hidden" name="prev_file" value="' . $model['c']->profile_image . '"></div>')
                        );
                        echo '<p class="image-name-display">' . $model['c']->profile_image . '</p>';
                        */?>
                    </div> -->

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'mobile', array('rel' => 'tooltip','onblur' => 'fnKeyDown("Customer_mobile")', 'data-toggle' => "tooltip", 'data-placement' => "right","title"=>"Mobile no should be unique!!")
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'email', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

					                    <div class="span5">
                        <?php
                        if (Yii::app()->controller->action->id == 'update') {
                            echo $form->passwordFieldRow(
                                    $model['c'], 'password', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right", 'value' => '')
                            );
                        } else {
                            echo $form->passwordFieldRow(
                                    $model['c'], 'password', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right"));
                        }
                        ?>
                    </div>
                    <div class="span5">
                        <?php
                        echo $form->passwordFieldRow(
                                $model['c'], 'confirm', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                    </div>

                    <div class="span5" id="no_of_trucks" <?php if ($model['c']->type != 'C' && $model['c']->type != 'T') { ?>style="display:none" <?php } ?> >  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'no_of_vechiles', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                  

						<div class="span5">  <?php
						//echo $form->dropDownListRow($model['c'], 'load_required', array('0'=>'No','1'=>'Yes'),array('prompt'=>'Select'));
						echo $form->checkboxRow($model['c'],'load_required',array('value'=>1));
                        ?></div>

						<div class="span5">  <?php
						//echo $form->dropDownListRow($model['c'], 'gps_required', array('0'=>'No','1'=>'Yes'),array('prompt'=>'Select'));
                        echo $form->checkboxRow($model['c'],'gps_required',array('value'=>1));
						?></div>
						
						<div class="span5">  <?php
						//echo $form->dropDownListRow($model['c'], 'gps_required', array('0'=>'No','1'=>'Yes'),array('prompt'=>'Select'));
                        echo $form->checkboxRow($model['c'],'smartphone_available',array('value'=>1));
						?></div>

	<div class="span5" id="id_truck_type"  <?php if ($model['c']->type != 'C' && $model['c']->type != 'T') { ?>style="display:none" <?php } ?>  > <div class="control-group"><label class="control-label" for="ProductGroup_sort_order">Truck Types</label>
		<div class="controls">
        <?php
              
	
		foreach($model['truckTypes'] as $pro):
				$data['product'][$pro->id_truck_type]=$pro->title;
			endforeach;
			//echo '<pre>';print_r($data['product']);exit;
	
                $selectedProductTypes=$model['cvt'];
				//echo '<pre>';print_r($model['cvt']);exit;
                    foreach($selectedProductTypes as $key=> $product):
                            $data['selected'][$key]=array('selected'=>'selected');
                    endforeach;
		
                //echo '<pre>';print_r($selectedProductTypes); print_r($data); echo '</pre>';
        ?>
        <?php
		$this->widget(
				'bootstrap.widgets.TbSelect2',
				array(
					'name' => 'id_truck_type',
					'data'=>$data['product'],
					'options' => array(
							'placeholder'=>'Search Truck Type..',
                            ),
                    'htmlOptions' => array(
                            'options' => $data['selected'],
							'multiple' => 'multiple',
							'id' => 'issue-574-checker-select'   
							),           
				)
			);
        ?>
       				</div>
       			</div>
       		</div>
				


                    <div class="span4">		
					<?php
                      echo $form->dropDownListRow($model['c'], 'year_in_service', Library::getExperienceYear(),array('prompt'=>'Year'));
					  $experience = (date(Y))-($model['c']->year_in_service);?>
					  </div>
                    <div class="span1" id="experience"><?php echo $experience; ?>
					</div>

					<div class="span5">  
						<div class="control-group">
							<label for="Customer_alt_mobile_1" class="control-label">Alt Mobile 1</label>
							<div class="controls">
							<input placeholder="Alt Mobile 1" style="width:90px" type="text" id="Customer_alt_mobile_1" name="Customer[alt_mobile_1]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $model['c']->alt_mobile_1;?>">
							<input placeholder="Alt Mobile 1" style="width:90px"  type="text" id="Customer_alt_mobile_2" name="Customer[alt_mobile_2]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $model['c']->alt_mobile_2;?>">
							<input placeholder="Alt Mobile 1" style="width:90px"  type="text" id="Customer_alt_mobile_3" name="Customer[alt_mobile_3]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $model['c']->alt_mobile_3;?>">
							<div style="display:none" id="Customer_alt_mobile_1_em_" class="help-inline error"></div>
							</div>
						</div>
					</div>
                    <!-- <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'alt_mobile_1', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'alt_mobile_2', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'alt_mobile_3', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div> -->

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow(
                                $model['c'], 'address', array('rows' => 2, 'cols' => 50)
                        );
                        ?></div>

                    <!-- <div class="span5">  <?php
                        /*echo $form->textFieldRow(
                                $model['c'], 'operating_source_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );*/
                        ?></div> -->

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->dropDownListRow($model['c'], 'state', Library::getStates());
                        /* echo $form->textFieldRow(
                          $model['c'],'state',
                          array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
                          ); */
                        ?></div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'pincode', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'landline', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php echo $form->dropDownListRow($model['c'], 'payment_type', Library::getPaymentTypes(), array('prompt' => 'select')); ?>
                    </div>
                    <div id="load" <?php if ($model['c']->type != 'C' && $model['c']->type != 'T') { ?>style="display:none" <?php } ?> >
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'load_payment_advance_percent', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'load_payment_topay_percent', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'load_payment_pod_days', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div></div>
                    <div class="span5" id="DivCustomer_bank_name"  style="display:none">
                        <?php
                        echo $form->textFieldRow(
                                $model['c'], 'bank_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                    </div>
                    <div class="span5"  id="DivCustomer_bank_account_no"  style="display:none" >
<?php
echo $form->textFieldRow(
        $model['c'], 'bank_account_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
);
?>
                    </div>
                    <div class="span5"  id="DivCustomer_bank_ifsc_code"  style="display:none">
<?php
echo $form->textFieldRow(
        $model['c'], 'bank_ifsc_code', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
);
?>
                    </div>
                    <div class="span5"  id="DivCustomer_bank_branch" style="display:none">
                    <?php
                    echo $form->textFieldRow(
                            $model['c'], 'bank_branch', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                    );
                    ?>
                    </div>
<div class="span5 uploading-img-main" id="tds_doc" <?php if ($model['c']->type != 'C' && $model['c']->type != 'T') { ?>style="display:none" <?php } ?> >  <?php
                           
                        echo $form->fileFieldRow(
                                $model['c'], 'tds_declaration_doc',
                                array('name' => 'tds_declaration_doc', 'rel' => 'tooltip', 
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'),
                                array('hint' => '<div class="logo-img"><img src="' . Library::getMiscUploadLink() . $model['c']->tds_declaration_doc . '"><input type="hidden" name="prev_tds_declaration_doc" value="' . $model['c']->tds_declaration_doc . '"></div>')
                        );
                        echo '<p class="image-name-display">' . $model['c']->tds_declaration_doc . '</p>';?>
                        </div>

                    <!--<?php //if ((int) $_GET['id']) { ?>
                        <div class="span5">  <?php
                            //echo $form->radioButtonListRow($model['c'], 'status', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>


                        <div class="span5">  <?php
                            //echo $form->radioButtonListRow($model['c'], 'approved', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>
                        <input type="hidden" name="id" value="<?php //echo (int) $_GET['id']; ?>">
                    <?php //} ?>-->


                    <div class="span5">  <?php
                        echo $form->dropDownListRow($model['c'], 'lead_source', Library::getLeadSources());
                        ?></div>
                    <?php /* if ((int) $_GET['id']) { ?>
                      <div class="span5">  <?php
                      echo $form->dropDownListRow($model['c'], 'lead_status', Library::getLeadStatuses());
                      ?></div><?php } */ ?>
<div class="span12">					
<div class="span5" id="field_ca" >  <div class="control-group"><label for="Customer_operating_destination_city" class="control-label">Operating Routes</label><div class="controls" id="input_fields_wrap"><button class="add_field_button" id="add_field_operating">+</button>
                                <?php /* if ($_GET['id'] == '') { ?>                               
                                  <div><input type="text"  id="Customer_operating_destination_city_1" name="Customer[operating_destination_city][]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $model['cod']['0']->address ?>"></div><?php } */ ?>
                                <?php
                                $i = 1;
								
								$trucktypelist = CHtml::listData($model['truckTypes'], 'id_truck_type', 'title');
                                foreach ($model['cod'] as $codObj) {
                                    ?>
                                    <div><input type="text" placeholder="source"  id="Customer_operating_source_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->source_address ?>"><input type="text" placeholder="destination" id="Customer_operating_destination_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][destination]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->destination_address ?>">
									<?php if($model['c']->type=='TR'){
									echo CHtml::dropDownList('Customer[operating_city]['.$i.'][id_truck_type]', $codObj->id_truck_type,$trucktypelist);
									}?>
									<button class="remove_field" id="remove_field_operate"  href="#">-</button></div>

                                    <?php
                                    $i++;
                                }

								if(!sizeof($model['cod'])){?>
								<div><input type="text" placeholder="source"  id="Customer_operating_source_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->source_address ?>"><input type="text" placeholder="destination" id="Customer_operating_destination_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][destination]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->destination_address ?>">
									<?php if($model['c']->type=='TR'){
									echo CHtml::dropDownList('Customer[operating_city]['.$i.'][id_truck_type]', $codObj->id_truck_type,$trucktypelist);
									}?>
									<button class="remove_field" id="remove_field_operate"  href="#">-</button></div>
								<?php $i++;}
                                ?>
                            </div></div></div>
					

<div class="span5" id="span_traffic_manager" <?php if($model['c']->type!='TR'){?>style="display:none"<?php }?>>  <div class="control-group"><label for="Customer_operating_destination_city" class="control-label">Traffic Managers</label><div class="controls" id="input_fields_traffic"><button class="add_field_button" id="add_field_traffic">+</button>

<?php
$j = 1;
foreach ($model['ctm'] as $codObj) {
	?>
	<div>
	<input type="text" placeholder="Full Name"  id="Customertrafficmanager_full_name_<?php echo $j; ?>" name="Customertrafficmanager[traffic][<?php echo $j; ?>][full_name]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->full_name ?>">
	<input type="text" placeholder="Mobile" id="Customertrafficmanager_mobile_<?php echo $j; ?>" name="Customertrafficmanager[traffic][<?php echo $j; ?>][mobile]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->mobile ?>">
	<input type="text" placeholder="City" id="Customertrafficmanager_city_<?php echo $j; ?>" name="Customertrafficmanager[traffic][<?php echo $j; ?>][city]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->city ?>">
	<button class="remove_field" id="remove_field_traffic" href="#">-</button></div>

	<?php
	$j++;
}
?>
</div></div></div></div>

                </div>
            </fieldset>


        </div>
    </div>
</div>

     <?php
$this->renderPartial('/leads/_form_truck_block', array('form'=>$form,'model'=>$model));
    $this->renderPartial('/leads/_form_driver_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('/leads/_form_customer_doc_block', array('form'=>$form,'model'=>$model));
			/*$this->renderPartial('_form_status_comment_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_doc_collection_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_lead_assignment_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_status_history_block', array('form'=>$form,'model'=>$model));*/
        ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->   
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

			var select='<select id="Customer_operating_destination_id_truck_type_' + x + '" name="Customer[operating_city][' + x + '][id_truck_type]" data-toggle="tooltip" rel="tooltip" data-original-title=""><option value="">Select</option><?php foreach($model['truckTypes'] as $pro):
	echo '<option value="'.$pro->id_truck_type.'">'.$pro->title.'</option>';
		endforeach;?></select>';
		if($("#Customer_type").val()!='TR'){
			select="";
		}
                $(wrapper).append('<div><input type="text" placeholder="Source"  id="Customer_operating_source_city_' + x + '" name="Customer[operating_city][' + x + '][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><input type="text"  id="Customer_operating_destination_city_' + x + '" name="Customer[operating_city][' + x + '][destination]" placeholder="Destination" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="">'+select+'<button class="remove_field" id="remove_field_operate" >-</button></div>'); //add input box
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

		//traffic manager
		var wrapper1 = $("#input_fields_traffic"); //Fields wrapper
        var add_button1 = $("#add_field_traffic"); //Add button ID

        var x = "<?php echo $j; ?>"; //initlal text box count
		var max_fields=20;
        $(add_button1).click(function(e) { //on add input button click
            e.preventDefault();
			if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper1).append('<div><input type="text" placeholder="Full Name"  id="Customertrafficmanager_full_name_' + x + '" name="Customertrafficmanager[traffic][' + x + '][full_name]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><input type="text"  id="Customertrafficmanager_full_mobile_' + x + '" name="Customertrafficmanager[traffic][' + x + '][mobile]" placeholder="Mobile" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><input type="text"  id="Customertrafficmanager_full_city_' + x + '" name="Customertrafficmanager[traffic][' + x + '][city]" placeholder="City" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><button class="remove_field" id="remove_field_traffic" >-</button></div>'); //add input box
            }
        });

        $(wrapper1).on("click", "#remove_field_traffic", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            //x--;
        })

    $('#Customer_type').live('click', function() {
//alert(this.value);
        /*if (this.value == 'C') {
         $('#field_ca').css('display', '');
         } else {
         $('#field_ca').css('display', 'none');
         }*/
		
		if(this.value=='TR'){
			$('#span_traffic_manager').css('display', '');
		}else{
			$('#span_traffic_manager').css('display', 'none');
		}

        if (this.value == 'C' || this.value == 'T') {
            $('#truck_list_table').css('display', '');
            $('#driver_list_table').css('display', '');
            $('#no_of_trucks').css('display', '');
            $('#id_truck_type').css('display', '');
            $('#tds_doc').css('display', '');
            $('#load').css('display', 'none');
			$('#DivCustomer_bank_name').css('display', '');
			$('#DivCustomer_bank_account_no').css('display', '');
			$('#DivCustomer_bank_branch').css('display', '');
			$('#DivCustomer_bank_ifsc_code').css('display', '');
		} else {
            $('#truck_list_table').css('display', 'none');
            $('#driver_list_table').css('display', 'none');
            $('#no_of_trucks').css('display', 'none');
            $('#id_truck_type').css('display', 'none');
            $('#tds_doc').css('display', 'none');
            $('#load').css('display', '');
			
			$('#DivCustomer_bank_name').css('display', 'none');
			$('#DivCustomer_bank_account_no').css('display', 'none');
			$('#DivCustomer_bank_branch').css('display', 'none');
			$('#DivCustomer_bank_ifsc_code').css('display', 'none');
        }

    });
</script>
<!-- <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Library::getGoogleMapsKey();?>&libraries=places"></script>
<script type="text/javascript">
    function initialize() {
        //alert('init')
        //var input = document.getElementsByName('Customer[operating_destination_city][]');
<?php for ($j = 1; $j <= $i; $j++) { ?>
            var input1 = document.getElementById('Customer_operating_destination_city_<?php echo $j; ?>');
            var autocomplete1 = new google.maps.places.Autocomplete(input1);

			var input2 = document.getElementById('Customer_operating_source_city_<?php echo $j; ?>');
            var autocomplete1 = new google.maps.places.Autocomplete(input2);
<?php } ?>
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php if (($model['c']->type == 'C' || $model['c']->type == 'T')) {
    echo "<script>$('#truck_list_table').css('display', '');  $('#driver_list_table').css('display', '');</script>";
}?>

<script type="text/javascript">
    //function fnKeyDownCustTruck(id){
function fnKeyDown(id){
    //alert($(obj).attr('id'));
    //var field=$(obj).attr('id');
    var value=$("#"+id).attr('value');
    //var id="806";
    $.ajax({url: "<?php echo $this->createUrl('leads/checkunique');?>",
            type:'POST',
            data:{'mobile':value},
			beforeSend:function(){
			$('#'+id).after('<div  id="Customer_mobile_loading"">checking..</div>');
			},
            success: function(result){
                $('#Customer_mobile_loading').remove();
				if(result["status"]){
                    $('#'+id).css('border','1px solid green');
					//$('#Customer_mobile_em_').removeClass("help-inline error");
					$('#Customer_mobile_em_').remove();
				}else{
					$('#'+id).css('border','1px solid red');
					$('#'+id).after('<div  id="Customer_mobile_em_">'+result["msg"]+'</div>');
					/*$('#Customer_mobile_em_').addClass("help-inline error");
					$('#Customer_mobile_em_').html(result["msg"]);
					$('#'+id).css('display','');*/
				}
            },
            dataType:'json'});
    

}
function fnKeyDowncomment(id){
//alert(id)
$(function() {
var availableTags = [
<?php foreach($model['existingCustomers'] as $row){ echo $pre.'"'.$row->idprefix.','.$row->fullname.','.Library::getCustomerType($row->type).','.$row->mobile.','.$row->email.','.$row->landline.'"';$pre=",";}?>
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

});
});
}


$("#yw0").click(function(){
if($("#Customer_operating_source_city_1").val()=="" || $("#Customer_operating_destination_city_1").val()==""){
	alert("operating source and destination are mandatory!!");
	$("#Customer_operating_source_city_1").focus();
	return false;
}else{
//alert("in else");
//return false
}
	//alert("clicked");
	//return false;
});

</script>