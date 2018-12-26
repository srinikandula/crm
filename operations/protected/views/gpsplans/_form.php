<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Gps Plans Details' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'plan_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                            <label class="control-label required" for="Truck_id_truck_type">
                                Duration In Months <span class="required">*</span>
                            </label>
                            <div class="controls">
                                <?php echo CHtml::dropdownlist('GpsDevicePlans[duration_in_months]', $model->duration_in_months, Library::getPlanMonths(),array('prompt'=>'Select One'));?>
                            
                            </div>
                        </div>
                
                <div class="span5">
                    <?php echo $form->textFieldRow( $model, 'amount',  array('rel' => 'tooltip','data-toggle' => "tooltip", 'data-placement' => "right",));
                        ?>
                </div>
                
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));?>
                </div>
                
            </div>
        </fieldset>
    </div>
</div>
