<?php $action = Yii::app()->controller->action->id; ?>
<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Gps Devices' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <div class="span5">
                    <?php $list = CHtml::listData(Gpsdevice::model()->findAll(array('condition'=>'isActive=1')), 'accountID', 'accountID'); 
                    echo $form->dropDownListRow($model, 'accountID', $list,array('disabled'=>$action != 'create'?"disabled":"",'prompt'=>'Select Account')); ?>
                    <?php //echo $form->textFieldRow($model, 'accountID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$action == 'create'?false:true));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'deviceID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$action == 'create'?false:true));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'simPhoneNumber', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'simID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'imeiNumber', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'vehicleMake', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5"> <div class="control-group">
                <label class="control-label" for="GpsDevice_vehicleType">Vehicle Type</label><div class="controls">
                <?php $checked = $model['vehicleType'];?>
                <select name="GpsDevice[vehicleType]" id="GpsDevice_vehicleType">
                <option value="TK" <?php echo $checked =='TK'?"selected":""; ?>>TK</option>
                <option value="TR" <?php echo $checked =='TR'?"selected":""; ?>>TR</option>
                <option value="NTK" <?php echo $checked =='NTK'?"selected":""; ?>>NTK</option>
                </select><div class="help-inline error" id="GpsDevice_vehicleType_em_" style="display:none"></div></div></div>
		</div>
                <div class="span5" id="vehicle_model" style="display:none">
                    <?php echo $form->textFieldRow($model, 'vehicleModel', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5" id="tt">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model, 'truckTypeId', $list,array('prompt'=>'Select a Truck')); ?>
                </div>
                <div class="span5">
                <?php echo $form->textAreaRow(
                    $model,'description',array('rows' => 2, 'cols' => 50)
                    ); ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="GpsDevice[vehicleType]"]').click(function(){
        if($(this).attr("value")=="NTK"){
            $('#tt').css('display', 'none');
            $('#vehicle_model').css('display', 'block');
           } else {
            $('#tt').css('display', '');
            $('#vehicle_model').css('display', 'none');
            }
        });
    })
</script>
