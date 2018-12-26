    <div class="span12 pull-right" id="truck_list_table">
      
  <?php
$truck_type = "";
foreach (Trucktype::model()->findAll() as $ttypeRow) {
    $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
}

$goods_type = "";
foreach (Goodstype::model()->findAll() as $ttypeRow) {
    $goods_type.='<option value="' . $ttypeRow->id_goods_type . '" >' . $ttypeRow->title . '</option>';
    $goodsTypeArray[$ttypeRow->id_goods_type] = $ttypeRow->title;
}
?>

      <?php
            $truck_type = "";
            $truckTypeArray = array();
            foreach (Trucktype::model()->findAll() as $ttypeRow) {
                $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
                $truckTypeArray[$ttypeRow->id_truck_type] = $ttypeRow->title . ' ' . $ttypeRow->tonnes;
            }
            
            $row_truck = 1;
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
                    <th>Goods Type</th>
                    <th>Truck Type</th>
                    <th>Expected Price</th>
                    <th>Tracking Available</th>
                    <th>Insurance Available</th>
                    <th>Date Time Required</th>
                    <th>Pickup Point</th>
                    <th>Response Time/Comment</th>
                    <!--<th  width="8%">Action</th>-->
                </tr>
                <tr>
                        <td><input placeholder="source" type="text" id="Truck_01_source_address" name="Truck[01][source_address]"></td>
                        <td><input placeholder="destination" type="text" id="Truck_01_destination_address" name="Truck[01][destination_address]"></td>
                        <td><?php echo CHtml::dropdownlist('Truck[01][id_goods_type]', $tObj->id_goods_type, $goodsTypeArray); ?></td>
                        <td><?php echo CHtml::dropdownlist('Truck[01][id_truck_type]', $tObj->id_truck_type, $truckTypeArray); ?></td>
                        <td><input placeholder="price" class="price" type="text" id="Truck_01_expected_price" name="Truck[01][expected_price]"></td>
                        <td><select name="Truck[01][tracking]"><option value="1">Yes</option><option value="0">No</option></select></td>
                        <td><select name="Truck[01][insurance]"><option value="1">Yes</option><option value="0">No</option></select></td>
                        <td><input type="text" name="Truck[01][date_required]" class="datetimepicker"></td>
                        <td><input type="text" name="Truck[01][pickup_point]"></td>
                        <td><textarea name="Truck[01][comment]" rows="2" cols="5"></textarea></td>
                        <!--<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>-->
                        </tr>
            </thead>
            
            <tfoot>
                <tr>
                    <td colspan="11"><?php
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

<script type="text/javascript">

                    function datetime() {
                        $('.datetimepicker').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            format: 'd-m-Y H:i',
                            startDate: '<?php echo date('d/m/Y'); ?>'	//'2015/09/01'
                        });
                    }

                    
                    var row_no = '<?php echo $row_truck; ?>';
                    
                    function addTruck()
                    {
                        row = '<tbody id="row1-' + row_no + '">';
                        row += '<tr>';
                        row += '<td><input placeholder="source" type="text" id="Truck_' + row_no + '1_source_address" name="Truck[' + row_no + '1][source_address]"></td>';
                        row += '<td><input placeholder="destination" type="text" id="Truck_' + row_no + '1_destination_address" name="Truck[' + row_no + '1][destination_address]"></td>';
                        row += '<td><select name="Truck[' + row_no + '1][id_goods_type]"><?php echo $goods_type; ?></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][id_truck_type]"><?php echo $truck_type; ?></select></td>';
                        row += '<td><input placeholder="price" type="text" class="price" id="Truck_' + row_no + '1_expected_price" name="Truck[' + row_no + '1][expected_price]"></td>';
                        row += '<td><select name="Truck[' + row_no + '1][tracking]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                        row += '<td><select name="Truck[' + row_no + '1][insurance]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                        row += '<td><input type="text" name="Truck[' + row_no + '1][date_required]" class="datetimepicker"></td>';
                        row += '<td><input type="text" name="Truck[' + row_no + '1][pickup_point]"></td>'
                        row += '<td><textarea name="Truck[' + row_no + '1][comment]" rows="2" cols="5"></textarea></td>';
                        //row += '<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
                        row += '</tr>';
                        row += '</tbody>';
                        $('#table_upload_truck tfoot').before(row);
                        var input = document.getElementById('Truck_' + row_no + '1_source_address');
                        var autocomplete = new google.maps.places.Autocomplete(input);
                        
                        var input1 = document.getElementById('Truck_' + row_no + '1_destination_address');
                        var autocomplete = new google.maps.places.Autocomplete(input1);
                        row_no++;
                        datetime();

                    }
                    
                    $(document).ready(function() {
    $(".price").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
                    
</script>

