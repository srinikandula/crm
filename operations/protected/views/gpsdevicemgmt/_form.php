<?php $action = Yii::app()->controller->action->id; ?>
<style>
.form-horizontal .controls {
    position: relative;
    margin-left: 0px;
}
.row-fluid [class*="span"] {
    margin-left:0%;

}

.row-fluid .span5 {
    width: 100%;
}

.row {
	margin-left:10px;
}

.enble{
	padding-left:10px;
}
.form-horizontal select{
	width: 96%;
}
</style>
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
			<div class="row" id="frm">
			<div class="span6">
                <div class="span5">
                    <?php
                    $acidReadonly=false;
                    if($_SESSION['id_admin_role']!=1 && $_GET['ids']!=""){
                        $acidReadonly=true;
                    }
					//$acidReadonly=$_GET['ids']==""?false:true;
					echo $form->textFieldRow($model, 'accountID', array('readonly'=>$acidReadonly,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'contactName', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5"> 
			<?php 
                        if($_GET['ids']!=""){
                            $cObj=Customer::model()->find('gps_account_id="'.$_GET['ids'].'"');
                            $model->customer_type = in_array($cObj->type,array('T','TR','C'))?$cObj->type:"GPS";
                            $page=$this->customerTypeLinks[$cObj->type];
                            $link="<a href='".$this->createUrl($page."/update",array('id'=>$cObj->id_customer))."' target='_blank' >".$cObj->idprefix."</a>";
                        }
 			
			echo $form->dropDownListRow($model, 'customer_type', array('T'=>'Truck Owner','TR'=>'Transporter','C'=>'Commission Agent','GPS'=>'GPS'),array('prompt'=>'Select'));?> <?php echo $link;?>
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
		</div>
		
				
				<div class="span6">
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
				                <div class="span5">
                    <?php echo $form->textAreaRow(
                                $model, 'contactAddress', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));
                    ?>
                </div>
				</div>
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
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type='text/javascript'>
var input = document.getElementById('GpsAccount_contactAddress');
var autocomplete = new google.maps.places.Autocomplete(input);
</script>

