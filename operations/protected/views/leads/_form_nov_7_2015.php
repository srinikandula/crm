
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
				
                <div>Note:1.Automatic password will be generated and mailed when approved.</div>
                <div class="portlet-content" id="hide_box_line1">
                    <div class="span5">  <?php
                        $cTypes = Library::getCustomerTypes();
                        unset($cTypes['G']);
                        //echo '<pre>';print_r($cTypes);echo '</pre>';
                        echo $form->radioButtonListRow($model['c'], 'type', $cTypes);
                        ?></div>
                    <?php //echo '<pre>';print_r($model['cod']);echo '</pre>';?>
                    <div class="span5" id="field_ca" >  <div class="control-group"><label for="Customer_operating_destination_city" class="control-label">Operating Routes</label><div class="controls" id="input_fields_wrap"><button class="add_field_button">+</button>
                                <?php /* if ($_GET['id'] == '') { ?>                               
                                  <div><input type="text"  id="Customer_operating_destination_city_1" name="Customer[operating_destination_city][]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $model['cod']['0']->address ?>"></div><?php } */ ?>
                                <?php
                                $i = 1;
                                foreach ($model['cod'] as $codObj) {
                                    ?>
                                    <div><input type="text" placeholder="source"  id="Customer_operating_source_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->source_address ?>"><input type="text" placeholder="destination" id="Customer_operating_destination_city_<?php echo $i; ?>" name="Customer[operating_city][<?php echo $i; ?>][destination]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title="" value="<?php echo $codObj->destination_address ?>"><button class="remove_field"  href="#">-</button></div>

                                    <?php
                                    $i++;
                                }
                                ?>
                            </div></div></div> 

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'fullname', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>
                    <div class="span5 uploading-img-main"> <?php
                        echo $form->fileFieldRow(
                                $model['c'], 'profile_image', array('name' => 'image', 'rel' => 'tooltip',
                            'data-toggle' => "tooltip", 'data-placement' => "right",
                            'class' => 'Options_design'), array('hint' => '<div class="logo-img"><img src="' . Library::getMiscUploadLink() . $model['c']->profile_image . '"><input type="hidden" name="prev_file" value="' . $model['c']->profile_image . '"></div>')
                        );
                        echo '<p class="image-name-display">' . $model['c']->profile_image . '</p>';
                        ?>
                    </div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'email', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5" id="no_of_trucks" <?php if ($model['c']->type != 'C' && $model['c']->type != 'T') { ?>style="display:none" <?php } ?> >  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'no_of_vechiles', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
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


                    <div class="span5">  <?php
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
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textAreaRow(
                                $model['c'], 'address', array('rows' => 3, 'cols' => 50)
                        );
                        ?></div>

                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'operating_source_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

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
                                $model['c'], 'landline', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php echo $form->dropDownListRow($model['c'], 'payment_type', Library::getPaymentTypes(), array('prompt' => 'select')); ?>
                    </div>
                    <div class="span5">
                        <?php
                        echo $form->textFieldRow(
                                $model['c'], 'bank_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                    </div>
                    <div class="span5">
<?php
echo $form->textFieldRow(
        $model['c'], 'bank_account_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
);
?>
                    </div>
                    <div class="span5">
<?php
echo $form->textFieldRow(
        $model['c'], 'bank_ifsc_code', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
);
?>
                    </div>
                    <div class="span5">
                    <?php
                    echo $form->textFieldRow(
                            $model['c'], 'bank_branch', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                    );
                    ?>
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
                </div>
            </fieldset>


        </div>
    </div>
</div>

     <?php 
            $this->renderPartial('_form_customer_doc_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_truck_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_driver_block', array('form'=>$form,'model'=>$model));
            /*$this->renderPartial('_form_status_comment_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_doc_collection_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_lead_assignment_block', array('form'=>$form,'model'=>$model));
            $this->renderPartial('_form_status_history_block', array('form'=>$form,'model'=>$model));*/
        ?>
   
<script type="text/javascript">
    $(document).ready(function() {
        var max_fields = 20; //maximum input boxes allowed
        var wrapper = $("#input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = "<?php echo $i; ?>"; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div><input type="text" placeholder="Source"  id="Customer_operating_source_city_' + x + '" name="Customer[operating_city][' + x + '][source]" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><input type="text"  id="Customer_operating_destination_city_' + x + '" name="Customer[operating_city][' + x + '][destination]" placeholder="Destination" data-placement="right" data-toggle="tooltip" rel="tooltip" data-original-title="" title=""><button class="remove_field" >-</button></div>'); //add input box
                //initialize();
                var input2 = document.getElementById('Customer_operating_destination_city_' + x);
                var autocomplete2 = new google.maps.places.Autocomplete(input2);
                var input2 = document.getElementById('Customer_operating_source_city_' + x);
                var autocomplete2 = new google.maps.places.Autocomplete(input2);
            }
        });

        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            //x--;
        })
    });

    $('#Customer_type input[type=\'radio\']').live('click', function() {
//alert(this.value);
        /*if (this.value == 'C') {
         $('#field_ca').css('display', '');
         } else {
         $('#field_ca').css('display', 'none');
         }*/

        if (this.value == 'C' || this.value == 'T') {
            $('#truck_list_table').css('display', '');
            $('#driver_list_table').css('display', '');
            $('#no_of_trucks').css('display', '');
            $('#id_truck_type').css('display', '');
        } else {
            $('#truck_list_table').css('display', 'none');
            $('#driver_list_table').css('display', 'none');
            $('#no_of_trucks').css('display', 'none');
            $('#id_truck_type').css('display', 'none');
        }

    });
</script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">
    function initialize() {
        //alert('init')
        //var input = document.getElementsByName('Customer[operating_destination_city][]');
<?php for ($j = 1; $j <= $i; $j++) { ?>
            var input1 = document.getElementById('Customer_operating_destination_city_<?php echo $j; ?>');
            var autocomplete1 = new google.maps.places.Autocomplete(input1);
<?php } ?>
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php if (($model['c']->type == 'C' || $model['c']->type == 'T')) {
    echo "<script>$('#truck_list_table').css('display', '');  $('#driver_list_table').css('display', '');</script>";
}?>