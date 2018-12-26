<fieldset class="portlet " >
    <div class="span12 pull-right" id="region_list">
        <table class="table" id="table_person">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Commission</th>
                    <th  width="8%">Action</th>
                </tr>
            </thead>
            <?php
            $row = 0;
            foreach ($model as $modelRow):
                ?>
                <tbody id='row-<?php echo $row; ?>'>
                    <tr >
                        <td>
                            <input  type="text" onkeydown="fnKeyDown('Commission_Person_<?php echo $row; ?>_title','Person')" id="Commission_Person_<?php echo $row; ?>_title" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                    name="Commission[Person][<?php echo $row; ?>][title]" data-original-title="type customer name and number" value="<?php echo $modelRow->title; ?>">                                    </td>
                        <td><select name="Commission[Person][<?php echo $row; ?>][commission_type]"><option <?php echo $modelRow->commission_type == 'F' ? 'selected' : ''; ?> value="F">Fixed</option><option value="P" <?php echo $modelRow->commission_type == 'P' ? 'selected' : ''; ?> >Percent</option></select></td>
                        <td>
                            <input type="text" id="Commission_Person_<?php echo $row; ?>_commission" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                   name="Commission[Person][<?php echo $row; ?>][commission]" data-original-title="enter commission" value="<?php echo $modelRow->commission; ?>">
                        </td>

                        <td><a onclick="$('#row-<?php echo $row; ?>').remove()" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>
                    </tr>
                </tbody>
                <?php
                $row++;
            endforeach;
            ?>
            <tfoot>
                <tr>
                    <td colspan="3"><?php
                        $this->widget(
                                'bootstrap.widgets.TbButton', array(
                            'label' => 'Add',
                            'type' => 'btn-info',
                            'htmlOptions' => array('onclick' => 'addCustomer()'),
                                )
                        );
                        ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</fieldset>
<script type='text/javascript'>
    var row_no =<?php echo $row; ?>;
    function addCustomer()
    {
        var tid = 'Commission_Person_' + row_no + '_title';
        row = '<tbody id="row-' + row_no + '">';
        row += '<tr>';
        row += '<td><input  id="Commission_Person_' + row_no + '_title" name="Commission[Person][' + row_no + '][title]" type="text" onkeydown="fnKeyDown(\'' + tid + '\',\'Person\')"></td>';
        row += '<td><select name="Commission[Person][' + row_no + '][commission_type]"><option value="F">Fixed</option><option  value="P">Percent</option></select></td>';
        row += '<td><input type="text"  data-original-title="enter commission" name="Commission[Person][' + row_no + '][commission]" rel="tooltip" data-toggle="tooltip" data-placement="right" class="Options_design" id="Commission_Person_' + row_no + '_commission"></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_person tfoot').before(row);
        row_no++;
    }




</script>