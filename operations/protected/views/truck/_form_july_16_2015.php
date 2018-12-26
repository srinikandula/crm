<div class="tab-pane active" id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Truck Details' ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <div class="span5">  <?php echo $form->textFieldRow($model, 'truck_reg_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                    
                    <div class="span5">   <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1 order by title asc')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model, 'id_truck_type', $list); ?> </div>
                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'tracking_available',
                            array('1' => 'Yes', '0' => 'No'));
                    ?></div>
                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                </div>
                            <div class="span12 pull-right" id="region_list">

                        <?php
                        $box = $this->beginWidget(
                                'bootstrap.widgets.TbBox',
                                array(
                            'title' => 'Add Prices',
                            'htmlOptions' => array('class' => 'portlet-decoration	')
                                )
                        );
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Destination</th>
                                    <th>Date Available</th>
                                    <th>Price</th>
                                    <th>Type Of Goods</th>
                                    <th>Load Type</th>
                                    <th>Status</th>
                                    <th  width="8%">Action</th>
                                </tr>
                            </thead>

                            <?php
                            $row = 0;
                            foreach ($truck as $truckRow):
                                ?>
                                <tbody id='row-<?php echo $row; ?>'>
                                    <tr >
                                        <td > </td>
                                <td ></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

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
            row += '<td width="200px">';
            row += '<select id="region_list_' + row_no + '_id_country" name="region_list[' + row_no + '][country]" onchange="getStates(this.value,this.id)" >';
            row += "<?php echo $countrylist; ?>";
            row += '</select>';
            row += '</td>';


            row += '<td width="200px">';
            row += '<select id="region_list_' + row_no + '_id_state" name="region_list[' + row_no + '][state][]" multiple  ><option value="0">None</option>';
            row += '</select>';
            row += '</td>';
            row += '<td></td>';
            row += '<td></td>';
            row += '<td></td>';
            row += '<td></td>';
            row += '<td></td>';


            //row += '<td style="width: 60px"><input width="100" type="text" id="region_list_1_name" name="region_list[' + row_no + '][country]" value="" maxlength="100"></td>';
            //row += '<td><input width="100" type="text" id="region_list_1_sort_order" name="region_list[' + row_no + '][state]" value="" maxlength="100"></td>';
            row += '<td> <a onclick="$(\'#row-' + row_no + '\').remove();" href="#" class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
            row += '</tr>';
            row += '</tbody>';
            $('.table tfoot').before(row);
            row_no++;
        }
</script>