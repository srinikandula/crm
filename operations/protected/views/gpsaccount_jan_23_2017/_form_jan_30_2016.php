<?php $action = Yii::app()->controller->action->id; ?>
<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Gps Account' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                <div class="span5">
                    <?php 
					$acidReadonly=$_GET['ids']==""?false:true;
					echo $form->textFieldRow($model, 'accountID', array('readonly'=>$acidReadonly,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'contactName', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'contactPhone', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
					echo "<b>".Library::isMobileDND($model->contactPhone)."</b>";
					?>
					
                </div>
		<div class="span5"> 
		<?php 
 		$model->vehicleType = $_GET['ids']!=""?$model->vehicleType:"";
		echo $form->dropDownListRow($model, 'vehicleType', array('TRUCK'=>'TRUCK','NONTRUCK'=>'NONTRUCK','BOTH'=>'BOTH'),array('prompt'=>'Select'));?>
		<!--<div class="control-group">
                <label class="control-label" for="GpsAccount_vehicleType">Vehicle Type</label><div class="controls">
                <?php $checked = $_GET['ids']!=""?$model['vehicleType']:"";?>
                <select name="GpsAccount[vehicleType]" id="GpsAccount_vehicleType">
				<option value="" >Select</option>
                <option value="TRUCK" <?php echo $checked =='TRUCK'?"selected":""; ?>>TRUCK</option>
                <option value="NONTRUCK" <?php echo $checked =='NONTRUCK'?"selected":""; ?>>NONTRUCK</option>
                <option value="BOTH" <?php echo $checked =='BOTH'?"selected":""; ?>>BOTH</option>
                </select><div class="help-inline error" id="GpsAccount_vehicleType_em_" style="display:none"></div></div></div>-->
		</div>
                <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model, 'password', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                </div>
                
                <div class="span5">  <?php
                        echo $form->textFieldRow(
                                $model, 'contactEmail', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        );
                        ?>
                </div>
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'smsEnabled',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                </div>
                <div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'isActive',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                </div>
                </div>
        </fieldset>
        
    </div>
</div>
<?php //if ($action == 'update') {echo $this->renderPartial('_form_device_block', array('form'=>$form,'model'=>$model),true);}?>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="GpsAccount[vehicleType]"]').click(function(){
        if(($(this).attr("value")=="TRUCK")||($(this).attr("value")=="BOTH")){
            $('#device_list_table').css('display', '');
           } else {
            $('#device_list_table').css('display', 'none');
            }

    });
    })
</script>


