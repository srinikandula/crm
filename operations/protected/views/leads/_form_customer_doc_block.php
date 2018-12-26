<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span12 pull-right" id="region_list">
                <?php
                $box = $this->beginWidget(
                        'bootstrap.widgets.TbBox', array(
                    'title' => 'Upload Customer Docs',
                    'htmlOptions' => array('class' => 'portlet-decoration	')
                        )
                );
                ?>
                <table class="table" id="table_upload">
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
                                    <td><?php echo CHtml::dropdownlist('Customerdocs[upload]['.$row.'][doc_type]',$fileRow->doc_type,Library::getCustomerDocTypes());?></td>
                                    <td><div  id="image-name-display-id"><?php echo $fileRow->file; ?><!--<img src="<?php echo Library::getMiscUploadLink() . $fileRow->file; ?>" width="200px" height="200px" >-->
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
                            <td colspan="4"><?php
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
    </div>
</div>
<?php
$docTypes="";
foreach(Library::getCustomerDocTypes() as $k=>$v){
    $docTypes.='<option value="' . $v . '" >' . $v . '</option>';
}?>
<script type="text/javascript">
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
        row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
        row += '</tr>';
        row += '</tbody>';
        $('#table_upload tfoot').before(row);
        row_no++;
    }
</script>