<div class="tab-pane active" id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Details' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div class="span5">  <?php echo $form->textFieldRow($model['t'], 'truck_reg_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    
                    <div class="span5">   <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model['t'], 'id_truck_type', $list); ?> </div>
                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model['t'], 'tracking_available',
                            array('1' => 'Yes', '0' => 'No'));
                    ?></div>

					<div class="span5" > 
                    <?php echo $form->radioButtonListRow($model['t'], 'insurance_available',
                            array('1' => 'Yes', '0' => 'No'));
                    ?></div>

                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model['t'], 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                </div>
                            <div class="span12 pull-right" id="region_list">

                        <?php
                        $box = $this->beginWidget(
                                'bootstrap.widgets.TbBox',
                                array(
                            'title' => 'Add/Edit Images',
                            'htmlOptions' => array('class' => 'portlet-decoration	')
                                )
                        );
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Upload</th>
                                    <th>File</th>
                                    <th  width="8%">Action</th>
                                </tr>
                            </thead>

                            <?php
                            $row = 0;
                            foreach ($model['f'] as $fileRow):
                                ?>
                                <tbody id='row-<?php echo $row; ?>'>
                                    <tr >
                                    <?php echo CHtml::activeHiddenField($fileRow,
                                        'prev_image',
                                        array('name' => 'TruckDoc[upload][' . $row . '][prev_image]',
                                    'value' => $fileRow->file)); ?>
                                        <td>
                                            <div class="span5 uploading-img-main"> 
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="file" id="TruckDoc[upload][<?php echo $row;?>][image]?>" class="Options_design" data-placement="right" data-toggle="tooltip" rel="tooltip"
                                                   name="TruckDoc[upload][<?php echo $row;?>][image]" data-original-title="Upload category logo from your computer">
                                            <div style="display:none" id="TruckDoc[upload][<?php echo $row;?>][image]" class="help-inline error"></div><p class="help-block"></p>
                                             
                                          
                                        </div>
                                    </div>
                                   
                                </div>
                                        </td>
                                        <td><div  id="image-name-display-id"><?php echo $fileRow->file;?><img src="<?php echo Library::getTruckUploadLink().$fileRow->file;?>" width="200px" height="200px" >
                                            <div class="logo-img">
                                                <img   src="<?php echo Library::getTruckUploadLink().$fileRow->file;?>">
                                            </div>
                                            </div></td>

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
                                            'bootstrap.widgets.TbButton',
                                                array(
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
<script type='text/javascript'>
        var row_no =<?php echo $row; ?>;
        function addOption()
        {
            row = '<tbody id="row-' + row_no + '">';
            row += '<tr>';
            row += '<input type="hidden" value="" id="TruckDoc_upload_' + row_no + '_prev_image" name="TruckDoc[upload][' + row_no + '][prev_image]">';
            row += '<td><input id="TruckDoc_upload_' + row_no + '_image" name="TruckDoc[upload][' + row_no + '][image]" type="file">\n\
    <input id="ytproductimage_upload_' + row_no + '_image" type="hidden" name="TruckDoc[upload][' + row_no + '][image]" value=""></td>';
            row += '<td></td>';
            row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
            row += '</tr>';
            row += '</tbody>';
            $('.table tfoot').before(row);
            row_no++;
        }
</script>