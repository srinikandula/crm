<?php //echo Yii::app()->controller->id." and ".Yii::app()->controller->action->id."<br/>";exit;
$action = Yii::app()->controller->action->id;
$this->widget('ext.Flashmessage.Flashmessage');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.css"/>
<div id="notification"></div>
<div class="row-fluid">
    <div class="tab-pane active" id="Personal Details">
                <?php /*
        if($_SESSION['id_admin_role']==8){
        echo $form->textFieldRow(
                                    $model['ltr'], 'title', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",'value'=>$records['customer'][0]->idprefix.",".$records['customer'][0]->fullname.",".$records['customer'][0]->type.",".$records['customer'][0]->mobile.",".$records['customer'][0]->email)
                            );}*/?>
        <div class="span<?php echo $_SESSION['id_admin_role']==10?'12':'12';?>"  >
            <fieldset class="portlet" >
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
                    <div class="span11">Details</div>
                    <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-content" id="hide_box_line1">
                    <?php if(!$_GET['id']){?><div class="span4">  <div class="control-group"><label for="Admin_id_admin_role" class="control-label required">Customer Type <span class="required">*</span></label><div class="controls"><select id="Truckloadrequest_type" name="Truckloadrequest[type]" onchange="fncustomertype(this);">
<option value="1">Registered</option>
<option value="0">UnRegistered</option>

</select><div style="display:none" id="Admin_id_admin_role_em_" class="help-inline error"></div></div></div> </div>
                    
                    <div id="registered">
                    
                        <div class="span4">
                            <?php
                            
                            echo $form->textFieldRow(
                                    $model['ltr'], 'title', array('onkeydown' => 'fnKeyDown("Truckloadrequest_title")', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            if ($action == 'create') { echo '<b>Note:Truck request from unregistered customer will be rejected.Customer available in the drop down will only be accepted</b>';}
                            ?>
                        </div>
                    </div><?php }?>
                    <div id="unregistered" style="display:none" >
                        <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'fullname',
           array('value'=>'empty','rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>

	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'mobile',
           array('value'=>'0000000000','rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>

	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'email',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>



	   <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'company',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>

	   <div class="span5">  <?php echo $form->textAreaRow(
           $model['c'],'address',array('rows' => 3, 'cols' => 30)
       ); ?></div>
       
  <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'city',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
     
<div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'state',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
     
       <div class="span5">  <?php echo $form->textFieldRow(
           $model['c'],'landline',
           array('rel'=>'tooltip','data-toggle'=>"tooltip",'data-placement'=>"right",)
       ); ?></div>
                        
                    </div>
                        <?php if ($action != 'create') { ?>

                        <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'source_address', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                    
 
                    <?php //echo '<pre>';print_r($model['dest']);print_r($model['price']);echo '</pre>';?>
                    <div class="span4">  <div class="control-group"><label for="Truckloadrequest_title" class="control-label required">Destination/Price 1<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" id="Truckloadrequest_0_destination_address" name="Truckloadrequest[destination_address][0]"  value="<?php echo $model['dest'][0]->destination_address;?>" style="width:150px;" >
								<input type="text" id="Truckloadrequest_0_price" name="Truckloadrequest[price][0]"  value="<?php echo $model['dest'][0]->price;?>" style="width:50px;" >
                                <div style="display:none" id="Truckloadrequest_title_em_" class="help-inline error"></div></div>
                                    
                        </div></div>

					<div class="span4">  <div class="control-group"><label for="Truckloadrequest_title" class="control-label required">Destination/Price 2<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" id="Truckloadrequest_1_destination_address" name="Truckloadrequest[destination_address][1]"  value="<?php echo $model['dest'][1]->destination_address;?>" style="width:150px;" >
								<input type="text" id="Truckloadrequest_0_price" name="Truckloadrequest[price][1]"  value="<?php echo $model['dest'][1]->price;?>" style="width:50px;" >
                                <div style="display:none" id="Truckloadrequest_title_em_" class="help-inline error"></div></div>
                                    
                        </div></div>
						<div class="span4">  <div class="control-group"><label for="Truckloadrequest_title" class="control-label required">Destination/Price 3<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" id="Truckloadrequest_2_destination_address" name="Truckloadrequest[destination_address][2]"  value="<?php echo $model['dest'][2]->destination_address;?>" style="width:150px;" >
								<input type="text" id="Truckloadrequest_0_price" name="Truckloadrequest[price][2]"  value="<?php echo $model['dest'][2]->price;?>" style="width:50px;" >
                                <div style="display:none" id="Truckloadrequest_title_em_" class="help-inline error"></div></div>
                                    
                        </div></div>

                     <!-- <div class="span4">  <div class="control-group"><label for="Truckloadrequest_price" class="control-label required">Price 1<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" id="Truckloadrequest_price" name="Truckloadrequest[price]" data-placement="right" data-toggle="tooltip" rel="tooltip" value="<?php echo $model['price'];?>" data-original-price="" price="">
                                <div style="display:none" id="Truckloadrequest_price_em_" class="help-inline error"></div></div>
                                    
                        </div></div> -->
                    
                    
                    

                        <div class="span4">   <?php
                        $list = CHtml::listData(Goodstype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_goods_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_goods_type', $list);
                            ?> </div>

                        <div class="span4">   <?php
                        $list = CHtml::listData(Trucktype::model()->findAll(array('condition' => 'status=1 order by title asc')), 'id_truck_type', 'title');
                        echo $form->dropDownListRow($model['ltr'], 'id_truck_type', $list);
                        ?> </div>


                        <div class="span4">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'tracking_available', array('1' => 'Yes', '0' => 'No'));
                            ?></div><div class="span4">  <?php
                            echo $form->dropDownListRow($model['ltr'], 'insurance_available', array('1' => 'Yes', '0' => 'No'));
                            ?></div>
                        <div class="span4">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'date_available', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );?></div>
                     <div class="span4">   <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'expected_return', array('class' => 'datetimepicker', 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );?></div>
                        <!--<div class="span4">   <?php
                           // echo $form->textFieldRow(
                             //       $model['ltr'], 'make', array( 'rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                        //    );?></div>-->
                   <div class="span4">
                                            <div class="control-group"><label for="Truckloadrequest_make_year" class="control-label">Make Year/Month</label><div class="controls"><?php echo CHtml::dropdownlist('Truckloadrequest[make_year]', $model['ltr']->make_year, Library::getMakeYears(),array('style'=>'width:auto'));echo CHtml::dropdownlist('Truckloadrequest[make_month]', $model['ltr']->make_month, Library::getMakeMonths(),array('style'=>'width:auto'));?>
											
											</div></div>                                        </div>
                        <!--<div class="span4">
                                            <?php
                            //echo $form->dropDownListRow($model['ltr'], 'make_year', Library::getMakeYears());
                            ?>
                                        </div>
					<div class="span4">
                                            <?php
                            //echo $form->dropDownListRow($model['ltr'], 'make_month', Library::getMakeMonths());
                            ?>
                                        </div>-->
                        
                    <div class="span4">  <?php
                            echo $form->textFieldRow(
                                    $model['ltr'], 'truck_reg_no', array('rel' => 'tooltip', 'data-toggle' => "tooltip", 'data-placement' => "right",)
                            );
                            ?></div>
                    
                        <div class="span4">  <?php echo $form->textAreaRow(
           $model['ltr'],'add_info',array('rows' => 3, 'cols' => 30)
       ); ?></div>    <div class="span4" > 
                    <?php echo $form->radioButtonListRow($model['ltr'], 'status',
                            array('1' => 'Enable', '0' => 'Disable'));
                    ?></div>
                      <!--  <div class="span4">  <?php
                          //  echo $form->dropDownListRow($model['ltr'], 'status', Library::getLTRStatuses());
                            ?></div>-->
    
    <?php if (!$model['ltr']->approved) { ?>
                            <div class="span5">  <?php
        echo $form->radioButtonListRow($model['ltr'], 'approved', array('1' => 'Yes', '0' => 'No'));
        ?></div>
    <?php }
} ?></div>
            </fieldset>
        </div>

<?php if ($action == 'create') {echo $this->renderPartial('_form_truck_block', array('form'=>$form,'model'=>$model),true);}?>
    </div></div>


<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
    <script type='text/javascript'>
   
        var input = document.getElementById('Truckloadrequest_source_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
		
        var input = document.getElementById('Truckloadrequest_0_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
		
		var input = document.getElementById('Truckloadrequest_1_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
		
		var input = document.getElementById('Truckloadrequest_2_destination_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
         </script>

<script src="<?php echo Yii::app()->params['config']['admin_url']; ?>js/datetime/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$('.datetimepicker').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        format: 'Y-m-d H:i',
                        startDate: '<?php echo date('Y/m/d'); ?>'	//'2015/09/01'
                    });

function fncustomertype(id){
    if(id.value=='1'){
        $('#unregistered').hide();
        $('#registered').show();
        $('#Customer_fullname').val('empty');
        $('#Customer_mobile').val('0000000000');
        $('#Truckloadrequest_title').val('');
        
    }else{
        $('#registered').hide();
        $('#unregistered').show();
        $('#Customer_fullname').val('');
        $('#Customer_mobile').val('');
        $('#Truckloadrequest_title').val('empty');
    }
}
</script>
