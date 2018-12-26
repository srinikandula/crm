<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span12 pull-right" id="driver_list_table" style="display:none">
                <?php
                $box = $this->beginWidget(
                        'bootstrap.widgets.TbBox', array(
                    'title' => 'Add/Edit Drivers',
                    'htmlOptions' => array('class' => 'portlet-decoration	')
                        )
                );
                ?>
                <table class="table" id="table_upload_driver">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Upload Licence</th>
                            <th  width="8%">Action</th>
                        </tr>
                    </thead>

                    <?php
                    $row = 0;
                    if ($_GET['id'] != '') {
                        foreach ($model['dr'] as $fileRow):
                            ?>
                            <tbody id='row-<?php echo $row; ?>'>
                                <tr >
                                    <td><input type="text" name="Driver[<?php echo $row; ?>][name]" value="<?php echo $fileRow->name; ?>"><input type="hidden" name="Driver[<?php echo $row; ?>][id_driver]" value="<?php echo $fileRow->id_driver;?>"></td>
                                    <td><input type="text" name="Driver[<?php echo $row; ?>][mobile]" value="<?php echo $fileRow->mobile; ?>"></td>
                                    <?php
                                    echo CHtml::activeHiddenField($fileRow, 'prev_image', array('name' => 'Driver[' . $row . '][upload][prev_image]',
                                        'value' => $fileRow->licence_pic));
                                    ?>
                                    <td>
                                        <div class="span5 uploading-img-main"> 
                                            <div class="control-group">
                                                <div class="controls">
                                                    <input type="file" id="Driver[<?php echo $row; ?>][upload][image]?>" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                                           name="Driver[<?php echo $row; ?>][upload][image]" data-original-title="Upload category logo from your computer">
                                                    <div style="display:none" id="Driver[<?php echo $row; ?>][upload][image]" class="help-inline error"></div><p class="help-block"></p>


                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <td><div  id="image-name-display-id"><?php echo $fileRow->licence_pic; ?><img src="<?php echo Library::getMiscUploadLink() . $fileRow->licence_pic; ?>" width="200px" height="200px" >
                                            <div class="logo-img">
                                                <img   src="<?php echo Library::getMiscUploadLink() . $fileRow->licence_pic; ?>">
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
                            <td colspan="5"><?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Add',
                                    'type' => 'btn-info',
                                    'htmlOptions' => array('onclick' => 'addDriver()'),
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
$docTypes="";
foreach(Library::getCustomerDocTypes() as $k=>$v){
    $docTypes.='<option value="' . $v . '" >' . $v . '</option>';
}?>
<script type="text/javascript">
    var row_no =<?php echo $row; ?>;
    function addDriver()
    {
        row = '<tbody id="row-' + row_no + '">';
        row += '<tr>';
        
        row += '<input type="hidden" value="" id="Driver_upload_' + row_no + '_prev_image" name="Driver[' + row_no + '][upload][prev_image]">';
        row += '<td><input type="hidden" value=""  name="Driver[' + row_no + '][id_driver]"><input type="text" value=""  name="Driver[' + row_no + '][name]"></td>';    
        row += '<td><input type="text" value=""  name="Driver[' + row_no + '][mobile]"></td>';    
        row += '<td><input id="Driver_upload_' + row_no + '_image" name="Driver[' + row_no + '][upload][image]" type="file">\n\
    <input id="ytproductimage_upload_' + row_no + '_image" type="hidden" name="Driver[' + row_no + '][upload][image]" value=""></td>';
        row += '<td></td>';
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#driver_list_table tfoot').before(row);
        row_no++;
    }
</script>