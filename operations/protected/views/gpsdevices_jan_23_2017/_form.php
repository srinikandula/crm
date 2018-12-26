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
<?php //$disableField=$_SESSION['id_admin_role']==1?false:true;
$disableField=$_SESSION['id_admin_role']==1?false:false;?>
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
			<div class="row">
				<div class="span6">
                <div class="span5">
                    <?php 
                    $disabled=false;
                    if($action=='update' && !in_array($model->accountID,array('santosh','accounts')) && $_SESSION['id_admin_role']!=1){
                        //exit("inside");
						$disabled=true;    
                    }
                    $list = CHtml::listData(GpsAccount::model()->findAll(array('condition'=>'isActive=1 order by accountID asc')), 'accountID', 'accountID'); 
                    echo $form->dropDownListRow($model, 'accountID', $list,array('disabled'=>$disabled,'prompt'=>'Select Account')); ?>
                    <?php //echo $form->textFieldRow($model, 'accountID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$action == 'create'?false:true));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'deviceID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$disabled));   ?>
                </div>
				<div class="span5" id="tt">
                            <?php $list = CHtml::listData(Trucktype::model()->findAll(array('condition'=>'status=1')), 'id_truck_type', 'title'); 
			echo $form->dropDownListRow($model, 'truckTypeId', $list,array('prompt'=>'Select a Truck')); ?>
                </div>
				
                <!--<div class="span5">
                    <?php //echo $form->textFieldRow($model, 'vehicleMake', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>-->
                <div class="span5"> 
				<?php	echo $form->dropDownListRow($model, 'vehicleType', array('TK'=>'Truck','TR'=>'Transporter','NTK'=>'Non Truck'),array('prompt'=>'Select'));?>	
				</div>
				<div class="span5">
                    <?php 
                    /*$list = CHtml::listData(Admin::model()->findAll(array('select'=>'concat(first_name," ",last_name) as first_name','condition'=>'status=1','order'=>'first_name asc')), 'first_name', 'first_name');*/ 
                    $list = CHtml::listData(Admin::model()->findAll(array('select'=>'concat(first_name," ",last_name) as first_name,concat(id_admin,"@@",first_name," ",last_name) as id_admin','condition'=>'status=1','order'=>'first_name asc')), 'id_admin', 'first_name');
					$model->installedBy=$model->installedById."@@".$model->installedBy;
					
					echo $form->dropDownListRow($model, 'installedBy', $list,array("disabled"=>$disableField,'prompt'=>'--Select--')); ?>
                    <?php //echo $form->textFieldRow($model, 'accountID', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'readonly'=>$action == 'create'?false:true));   ?>
                </div>
								<div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'isActive',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?>
                </div>
				<div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'isDamaged',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                </div>
				<div class="span5">
				<?php $addr=Library::getGPBYLATLNGDetails($model->lastValidLatitude.",".$model->lastValidLongitude);
				echo "<b>Current Location</b> : ".$addr['address'];?>
				</div>
			</div>
				<div class="span6">
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'imeiNumber', array("disabled"=>$disableField,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'simPhoneNumber', array("disabled"=>$disableField,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5">
                    <?php echo $form->textFieldRow($model, 'simID', array("disabled"=>$disableField,'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                <div class="span5" id="vehicle_model" style="display:none">
                    <?php echo $form->textFieldRow($model, 'vehicleModel', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                
				<div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'insuranceExpire', array(
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
                        );
                           ?>
				</div>

				<div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'fitnessExpire', array(
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
                        );
                           ?>
				</div>
				
				<div class="span5">
                    <?php echo $form->textFieldRow( $model, 'rcNo',  array('rel' => 'tooltip','data-toggle' => "tooltip", 'data-placement' => "right",));
                        ?>
                </div>

				<div class="span5">
                    <?php echo $form->radioButtonListRow($model, 'NPAvailable',
                            array('1' => 'Yes', '0' => 'No'));
                    ?>
                </div>

				<div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'NPExpire', array(
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
                        );
                           ?>
				</div>
				
				<div class="span5">
                    <?php echo $form->textFieldRow( $model, 'insuranceAmount',  array('rel' => 'tooltip','data-toggle' => "tooltip", 'data-placement' => "right",));
                        ?>
                </div>


				
					
                <!--<div class="span5">
                <?php /*echo $form->textAreaRow(
                    $model,'description',array('rows' => 2, 'cols' => 50)
                    );*/ ?>
                </div>-->
            </div>
			</div>
        </fieldset>
		<?php if($_GET['ids']!=""){$this->renderPartial('_form_plans', array('form'=>$form,'model'=>$model));}?>
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
$('#yw0').on('click',function(){
  if($("#GpsDevice_imeiNumber").val().length!=15){
    alert("IMei No should not be greater  than 15 digits and should contain numbers only!!");
    return false;
  }
});
</script>
