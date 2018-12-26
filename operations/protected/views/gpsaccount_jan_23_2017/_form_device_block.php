<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span12 pull-right" id="device_list_table">
                <?php
                $box = $this->beginWidget(
                        'bootstrap.widgets.TbBox', array(
                    'title' => 'Add Devices',
                    'htmlOptions' => array('class' => 'portlet-decoration	')
                        )
                );
                ?>
                <table class="table" id="table_upload_device">
                    <thead>
                        <tr>
                            <th>GpsDeviceID</th>
                            <th>SIM Phone No</th>
                            <th>SIM ID</th>
                            <th>IMEI No</th>
                            <th>Vehicle Model</th>
                            <th>Vehicle Make</th>
                            <th>Vehicle Type</th>
                            <th>TruckTypeID</th>
                            <th>Description</th>
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
                    $row = 0;
                    $aa = $_GET['ids'];
                    $devicesRow=GpsDevice::model()->findAll("accountID='$aa'");
                    //echo '<pre>';print_r($model);exit;
                        foreach ($devicesRow as $deviceRow):
                            //echo '<pre>';print_r($model['gd']);exit;
                            ?>
                            <tbody id='row-<?php echo $row; ?>'>
                                <tr >
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][deviceID]" value="<?php echo $deviceRow->deviceID; ?>"></td>
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][simPhoneNumber]" value="<?php echo $deviceRow->simPhoneNumber; ?>"></td>
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][simID]" value="<?php echo $deviceRow->simID; ?>"></td>
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][imeiNumber]" value="<?php echo $deviceRow->imeiNumber; ?>"></td>
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][vehicleModel]" value="<?php echo $deviceRow->vehicleModel; ?>"></td>
                                    <td><input type="text" name="GpsDevice[<?php echo $row; ?>][vehicleMake]" value="<?php echo $deviceRow->vehicleMake; ?>"></td>
                                    <td><?php echo CHtml::dropdownlist('GpsDevice[' . $row . '][vehicleType]', $deviceRow->vehicleType, array('TK' => 'TK', 'TR' => 'TR','NTK'=>'NTK')); ?></td>
                                    <!--<td><input type="text" name="GpsDevice[<?php echo $row; ?>][truckTypeId]" value="<?php echo $deviceRow->truckTypeId; ?>"></td>-->
                                    <td><?php echo CHtml::dropdownlist('GpsDevice[' . $row . '][truckTypeId]', $deviceRow->truckTypeId, $truckTypeArray); ?></td>
                                    <td><textarea name="GpsDevice[<?php echo $row; ?>][description]" rows="2" cols="30"> <?php echo $deviceRow->description; ?></textarea></td>
                                    <td><a onclick="$('#row-<?php echo $row; ?>').remove()" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                                </tr>
                            </tbody>
                            <?php
                            $row++;
                        endforeach;
                    ?>
                    <tfoot>
                        <tr>
                            <td colspan="5"><?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Add',
                                    'type' => 'btn-info',
                                    'htmlOptions' => array('onclick' => 'addDevice()'),
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
?>
<script type="text/javascript">
    var row_no =<?php echo $row; ?>;
    function addDevice()
    {
        row = '<tbody id="row-' + row_no + '">';
        row += '<tr>';
        
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][deviceID]"></td>';
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][simPhoneNumber]"></td>';
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][simID]"></td>';  
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][imeiNumber]"></td>';    
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][vehicleModel]"></td>';    
        row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][vehicleMake]"></td>';    
        row += '<td><select name="GpsDevice[' + row_no + '][vehicleType]"><option value="TK">TK</option><option value="TR">TR</option><option value="NTK">NTK</option></select></td>';    
        //row += '<td><input type="text" value=""  name="GpsDevice[' + row_no + '][truckTypeId]"></td>';    
        row += '<td><select name="GpsDevice[' + row_no + '][truckTypeId]"><?php echo $truck_type; ?></select></td>';
        row += '<td><textarea name="GpsDevice[' + row_no + '][description]" rows="2" cols="30"></textarea></td>';
        row += '<td></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#device_list_table tfoot').before(row);
        row_no++;
    }
</script>