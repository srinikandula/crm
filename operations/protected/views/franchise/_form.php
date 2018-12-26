<div class="tab-pane active" id="Information">
    <div class="span12">
        <fieldset class="portlet">
            <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                <div class="span11">
                    <?php echo 'Franchise Details' ?>
                </div>
                <div class="span1 Dev-arrow">
                    <button class="btn btn-info arrow_main" type="button"></button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-content" id="hide_box_line1">
                
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'fullname', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'account', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'mobile', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'landline', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
                
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'city', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
				
				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'state', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>	
				
				<div class="span5">
                    <?php echo $form->textAreaRow($model, 'address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'company', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textAreaRow($model, 'bank_details', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'pancard', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>

				<div class="span5">
                    <?php echo $form->textFieldRow($model, 'service_tax_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",));   ?>
                </div>
				
                
				<div class="span5">   <?php 
                           echo $form->datepickerRow(
                                $model, 'doj', array(
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
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));?>
                </div>
                
            </div>
        </fieldset>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>