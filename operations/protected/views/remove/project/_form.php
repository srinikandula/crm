
<div class="tab-pane active" id="Information">
	<div class="span12">
       <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11"><?php echo 'Project Details'; ?></div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"  ></button> </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1" >
                    <?php 
					/*$arry=Customer::model()->findAll(array('select'=>'id_customer,concat(firstname,"-",company) as name_company','condition' => 'status=1 and type="S"'));//CHtml::listData(Customer::model()->findAll(array('select'=>'id_customer,concat(firstname,"-",company) as name','condition' => 'status=1 and type="S"')),'id_customer', 'name');
					echo '<pre>';print_r($arry);exit;*/
					echo $form->dropDownListRow($model,'id_supplier',CHtml::listData(Customer::model()->findAll(array('select'=>'id_customer,concat(firstname,"-",company) as name_company','condition' => 'status=1 and type="S"')),'id_customer', 'name_company'),array('prompt' =>'None')); ?></div>
					
					<div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'category',
                            Library::getCategories());
                    ?></div>
					<div class="span5">  <?php echo $form->textFieldRow($model, 'part_name', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                     
                             

									<div class="span5">  <?php echo $form->textFieldRow($model, 'part_number', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)); ?> </div>
                     
                                    

									<div class="span5">  <?php
                        /*echo $form->textFieldRow(
                                $model, 'drawing',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );*/
                        ?> 
						<?php
				echo $form->fileFieldRow(
				$model, 'drawing',
				array('name'=>'drawing', 'rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right", 
                                    'class'=>'Options_design'),
				array('hint' => '<div id="image-name-display-id"> '.$model->drawing.' 
								 <div class="logo-img"><img src="'.Library::getUploadLink().$model->drawing.'" >' . '<input type="hidden" name="prev_file" value="'.$model->drawing.'"></div></div>')
				); ?>
						
						</div>
                    <div class="span5"> <?php
                        /*echo $form->textFieldRow(
                                $model, '3d_model_photo',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );*/
                        ?>
				<?php
				echo $form->fileFieldRow(
				$model, '3d_model_photo',
				array('name'=>'3d_model_photo', 'rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right", 
                                    'class'=>'Options_design'),
				array('hint' => '<div id="image-name-display-id"> '.$model->getAttribute('3d_model_photo').' 
								 <div class="logo-img"><img src="'.Library::getUploadLink().$model->getAttribute('3d_model_photo').'" >' . '<input type="hidden" name="prev_file" value="'.$model->getAttribute('3d_model_photo').'"></div></div>')
				); ?>
						
						</div>

					<div class="span5"> <?php
                        echo $form->textFieldRow(
                                $model, 'target_price',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>

					<div class="span5"> <?php
                        echo $form->textFieldRow(
                                $model, 'lead_time',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>
					    <!--<div class="span5"> <?php
                        echo $form->textFieldRow(
                                $model, 'date_of_requirement',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>-->

					<div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'size',Library::getSizes());?></div>

					    <div class="span5">
                        <div class="control-group">
                            <label for="Product_date_product_available" class="control-label">Date Of Requirement</label>
                            <div class="controls"><?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',
                            array(
                        'model' => $model,
                        'attribute' => 'date_of_requirement',
                        'options' => array('dateFormat' => 'yy-mm-dd',
                            'altFormat' => 'dd-mm-yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                        ),
                        'htmlOptions' => array('size' => '10')
                    ));
                    ?>		</div>
                            <div style="display:none" id="Product_weight_em_" class="help-inline error"><?php echo $form->error($model,
                                        'date_of_requirement'); ?></div>

                        </div>
                    </div>

					<div class="span5">
                        <div class="control-group">
                            <label for="Product_date_product_available" class="control-label">Deadline</label>
                            <div class="controls"><?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',
                            array(
                        'model' => $model,
                        'attribute' => 'deadline',
                        'options' => array('dateFormat' => 'yy-mm-dd',
                            'altFormat' => 'dd-mm-yy',
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                        ),
                        'htmlOptions' => array('size' => '10')
                    ));
                    ?>		</div>
                            <div style="display:none" id="Product_weight_em_" class="help-inline error"><?php echo $form->error($model,
                                        'deadline'); ?></div>

                        </div>
                    </div>
										
										<div class="span5"> <?php
                        echo $form->textAreaRow(
                                $model, 'special_conditions',array('rows' => 5),
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>

						                    <div class="span5"> <?php
                        echo $form->textAreaRow(
                                $model, 'payment_terms',array('rows' => 5),
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );
                        ?> </div>

					<div class="span5">
					<?php echo $form->dropDownListRow($model,'project_status',Library::getProjectStatus(),array('prompt' => 'Select')); ?></div>

					<div class="span5">
					<?php echo $form->dropDownListRow($model,'manufacturing_status',Library::getManufacturingStatus(),array('prompt' => 'Select')); ?></div>

                    <div class="span5" > 
                    <?php echo $form->radioButtonListRow($model, 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                    
            </fieldset>
        </div>
		
		 <?php 
			if(Yii::app()->controller->action->id=='update'){  
			?>
             <div class="span12">
                <fieldset class="portlet " >
                    <div class="portlet-decoration" onclick=" $('#hide_box_line4').slideToggle();">
                        <div class="span11">Biddings</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line4" style="display:none;">
                    

                         
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Contact Name </th>
                                    <th>Company </th>
                                    <th>Project Time Line   </th>
                                    <th>Project Cost   </th>
									<th>Date Created   </th>
                                </tr>
                            </thead>

                            <?php
							$rows=Yii::app()->db->createCommand('select pb.project_timeline,pb.cost,pb.date_created,c.firstname,c.company from {{project_bidding}} pb,{{customer}} c where pb.id_project="'.$_GET['id'].'" and pb.id_customer=c.id_customer')->queryAll();
							$row = 0;
							if(sizeof($rows)){
                            foreach ($rows as $row):
                                ?>
                                <tbody id='row-<?php echo $row; ?>'>
                                <tr>
									<td style="width: 60px"><?php echo $row['firstname'];?></td>
									<td style="width: 60px"><?php echo $row['company'];?></td>
									<td style="width: 60px"><?php echo $row['project_timeline'];?></td>
									<td style="width: 60px"><?php echo $row['cost'];?></td>
									<td style="width: 60px"><?php echo $row['date_created'];?></td>
                                </tr>
                                </tbody>
                                            <?php
                                        endforeach;
							}else{ echo '<tbody><tr><td colspan="4"><center>No Records Found!!</center></td></tr></tbody>';}
                                        ?>
                        </table>
 
                    </div>
           </div>
		   <?php  } ?>
</div>