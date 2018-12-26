<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Input Details' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                
				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'idv', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'vehicle_number', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'age', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'ncb', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->radioButtonListRow($model, 'imt',
                            array('1' => 'Yes', '0' => 'No'));?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'weight', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->radioButtonListRow($model, 'pa_owner_driver',
                            array('1' => 'Yes', '0' => 'No'));?>
                </div>

				<div class="span3">
                    <?php echo $form->radioButtonListRow($model, 'nil_dep',
                            array('1' => 'Yes', '0' => 'No'));?>
                </div>

				<div class="span3">
					<div class="control-group">
						<label class="control-label" for="Customerinsurance_weight">File</label>
						<div class="controls">
						<?php echo $model->file==""?"":"<a href='".Library::getTruckUploadLink()."' target='_blank' >Show</a>"; ?>	
						</div>
					</div> 
				</div>

				<div class="span3">  <?php
                            echo $form->dropDownListRow($model, 'status', array('New'=>'New', 'Quote'=>'Quote', 'Reject'=>'Reject', 'Cancel'=>'Cancel', 
							'Interested'=>'Interested', 'Completed'=>'Completed'),array('value'=>$model->status,'name' => 'Customerinsurance[status]'));
                            ?></div>


				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'total_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
			</div>
        </fieldset>

		<fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                <div class="span11">
                    <?php echo 'OD Premium'; ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line2">
                
				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_rate', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_basic_od_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_gvw_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_total_basic_od_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_elec_fitting', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_bi_fuel_system_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_discount_amount', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_post_disount_amount', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_imt_23', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
				
				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_post_imt_23_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_ncb_amount', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'od_total_od_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

			</div>
        </fieldset>

		<fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
                <div class="span11">
                    <?php echo 'Liability Premium'; ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line2">
                
				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_basic_tp_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_compulsory_owner_driver', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_paid_drivers_clearners', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_tp_premium_bi_fuel_system', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_nfpp_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_total_liability_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_gross_premium', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span3">
                    <?php echo $form->textFieldRow($model, 'lb_service_tax', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

			</div>
        </fieldset>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>