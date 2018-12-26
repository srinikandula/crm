<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12 ">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
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
                                $model['c'], 'mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

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
                        ?></div>
                    <div class="span5">  <?php
                        echo $form->passwordFieldRow(
                                $model['c'], 'confirm', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>


                    <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model['c'], 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?></div>

                    <div class="span5">  <?php
                                echo $form->textFieldRow(
                                        $model['c'], 'no_of_vechiles', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                                );
                                ?></div>
                    <div class="span5"> <div class="control-group"><label class="control-label" for="ProductGroup_sort_order">Truck Types</label>
                            <div class="controls">
                                <?php
                                //echo '<pre>';print_r($model['truckTypes']);exit;
                                foreach ($model['truckTypes'] as $pro):
                                    $data['product'][$pro->id_truck_type] = $pro->title;
                                endforeach;
                                //echo '<pre>';print_r($data['product']);exit;

                                $selectedProductTypes = $model['cvt'];
                                //echo '<pre>';print_r($model['cvt']);exit;
                                foreach ($selectedProductTypes as $key => $product):
                                    $data['selected'][$key] = array('selected' => 'selected');
                                endforeach;

                                //echo '<pre>';print_r($selectedProductTypes); print_r($data); echo '</pre>';
                                ?>
                                <?php
                                $this->widget(
                                        'bootstrap.widgets.TbSelect2', array(
                                    'name' => 'id_truck_type',
                                    'data' => $data['product'],
                                    'options' => array(
                                        'placeholder' => 'Search Truck Type..',
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
echo $form->dropDownListRow($model['c'], 'year_in_service', Library::getExperienceYear(), array('prompt' => 'Year'));
$experience = (date(Y)) - ($model['c']->year_in_service);
?>
                    </div>
                    <div class="span1" id="experience"><?php echo $experience; ?>
                    </div>
                    <div class="span5">  <?php echo $form->dropDownListRow($model['c'], 'payment_type', Library::getPaymentTypes(), array('prompt' => 'select')); ?>
                    </div>

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

                    <div class="span5">  <?php
                        echo $form->radioButtonListRow($model['c'], 'status', array('1' => 'Enable', '0' => 'Disable'));
                        ?></div>

                        <?php if (!$model['c']->approved) { ?>
                        <div class="span5">  <?php
                        echo $form->radioButtonListRow($model['c'], 'approved', array('1' => 'Enable', '0' => 'Disable'));
                            ?></div>
                        <?php } ?>
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
                    <div class="span5">
                        <?php
                        echo $form->dropdownlistRow($model['c'], 'enable_sms_email_ads', Library::getSmsEmailStatus());
                        ?>
                    </div>

                        <?php if ((int) $_GET['id']) { ?>
                        <div class="span5">  <div class="control-group"><label for="Customer_password" class="control-label">Rating</label><div class="controls">
                            <?php echo $model['c']->rating; ?>
                                </div></div></div><?php } ?>

                    <div class="span12 pull-right" id="region_list">

<?php
$box = $this->beginWidget(
        'bootstrap.widgets.TbBox', array(
    'title' => 'Upload Customer Docs',
    'htmlOptions' => array('class' => 'portlet-decoration	')
        )
);
?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Upload</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th  width="8%">Action</th>
                                </tr>
                            </thead>

<?php
$row = 0;
if ($_GET['id'] != '') {
    foreach ($model['f'] as $fileRow):
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
                                            <td><?php echo CHtml::dropdownlist('Customerdocs[' . $row . '][doc_type]', $fileRow->doc_type, Library::getCustomerDocTypes()); ?></td>   
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
            </fieldset>
        </div>  
    </div>
<?php
$docTypes = "";
foreach (Library::getCustomerDocTypes() as $k => $v) {
    $docTypes.='<option value="' . $v . '" >' . $v . '</option>';
}
?>
    <script type='text/javascript'>
        var row_no =<?php echo $row; ?>;
        function addOption()
        {
            row = '<tbody id="row-' + row_no + '">';
            row += '<tr>';
            row += '<input type="hidden" value="" id="Customerdocs_upload_' + row_no + '_prev_image" name="Customerdocs[upload][' + row_no + '][prev_image]">';
            row += '<td><input id="Customerdocs_upload_' + row_no + '_image" name="Customerdocs[upload][' + row_no + '][image]" type="file">\n\
            <input id="ytproductimage_upload_' + row_no + '_image" type="hidden" name="Customerdocs[upload][' + row_no + '][image]" value=""></td>';
            row += '<td><select name="Customerdocs[upload][' + row_no + '][doc_type]"><?php echo $docTypes; ?></select></td>';
            row += '<td></td>';
            row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
            row += '</tr>';
            row += '</tbody>';
            $('.table tfoot').before(row);
            row_no++;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var max_fields = 20; //maximum input boxes allowed
            var wrapper = $("#input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = "<?php echo $i; ?>"; //initlal text box count
            $(add_button).click(function (e) { //on add input button click
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

            $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                //x--;
            })
        });
    </script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>


</div>
</fieldset>


</div>


</div>

</div>