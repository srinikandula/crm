<?php //echo Yii::app()->controller->id." and ".Yii::app()->controller->action->id."<br/>";exit;
$action = Yii::app()->controller->action->id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
        <div class="span12 ">
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details </div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                        <?php if ($_GET['id'] == '') { ?>
                        <div class="span5">
                            <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'title', array('onkeydown' => 'fnKeyDown("Loadtruckrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?>
                        <!--<div class="control-group"><label for="Loadtruckrequest_title" class="control-label">Customer</label><div class="controls"><input type="text" id="Loadtruckrequest_title" onkeydown="fnKeyDown('Loadtruckrequest_title')" name="Loadtruckrequest[title]" value='<?php echo $model['ltr']->title ?>' ><div style="display:none" id="Loadtruckrequest_title_em_" class="help-inline error"></div></div></div>--></div>
                        <?php } if ($action != 'create') { ?>

                        <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'destination_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span4">   <?php
                        $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span4">   <?php
                        $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                        ?> </div>

                        <!--<div class="span5">   <?php /* $list = CHtml::listData(Loadtype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_load_type', 'title');
                          echo $form->dropDownListRow($model['ltr'], 'id_load_type', $list); */
                        ?> </div>-->

                        <div class="span4">  <?php
                            echo $form->radioButtonListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span4">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'date_required', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            /* echo $form->datepickerRow(
                              $model['ltr'], 'date_required', array(
                              'options' => array('dateFormat' => 'yy-mm-dd',
                              'altFormat' => 'dd-mm-yy',
                              'changeMonth' => 'true',
                              'changeYear' => 'true',
                              ),
                              'htmlOptions' => array(
                              )
                              ), array(
                              'prepend' => '<i class="icon-calendar"></i>'
                              )
                              ); */
                            ?></div>
                        <div class="span4">  <?php
                            echo $form->radioButtonListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'));
                            ?></div>

                        <div class="span4">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'status', Library::getLTRStatuses());
                            ?></div>

    <?php if (!$model['ltr']->approved) { ?>
                            <div class="span5">  <?php
        echo $form->radioButtonListRow($model['ltr'], 'approved', array('1' => 'Yes', '0' => 'No'));
        ?></div>
    <?php }
} ?></div>
            </fieldset>
        </div>

<?php if ($action != 'create') { ?>
        <div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
            <div class="span12">
                <fieldset class="portlet" >
                    <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                        <div class="span11">Filter </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="portlet-content" id="hide_box_line1">



                        <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('name' => 'search[source_address]', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'destination_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>

                        <div class="span4">   <?php
                            $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span4">   <?php
                            $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                            echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                            ?> </div>


                        <div class="span4">  <?php
                            echo $form->radioButtonListRow($model['ltr'], 'tracking', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span4">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'date_required', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                        <div class="span4">  <?php
            echo $form->radioButtonListRow($model['ltr'], 'insurance', array('1' => 'Yes', '0' => 'No'));
            ?></div>




                    </div>
                </fieldset>
            </div>
            </div>
        </div>
            <?php } ?>


            <?php if ($action == 'create') { ?>
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
                            <th>Goods Type</th>
                            <th>Truck Type</th>
                            <th>Tracking Available</th>
                            <th>Insurance Available</th>
                            <th>Date Time Required</th>
                            <th>Response Time/Comment</th>
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
                                } ?>
                    <tfoot>
                        <tr>
                            <td colspan="9"><?php
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
        <?php } ?>



<?php
$truck_type = "";
foreach (Trucktype::model()->findAll() as $ttypeRow) {
    $truck_type.='<option value="' . $ttypeRow->id_truck_type . '" >' . $ttypeRow->title . ' ' . $ttypeRow->tonnes . '</option>';
}

$goods_type = "";
foreach (Goodstype::model()->findAll() as $ttypeRow) {
    $goods_type.='<option value="' . $ttypeRow->id_goods_type . '" >' . $ttypeRow->title . '</option>';
}
?>




    </div>

</div>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">

                            function datetime() {
                                $('.datetimepicker').datetimepicker({
                                    dayOfWeekStart: 1,
                                    lang: 'en',
                                    format: 'Y-m-d H:i',
                                    startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                                });
                            }

                            $('.datetimepicker').datetimepicker({
                                dayOfWeekStart: 1,
                                lang: 'en',
                                format: 'Y-m-d H:i',
                                startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                            });
                            var row_no = '<?php echo $row_truck; ?>';
                            function addTruck()
                            {
                                row = '<tbody id="row1-' + row_no + '">';
                                row += '<tr>';
                                row += '<td><input placeholder="source" type="text" id="Truck_' + row_no + '1_source_address" name="Truck[' + row_no + '1][source_address]"></td>';
                                row += '<td><input placeholder="destination" type="text" id="Truck_' + row_no + '1_destination_address" name="Truck[' + row_no + '1][destination_address]"></td>';
                                row += '<td><select name="Truck[' + row_no + '1][id_goods_type]"><?php echo $goods_type; ?></select></td>';
                                row += '<td><select name="Truck[' + row_no + '1][id_truck_type]"><?php echo $truck_type; ?></select></td>';
                                row += '<td><select name="Truck[' + row_no + '1][tracking]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                                row += '<td><select name="Truck[' + row_no + '1][insurance]"><option value="1">Yes</option><option value="0">No</option></select></td>';
                                row += '<td><input type="text" name="Truck[' + row_no + '1][date_required]" class="datetimepicker"></td>';
                                row += '<td><textarea name="Truck[' + row_no + '1][comment]" rows="2" cols="5"></textarea></td>';
                                row += '<td> <a onclick="$(\'#row1-' + row_no + '\').remove();"  class="btn btn-danger" ><i class="delete-iconall"></i></a> </td>';
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
</script>

<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
