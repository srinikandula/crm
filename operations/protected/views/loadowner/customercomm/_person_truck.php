<fieldset class="portlet " >
    <div class="span12 pull-right" id="region_list">
        <table class="table" id="table_person_truck">
            <thead>
                <tr>
                    <th>Customer Truck Reg No</th>
                    <th>Type</th>
                    <th>Commission</th>
                    <th  width="8%">Action</th>
                </tr>
            </thead>
            <?php
            $row1 = 0;
            foreach ($model as $modelRow):
                ?>
                <tbody id='row-<?php echo $row1; ?>'>
                    <tr >
                        <td>
                            <input  type="text" onkeydown="fnKeyDown('Commission_PersonTruck_<?php echo $row1; ?>_title','PersonTruck')" id="Commission_PersonTruck_<?php echo $row1; ?>_title" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                    name="Commission[PersonTruck][<?php echo $row1; ?>][title]" data-original-title="type customer name and number" value="<?php echo $modelRow->title; ?>">                                    </td>
                        <td><select name="Commission[PersonTruck][<?php echo $row1; ?>][commission_type]"><option <?php echo $modelRow->commission_type == 'F' ? 'selected' : ''; ?> value="F">Fixed</option><option value="P" <?php echo $modelRow->commission_type == 'P' ? 'selected' : ''; ?> >Percent</option></select></td>
                        <td>
                            <input type="text" id="Commission_PersonTruck_<?php echo $row1; ?>_commission" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                   name="Commission[PersonTruck][<?php echo $row1; ?>][commission]" data-original-title="enter commission" value="<?php echo $modelRow->commission; ?>">
                        </td>

                        <td><a onclick="$('#row-<?php echo $row1; ?>').remove()" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                    </tr>
                </tbody>
                <?php
                $row1++;
            endforeach;
            ?>
            <tfoot>
                <tr>
                    <td colspan="3"><?php
                        $this->widget(
                                'bootstrap.widgets.TbButton', array(
                            'label' => 'Add',
                            'type' => 'btn-info',
                            'htmlOptions' => array('onclick' => 'addCustomerTruck()'),
                                )
                        );
                        ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</fieldset>
<script type='text/javascript'>
    var row_no1 =<?php echo $row1; ?>;
    function addCustomerTruck()
    {
        var tid = 'Commission_PersonTruck_' + row_no1 + '_title';
        row = '<tbody id="row-' + row_no1 + '">';
        row += '<tr>';
        row += '<td><input  id="Commission_PersonTruck_' + row_no1 + '_title" name="Commission[PersonTruck][' + row_no1 + '][title]" type="text" onkeydown="fnKeyDown(\'' + tid + '\',\'PersonTruck\')"></td>';
        row += '<td><select name="Commission[PersonTruck][' + row_no1 + '][commission_type]"><option value="F">Fixed</option><option  value="P">Percent</option></select></td>';
        row += '<td><input type="text"  data-original-title="enter commission" name="Commission[PersonTruck][' + row_no1 + '][commission]" rel="tooltip" data-toggle="tooltip" data-placement="right" class="Options_design" id="Commission_PersonTruck_' + row_no1 + '_commission"></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no1 + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_person_truck tfoot').before(row);
        row_no1++;
    }




</script>