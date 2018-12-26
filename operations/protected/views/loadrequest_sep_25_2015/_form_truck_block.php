    <div class="span12 pull-right" id="truck_list_table">
        <?php
        $box = $this->beginWidget(
                'bootstrap.widgets.TbBox', array(
            'title' => 'Truck Details',
            'htmlOptions' => array('class' => 'portlet-decoration	')
                )
        );
        ?>
        
        <table class="table" id="table_upload_truck">
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Prices</th>
                    <th>Truck Reg No</th>
                    <th>Make Year</th>
                    <th>Make Month</th>
                    <th>Goods Type</th>
                    <th>Truck Type</th>
                    <th>Tracking Available</th>
                    <th>Insurance Available</th>
                    <th>Date Time Available</th>
                    <th>Expected Date Return</th>
                    <th>Comment/Driver Info</th>
                    <th  width="2%">Action</th>
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
                ?>
                <tbody id="row1-<?php echo $row_truck; ?>">
                    <tr><td><input type="text" name="Truck[<?php echo $row_truck; ?>1][source_city]" value="<?php echo $tObj->source_city; ?>"></td>
                    <tr><td><input type="text" name="Truck[<?php echo $row_truck; ?>1][destination_city]" value="<?php echo $tObj->destination_city; ?>"></td>
                        <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][id_goods_type]', $tObj->id_goods_type, $truckTypeArray); ?></td>
                        <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][id_truck_type]', $tObj->id_truck_type, $truckTypeArray); ?></td>
                        <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][tracking_available]', $tObj->tracking_available, array('1' => 'yes', '0' => 'no')); ?></td>
                        <td><?php echo CHtml::dropdownlist('Truck[' . $row_truck . '1][insurance_available]', $tObj->insurance_available, array('1' => 'yes', '0' => 'no')); ?></td>
                        <td>date</td>
                        <td> <a onclick="$(\'#row1-<?php echo $row_truck; ?>\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                    </tr>
                    
                </tbody>
                <?php $row_truck++;
            }
            ?>
            <tfoot>
                <tr>
                    <td colspan="14"><?php
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



<?php
$truck_type = "";
foreach (Trucktype::model()->findAll() as $ttypeRow) {
    $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
}

$goods_type = "";
foreach (Goodstype::model()->findAll() as $ttypeRow) {
    $goods_type.='<option value="' . $ttypeRow->id_goods_type . '" >' . $ttypeRow->title . '</option>';
}

$make_year = "";
foreach (Library::getExperienceYear() as $year) {
    $make_year.='<option value="' . $year . '" >' . $year . '</option>';
}
$make_month = "";
foreach (Library::getMakeMonths() as $monthKey=>$monthValue) {
    $make_month.='<option value="' . $monthKey . '" >' . $monthValue . '</option>';
}
?>
<script type="text/javascript">

                    function datetime() {
                        $('.datetimepicker,.datetimepicker1').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            format: 'Y-m-d H:i',
                            startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                        });
                    }

                    
                    var row_no = '<?php echo $row_truck; ?>';
                    //id="Truck_' + row_no + '1_destination_address"
                    
                    function addTruck()
                    {
                        row = '<tbody id="row1-' + row_no + '">';
                        row += '<tr>';
                        row += '<td><input placeholder="source" type="text" id="Truck_' + row_no + '1_source_address" name="Truck[' + row_no + '1][source_address]"></td>';
                        row += '<td><input placeholder="Destination 1" type="text" id="Truck_' + row_no + '10_destination_address" name="Truck[' + row_no + '1][destination_address][0]" ><input placeholder="Destination 2" type="text"  id="Truck_' + row_no + '11_destination_address" name="Truck[' + row_no + '1][destination_address][1]" ><input placeholder="Destination 3" type="text" id="Truck_' + row_no + '12_destination_address" name="Truck[' + row_no + '1][destination_address][2]" ></td>';
                        row += '<td><input placeholder="Price 1" type="text" style="width:50px" name="Truck[' + row_no + '1][price][0]" ><input placeholder="Price 2" style="width:50px" type="text"  name="Truck[' + row_no + '1][price][1]" ><input placeholder="Price 3" type="text"  name="Truck[' + row_no + '1][price][2]" style="width:50px" ></td>';
						//row += '<td><textarea placeholder="Price" type="text"  name="Truck[' + row_no + '1][price]" rows="2" cols="20"></textarea></td>';
                        row += '<td><input type="text" name="Truck[' + row_no + '1][truck_reg_no]" ></td>';
                        row += '<td><select name="Truck[' + row_no + '1][make_year]"><?php echo $make_year; ?></select></td>';
			row += '<td><select name="Truck[' + row_no + '1][make_month]"><?php echo $make_month; ?></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][id_goods_type]"><?php echo $goods_type; ?></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][id_truck_type]"><?php echo $truck_type; ?></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][tracking]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][insurance]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                        row += '<td><input type="text" name="Truck[' + row_no + '1][date_available]" class="datetimepicker"></td>';
                        row += '<td><input type="text" name="Truck[' + row_no + '1][expected_return]" class="datetimepicker1"></td>';
                        row += '<td><textarea name="Truck[' + row_no + '1][add_info]" rows="2" cols="5"></textarea></td>';
                        row += '<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
                        row += '</tr>';
                        row += '</tbody>';
                        $('#table_upload_truck tfoot').before(row);
                        var input = document.getElementById('Truck_' + row_no + '1_source_address');
                        var autocomplete = new google.maps.places.Autocomplete(input);

                        var input1 = document.getElementById('Truck_' + row_no + '10_destination_address');
                        var autocomplete = new google.maps.places.Autocomplete(input1);

						var input1 = document.getElementById('Truck_' + row_no + '11_destination_address');
                        var autocomplete = new google.maps.places.Autocomplete(input1);

						var input1 = document.getElementById('Truck_' + row_no + '12_destination_address');
                        var autocomplete = new google.maps.places.Autocomplete(input1);
                        row_no++;
                        datetime();
                }
</script>

