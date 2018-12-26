<?php $action = Yii::app()->controller->action->id; ?>
<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Gps Truck Notification' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <div class="span5">
                    <?php echo $form->textFieldRow($model['ga'], 'source_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model['ga'], 'destination_city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model['ga'], 'no_of_trucks', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model['ga'], 'id_truck_type', $list,array('prompt'=>'Select a Truck')); ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model['ga'], 'price', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model['ga'], 'date_available', array('class' => 'datetimepicker','rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model['ga'], 'sendtoall',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                </div>
                  
                
                
            </div>
        </fieldset>
    </div>
</div>
<?php if($action == 'update'){
        echo $this->renderPartial('_form_users', array('form' => $form, 'model' => $model), true);
        echo $this->renderPartial('_form_interested_cust', array('form' => $form, 'model' => $model), true);
}?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>

<script type="text/javascript">
                                    $('.datetimepicker').datetimepicker({
                                        dayOfWeekStart: 1,
                                        lang: 'en',
                                        format: 'Y-m-d',
                                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                                    });
</script>
<script>
$('#Gpsalerts_sendtoall input[type=\'radio\']').live('click', function() {
//alert(this.value);
       if (this.value == '0') {
            $('#user_list_table').css('display', '');
        } else {
            $('#user_list_table').css('display', 'none');
        }

    });
</script>

                    
