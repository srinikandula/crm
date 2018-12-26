<?php 
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12 pull-right" id="truck_list_table" style="display:none">
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
                        <th>Make</th>
                        <th>Source Address</th>
                        <th>Truck Type</th>
                        <th>Tracking Available</th>
                        <th>GPS imei No</th>
                        <th>GPS Mobile No</th>
                        <th>Insurance Available</th>
                        <th>Insurance Exp Date</th>
                        <th>Fitness Exp Date</th>
                        <th>Mileage</th>
                        <th>Chasis No</th>
                        <th>Engine No</th>
                        <th>Truck Docs</th>
                        <th>Truck Pics</th>
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
                            <td><?php echo CHtml::dropdownlist('Truck['.$row_truck.'1][make_month]',$tObj->make_month,Library::getMakeMonths());echo CHtml::dropdownlist('Truck['.$row_truck.'1][make_year]',$tObj->make_year,Library::getMakeYears());?></td>
                            <td><input type="text" name="Truck[<?php echo $row_truck; ?>1][source_address]"  value="<?php echo $tObj->source_address; ?>"></td>
                            <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][id_truck_type]', $tObj->id_truck_type, $truckTypeArray); ?></td>
                            <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][tracking_available]', $tObj->tracking_available, array('1' => 'yes', '0' => 'no'),array('onchange'=>'fnTrackChange(this)')); ?></td>
                            <td><input id="emie_change" type="text" name="Truck[<?php echo $row_truck; ?>1][gps_imei_no]" value="<?php echo $tObj->gps_imei_no; ?>"></td>
                            <td><input id="mob_change" type="text" name="Truck[<?php echo $row_truck; ?>1][gps_mobile_no]" value="<?php echo $tObj->gps_mobile_no; ?>"></td>
                            <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][insurance_available]', $tObj->insurance_available, array('1' => 'yes', '0' => 'no')); ?></td>
                            <td>
<input type="text" name="Truck[<?php echo $row_truck; ?>1][vehicle_insurance_expiry_date]" class="date" value="<?php echo $tObj->vehicle_insurance_expiry_date; ?>"></td>
                            <td>
<input type="text" name="Truck[<?php echo $row_truck; ?>1][fitness_certificate_expiry_date]" class="date" value="<?php echo $tObj->fitness_certificate_expiry_date; ?>"></td>
                            <td><input id="mileage" type="text" name="Truck[<?php echo $row_truck; ?>1][mileage]" value="<?php echo $tObj->mileage; ?>"></td>
                            <td><input id="chasis_no" type="text" name="Truck[<?php echo $row_truck; ?>1][chasis_no]" value="<?php echo $tObj->chasis_no; ?>"></td>
                            <td><input id="engine_no" type="text" name="Truck[<?php echo $row_truck; ?>1][engine_no]" value="<?php echo $tObj->engine_no; ?>"></td>
                            <td>Vehicle Insurance<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_vehicle_insurance" name="Truckdoc[upload][<?php echo $row_truck; ?>1][vehicle_insurance]" type="file"><?php Library::imagePopup($tObj->vehicle_insurance,Library::getTruckUploadLink());?><br/>
                                Fitness Certificate<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_fitness_certificate" name="Truckdoc[upload][<?php echo $row_truck; ?>1][fitness_certificate]" type="file"><?php Library::imagePopup($tObj->fitness_certificate,Library::getTruckUploadLink());?><br/>
                               Vechile Rc <input id="Truckdoc_upload_<?php echo $row_truck; ?>1_vehicle_rc" name="Truckdoc[upload][<?php echo $row_truck; ?>1][vehicle_rc]" type="file"><?php Library::imagePopup($tObj->vehicle_rc,Library::getTruckUploadLink());?></td>
                            <td>Front Pic:<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_front_pic" name="Truckdoc[upload][<?php echo $row_truck; ?>1][front_pic]" type="file"><?php Library::imagePopup($tObj->front_pic,Library::getTruckUploadLink());?><br/>Back Pic:<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_back_pic" name="Truckdoc[upload][<?php echo $row_truck; ?>1][back_pic]" type="file"><?php Library::imagePopup($tObj->back_pic,Library::getTruckUploadLink());?><br/>Left Pic:<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_left_pic" name="Truckdoc[upload][<?php echo $row_truck; ?>1][left_pic]" type="file"><?php Library::imagePopup($tObj->left_pic,Library::getTruckUploadLink());?><br/>Right Pic:<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_right_pic" name="Truckdoc[upload][<?php echo $row_truck; ?>1][right_pic]" type="file"><?php Library::imagePopup($tObj->right_pic,Library::getTruckUploadLink());?><br/>Top Pic<input id="Truckdoc_upload_<?php echo $row_truck; ?>1_top_pic" name="Truckdoc[upload][<?php echo $row_truck; ?>1][top_pic]" type="file"><?php Library::imagePopup($tObj->top_pic,Library::getTruckUploadLink());?>
                                <input type="hidden" name="Truck[<?php echo $row_truck; ?>1][vehicle_insurance_prev]" value="<?php echo $tObj->vehicle_insurance; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][fitness_certificate_prev]" value="<?php echo $tObj->fitness_certificate; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][vehicle_rc_prev]" value="<?php echo $tObj->vehicle_rc; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][front_pic_prev]" value="<?php echo $tObj->front_pic; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][back_pic_prev]" value="<?php echo $tObj->back_pic; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][left_pic_prev]" value="<?php echo $tObj->left_pic; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][right_pic_prev]" value="<?php echo $tObj->right_pic; ?>">
<input type="hidden" name="Truck[<?php echo $row_truck; ?>1][top_pic_prev]" value="<?php echo $tObj->top_pic; ?>">
                            </td>
                            <td> <a onclick="$('#row1-<?php echo $row_truck; ?>').remove();" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                        </tr>
                    </tbody>
                    <?php $row_truck++;
                }
                ?>
                <tfoot>
                    <tr>
                        <td colspan="12"><?php
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
    </div>

</div>
<?php
$truck_type = "";
foreach (Trucktype::model()->findAll() as $ttypeRow) {
    $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
}
$make_month="";
foreach(Library::getMakeMonths() as $k=>$v){
    $make_month.='<option value="' . $k . '" >' . $v . '</option>';
}

$make_year="";
foreach(Library::getMakeYears() as $k=>$v){
    $make_year.='<option value="' . $k . '" >' . $v . '</option>';
}?>

<script type="text/javascript">

    /*$('#Customer_type input[type=\'radio\']').live('click', function() {

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

    });*/

    var row_no = '<?php echo $row_truck; ?>';
    function addTruck()
    {
        row = '<tbody id="row1-' + row_no + '">';
        row += '<tr>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][truck_reg_no]"></td>';
        row += '<td><textarea name="Truck[' + row_no + '1][description]" rows="2" cols="30"></textarea></td>';
        row += '<td><select name="Truck[' + row_no + '1][make_month]"><?php echo $make_month; ?></select><select name="Truck[' + row_no + '1][make_year]"><?php echo $make_year; ?></select></td>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][source_address]"></td>';
        row += '<td><select name="Truck[' + row_no + '1][id_truck_type]"><?php echo $truck_type; ?></select></td>';
        row += '<td><select onchange="fnsh(this);" name="Truck[' + row_no + '1][tracking_available]"><option value="1">Yes</option><option value="0">No</option></select></td>';
        row += '<td><input id="gps_imei" type="text" name="Truck[' + row_no + '1][gps_imei_no]"></td>';
        row += '<td><input id="gps_mob" type="text" name="Truck[' + row_no + '1][gps_mobile_no]"></td>';
        row += '<td><select name="Truck[' + row_no + '1][insurance_available]"><option value="1">Yes</option><option value="0">No</option></select></td>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][vehicle_insurance_expiry_date]" class="date"></td>';
        row += '<td><input type="text" name="Truck[' + row_no + '1][fitness_certificate_expiry_date]"  class="date" ></td>';
        row += '<td><input id="mileage" type="text" name="Truck[' + row_no + '1][mileage]"></td>';
        row += '<td><input id="chasis_no" type="text" name="Truck[' + row_no + '1][chasis_no]"></td>';
        row += '<td><input id="engine_no" type="text" name="Truck[' + row_no + '1][engine_no]"></td>';
        row += '<td>Vehicle Insurance<input type="file" name="Truckdoc[upload][' + row_no + '1][vehicle_insurance]" id="Truckdoc_upload_' + row_no + '1_vehicle_insurance"><br>Fitness Certificate<input type="file" name="Truckdoc[upload][' + row_no + '1][fitness_certificate]" id="Truckdoc_upload_' + row_no + '1_fitness_certificate"><br>Vechile Rc <input type="file" name="Truckdoc[upload][' + row_no + '1][vehicle_rc]" id="Truckdoc_upload_' + row_no + '1_vehicle_rc"></td>';
        row += '<td>Front Pic:<input type="file" name="Truckdoc[upload][' + row_no + '1][front_pic]" id="Truckdoc_upload_' + row_no + '1_front_pic"><br>Back Pic:<input type="file" name="Truckdoc[upload][' + row_no + '1][back_pic]" id="Truckdoc_upload_' + row_no + '1_back_pic"><br>Left Pic:<input type="file" name="Truckdoc[upload][' + row_no + '1][left_pic]" id="Truckdoc_upload_' + row_no + '1_left_pic"><br>Right Pic:<input type="file" name="Truckdoc[upload][' + row_no + '1][right_pic]" id="Truckdoc_upload_' + row_no + '1_right_pic"><br>Top Pic<input type="file" name="Truckdoc[upload][' + row_no + '1][top_pic]" id="Truckdoc_upload_' + row_no + '1_top_pic"></td>';     
        row += '<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_upload_truck tfoot').before(row);
        jQuery('.date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
        row_no++;
    }
jQuery('.date').datepicker({'dateFormat': 'yy-mm-dd', 'altFormat': 'dd-mm-yy', 'changeMonth': 'true', 'changeYear': 'true'});
</script>
<script type="text/javascript">
                function fnsh(id) {
                            if (id.value == '1') {
                                 //$('#gps_imei').show();
                                 //$('#gps_mob').show();
                                 $('#gps_mob').removeAttr("readonly");
                                 $('#gps_imei').removeAttr("readonly");
                            } else {
                            //if(id.value == '0'){
                                //$('#gps_imei').hide();
                                //$('#gps_mob').hide();
                                
                                var anchor1 = document.getElementById("gps_mob");
                                var att = document.createAttribute("readonly");
                                att.value = "readonly";
                                anchor1.setAttributeNode(att);
                                
                                var anchor = document.getElementById("gps_imei");
                                var attr = document.createAttribute("readonly");
                                attr.value = "readonly";
                                anchor.setAttributeNode(attr);
                                
                                $("#gps_mob,#gps_imei").val('');
                            }
                        }
//function fnTrackChange(ref){
  //  alert(ref.value)
//}

</script>
<script type="text/javascript">
                function fnTrackChange(id) {
                            if (id.value == '1') {
                                 $('#emie_change').removeAttr("readonly");
                                 $('#mob_change').removeAttr("readonly");
                            } else {
                                var anchor1 = document.getElementById("emie_change");
                                var att = document.createAttribute("readonly");
                                att.value = "readonly";
                                anchor1.setAttributeNode(att);
                                
                                var anchor2 = document.getElementById("mob_change");
                                var att2 = document.createAttribute("readonly");
                                att2.value = "readonly";
                                anchor2.setAttributeNode(att2);
                                $("#emie_change,#mob_change").val('');
                               }
                        }
</script>