<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
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

                    <?php if ((int) $_GET['id']) { ?>
                        <div class="span5">  <?php
                            echo $form->radioButtonListRow($model['c'], 'status', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>


                        <div class="span5">  <?php
                            echo $form->radioButtonListRow($model['c'], 'approved', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>
                        <input type="hidden" name="id" value="<?php echo (int) $_GET['id']; ?>">
                    <?php } ?>


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

     <?php if ($model['c']->type == 'C' || $model['c']->type == 'T') { ?>
        <div class="span12 pull-right" id="truck_list_table">
                <?php
                $box = $this->beginWidget(
                        'bootstrap.widgets.TbBox', array(
                    'title' => 'Registered Truck Details',
                    'htmlOptions' => array('class' => 'portlet-decoration	')
                        )
                );
                ?>
                <table class="table" id="table_upload_truck">
                    <thead>
                        <tr>
                            <th>Truck Reg No</th>
                            <th>Description</th>
                            <th>Source Address</th>
                            <th>Truck Type</th>
                            <th>Tracking Available</th>
                            <th>Insurance Available</th>
                            <th>Images</th>
                            <th  width="8%">Action</th>
                        </tr>
                    </thead>
                    <?php
                    $truck_type = "";
                    $truckTypeArray = array();
                    foreach (Trucktype::model()->findAll() as $ttypeRow) {
                        $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
                        $truckTypeArray[$ttypeRow->id_truck_type] = $ttypeRow->title;
                    }
                    $row_truck = 0;
                    
                    foreach ($model['t'] as $tObj) {
                        //echo '<pre>';print_r($model['t']);exit;
                        ?>
                        <tbody id="row1-<?php echo $row_truck; ?>">
                            <tr><td><input type="text" name="Truck[<?php echo $row_truck; ?>1][truck_reg_no]" value="<?php echo $tObj->truck_reg_no; ?>"></td>
                                <td><textarea name="Truck[<?php echo $row_truck; ?>1][description]" rows="2" cols="30"> <?php echo $tObj->description; ?></textarea></td>
                                <td><input type="text" name="Truck[<?php echo $row_truck; ?>1][source_address]"  value="<?php echo $tObj->source_address; ?>"></td>
                                <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][id_truck_type]', $tObj->id_truck_type, $truckTypeArray); ?></td>
                                <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][tracking_available]', $tObj->tracking_available, array('1' => 'yes', '0' => 'no')); ?></td>
                                <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][insurance_available]', $tObj->insurance_available, array('1' => 'yes', '0' => 'no')); ?></td>
                                <td><input id="Truckdoc_upload_<?php echo $row_truck; ?>1_image" name="Truckdoc[upload][<?php echo $row_truck; ?>1][1][image]" type="file"><input id="Truckdoc_upload_<?php echo $row_truck; ?>2_image" name="Truckdoc[upload][<?php echo $row_truck; ?>1][2][image]" type="file"><input id="Truckdoc_upload_<?php echo $row_truck; ?>3_image" name="Truckdoc[upload][<?php echo $row_truck; ?>1][3][image]" type="file"><input id="Truckdoc_upload_<?php echo $row_truck; ?>4_image" name="Truckdoc[upload][<?php echo $row_truck; ?>1][4][image]" type="file"><input id="Truckdoc_upload_<?php echo $row_truck; ?>5_image" name="Truckdoc[upload][<?php echo $row_truck; ?>1][5][image]" type="file"></td>
                                <td> <a onclick="$(\'#row1-<?php echo $row_truck; ?>\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                            </tr>
                        </tbody>
        <?php $row_truck++;
    } ?>
                    <tfoot>
                        <tr>
                            <td colspan="8"><?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Add',
                                    'type' => 'btn-info',
                                    'htmlOptions' => array('onclick' => 'addTruck()'),
                                        )
                                );
                                ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php $this->endWidget(); ?>
            </div>
<?php } ?>

        
            <?php if ((int) $_GET['id']) { ?>
            <div class="span12 pull-right" id="region_list">

                <?php
                $box = $this->beginWidget(
                        'bootstrap.widgets.TbBox', array(
                    'title' => 'Add/Edit Customer Docs',
                    'htmlOptions' => array('class' => 'portlet-decoration	')
                        )
                );
                ?>
                <table class="table" id="table_upload">
                    <thead>
                        <tr>
                            <th>Upload</th>
                            <th>File</th>
                            <th  width="8%">Action</th>
                        </tr>
                    </thead>

                    <?php
                    $row = 0;
                    if ($_GET['id'] != '') {
                        foreach ($model['cd'] as $fileRow):
                            ?>
                            <tbody id='row-<?php echo $row; ?>'>
                                <tr >
                                    <?php
                                    echo CHtml::activeHiddenField($fileRow, 'prev_image', array('name' => 'Customerdocs[upload][' . $row . '][prev_image]',
                                        'value' => $fileRow->file));
                                    ?>
                                    <td>
                                        <div class="span5 uploading-img-main"> 
                                            <div class="control-group">
                                                <div class="controls">
                                                    <input type="file" id="Customerdocs[upload][<?php echo $row; ?>][image]?>" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                                           name="Customerdocs[upload][<?php echo $row; ?>][image]" data-original-title="Upload category logo from your computer">
                                                    <div style="display:none" id="Customerdocs[upload][<?php echo $row; ?>][image]" class="help-inline error"></div><p class="help-block"></p>


                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <td><div  id="image-name-display-id"><?php echo $fileRow->file; ?><img src="<?php echo Library::getMiscUploadLink() . $fileRow->file; ?>" width="200px" height="200px" >
                                            <div class="logo-img">
                                                <img   src="<?php echo Library::getMiscUploadLink() . $fileRow->file; ?>">
                                            </div>
                                        </div></td>

                                    <td><a onclick="$('#row-<?php echo $row; ?>').remove()" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                                </tr>
                            </tbody>
                            <?php
                            $row++;
                        endforeach;
                    }
                    ?>


                    <tfoot>
                        <tr>
                            <td colspan="3"><?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Add',
                                    'type' => 'btn-info',
                                    'htmlOptions' => array('onclick' => 'addOption()'),
                                        )
                                );
                                ?></td>
                        </tr>
                    </tfoot>
                </table>
    <?php $this->endWidget(); ?>


            </div>

            <div class="span6" id="pull_left">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Post Lead Status/Comment</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                        <table class="table uploading-status" border='0'>
                            <thead>
                                <tr>
                                    <th>Lead Status</th>
                                    <th>Comment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
                                <tr>
                                    <td><?php echo CHtml::dropDownList('Customerleadstatushistory[status]', '', Library::getLeadStatuses()); ?></td>
                                    <td><textarea id="Customerleadstatushistory_message" name="Customerleadstatushistory[message]" cols="30" rows="3"></textarea></td>
                                    <td><?php
                                        echo CHtml::ajaxButton("Submit", $this->createUrl('leads/updateStatus', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
location=window.location.href;	
}',), array('confirm' => 'Are you sure.you want to update the status??'));
                                        ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table> </div>
                </fieldset>
            </div> 
            <div class="span6 pull-right" id="pull_top1">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Post Document Collection Message</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                        <table class="table uploading-status" border='0'>
                            <thead>
                                <tr>
                                    <th>Assign To</th>
                                    <th>Message</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
                                <tr>
                                    <td><?php echo CHtml::dropDownList('Customerleadassignment[id_admin_to]', '', CHtml::listData($model['field'], 'id_admin', 'first_name')); ?></td>
                                    <td><textarea id="Customerleadassignment_message" name="Customerleadassignment[message]" cols="30" rows="3"></textarea></td>
                                    <td><?php
                                        echo CHtml::ajaxButton("Submit", $this->createUrl('leads/updateLeadAssigment', array('id' => (int) $_GET['id'])), array('dataType' => 'text', 'type' => 'post', 'success' => 'function(data){
location=window.location.href;	
}',), array('confirm' => 'Are you sure.you want to proceed??'));
                                        ?></td>
                                </tr>
                                <tr><td colspan="3"><div>Note:Message should contain appointment date/time,doc list to be collected and address.</div></td></tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table> </div>
                </fieldset>
            </div>    


            <div class="span6" id="pull_top2">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Assignment</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5" >

                        <table class="table uploading-status" border='0' >
                            <thead>
                                <tr>
                                    <th>Assigned By</th>
                                    <th>Assigned To</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody id="grand_total">
    <?php foreach ($model['cla'] as $cla) { ?>
                                    <tr>
                                        <td><?php echo $cla['from_admin']; ?></td>
                                        <td><?php echo $cla['to_admin']; ?></td>
                                        <td><?php echo $cla['message']; ?></td>
                                        <td><?php echo $cla['status']; ?></td>
                                        <td><?php echo $cla['date_created']; ?></td>
                                    </tr>
    <?php } ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>

                </fieldset>
            </div>

            <div class="span6 pull-right" id="pull_top3">
                <fieldset class="portlet " >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Status History</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                        <div id="for_scroll">
                            <table class="table uploading-status" border='0'>
                                <thead>
                                    <tr>
                                        <th>Updated By</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody id="grand_total">
    <?php foreach ($model['clsh'] as $clsh) { ?>
                                        <tr>
                                            <td><?php echo $clsh['admin']; ?></td>
                                            <td><?php echo $clsh['status']; ?></td>
                                            <td><?php echo $clsh['message']; ?></td>
                                            <td><?php echo $clsh['date_created']; ?></td>
                                        </tr>
    <?php } ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table> 
                        </div>
                </fieldset>
            </div><?php } ?> 
    </div>

</div>
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

    var row_no =<?php echo $row; ?>;
    function addOption()
    {
        row = '<tbody id="row-' + row_no + '">';
        row += '<tr>';
        row += '<input type="hidden" value="" id="Customerdocs_upload_' + row_no + '_prev_image" name="Customerdocs[upload][' + row_no + '][prev_image]">';
        row += '<td><input id="Customerdocs_upload_' + row_no + '_image" name="Customerdocs[upload][' + row_no + '][image]" type="file">\n\
    <input id="ytproductimage_upload_' + row_no + '_image" type="hidden" name="Customerdocs[upload][' + row_no + '][image]" value=""></td>';
        row += '<td></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_upload tfoot').before(row);
        row_no++;
    }
<?php
$truck_type = "";
foreach (Trucktype::model()->findAll() as $ttypeRow) {
    //$truck_type.="<option value='".$ttypeRow->id_truck_type."' >".$ttypeRow->title." ".$ttypeRow->tonnes."</option>";
    $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
}
?>
    var row_no = '<?php echo $row_truck; ?>';
    function addTruck()
    {
        row = '<tbody id="row1-' + row_no + '">';
        row += '<tr>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][truck_reg_no]"></td>';
        row += '<td><textarea name="Truck[' + row_no + '1][description]" rows="2" cols="30"></textarea></td>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][source_address]"></td>';
        row += '<td><select name="Truck[' + row_no + '1][id_truck_type]"><?php echo $truck_type; ?></select></td>';
        row += '<td><select name="Truck[' + row_no + '1][tracking_available]"><option value="1">Yes</option><option value="0">No</option></select></td>';
        row += '<td><select name="Truck[' + row_no + '1][insurance_available]"><option value="1">Yes</option><option value="0">No</option></select></td>';
        row += '<td><input id="Truckdoc_upload_' + row_no + '1_image" name="Truckdoc[upload][' + row_no + '1][1][image]" type="file"><input id="Truckdoc_upload_' + row_no + '2_image" name="Truckdoc[upload][' + row_no + '1][2][image]" type="file"><input id="Truckdoc_upload_' + row_no + '3_image" name="Truckdoc[upload][' + row_no + '1][3][image]" type="file"><input id="Truckdoc_upload_' + row_no + '4_image" name="Truckdoc[upload][' + row_no + '1][4][image]" type="file"><input id="Truckdoc_upload_' + row_no + '5_image" name="Truckdoc[upload][' + row_no + '1][5][image]" type="file"></td>';
        row += '<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_upload_truck tfoot').before(row);
        row_no++;
    }
   
</script>