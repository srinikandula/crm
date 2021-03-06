<?php $this->widget('ext.Flashmessage.Flashmessage'); ?> 
<div class="row-fluid">
 <div class="tab-pane active" id="Personal Details">
	<div class="span6">
     <fieldset class="portlet" >
	   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line1').slideToggle();">
		 <div class="span11">Customer Details </div>
		 <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
	   <div class="clearfix"></div>
	   </div>
 <div class="portlet-content" id="hide_box_line1">
   
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Full Name </label><div class="controls"><b><?php echo $model['0']->customer_fullname;?></b></div></div></div>
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Type </label><div class="controls"><b><?php echo Library::getCustomerType($model['0']->customer_type);?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Mobile </label><div class="controls"><b><?php echo $model['0']->customer_mobile;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->customer_email;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->customer_company;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->customer_address;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->customer_city;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->customer_state);?></b></div></div></div>
 
       </div>
   </fieldset>


  </div>
  
     <div class="span6">
     <fieldset class="portlet" >
	   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line2').slideToggle();">
		 <div class="span11">Order Details </div>
		 <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
	   <div class="clearfix"></div>
	   </div>
 <div class="portlet-content" id="hide_box_line2">
   
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Source </label><div class="controls"><b><?php echo $model['0']->source_address;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Destination </label><div class="controls"><b><?php echo $model['0']->destination_address;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Ordered </label><div class="controls"><b><?php echo $model['0']->date_ordered;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Full Name </label><div class="controls"><b><?php echo $model['0']->orderperson_fullname;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Mobile </label><div class="controls"><b><?php echo $model['0']->orderperson_mobile;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Email </label><div class="controls"><b><?php echo $model['0']->orderperson_email;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Company </label><div class="controls"><b><?php echo $model['0']->orderperson_company;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Address </label><div class="controls"><b><?php echo $model['0']->orderperson_address;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">City </label><div class="controls"><b><?php echo $model['0']->orderperson_city;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">State </label><div class="controls"><b><?php echo Library::getState($model['0']->orderperson_state);?></b></div></div></div>
           
           
       </div>
   </fieldset>


  </div>
     
     <div class="span6" style="margin-left:0px;">
     <fieldset class="portlet" >
	   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line3').slideToggle();">
		 <div class="span11"><?php echo $model['0']->booking_type=='T'?'Truck Booking':'Load Booking';?></div>
		 <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button></div>
	   <div class="clearfix"></div>
	   </div>
 <div class="portlet-content" id="hide_box_line3">
            
     <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Reg No </label><div class="controls"><!--<input type="text" name="truck_reg_no" style="height:30px; margin-left:0px;" class="span10" value="<?php //echo $model['0']->truck_reg_no;?>"><?php  //if($model['0']->booking_type=='L'){ echo ' (Assigned)';}?>-->
<?php 
  $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
      'attribute'=>'truck_reg_no',
        'model'=>$model['0'],
        'sourceUrl'=>array('truck/AutocompleteTruck'),
        'name'=>'truck_reg_no',
        'options'=>array(
          'minLength'=>'2',
        ),
        'htmlOptions'=>array(
          'value'=>$model['0']->truck_reg_no,
            'size'=>45,
          'maxlength'=>45,
        ),
  )); ?>                 
                 <button class="btn btn-info" type="button" id="modify_truck">Apply</button><span id="truck_reg_no_alert"></span></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Truck Type </label><div class="controls"><b><?php echo $model['0']->truck_type;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Tracking Available </label><div class="controls"><b><?php echo $model['0']->tracking_available==1?'Yes':'No';?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Insurance Available </label><div class="controls"><b><?php echo $model['0']->insurance_available==1?'Yes':'No';?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Date Available </label><div class="controls"><b><?php echo $model['0']->date_available;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Actual Amount </label><div class="controls"><b><?php echo $model['0']->amount;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Payable Amount </label><div class="controls"><b><?php echo $model['0']->payable_amount;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Goods Type </label><div class="controls"><b><?php echo $model['0']->goods_type;?></b></div></div></div>
           
           <div class="span12"><div class="control-group"><label for="Customer_fullname" class="control-label required">Load Type </label><div class="controls"><b><?php echo $model['0']->load_type;?></b></div></div></div>
           
       </div>
   </fieldset>


  </div>
     <div class="span6">
                <fieldset class="portlet " >
                   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line5').slideToggle();">
                        <div class="span11">Additional Charges</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line5">
                    <table class="table uploading-status" border='0'>
                            <thead>
                                    <tr>
                                       <th>Comment</th>
                                       <th>Amount</th>
                                    </tr>
                            </thead>
                            <tbody id="grand_total">
                                <tr>
                                       <td>Original Amount</td>
                                       <td><?php echo $model['0']->amount;?></td>
                                </tr>
                                <?php 
								$grandTotal=$model['0']->amount;
								foreach(Orderamount::model()->findAll(array('condition'=>'id_order='.(int)$_GET['id'],'order'=>'id_order_amount desc')) as $orderamount):?>
                                <tr>
                                       <td><?php echo $orderamount->comment;?></td>
                                       <td><?php echo $orderamount->amount_prefix.$orderamount->amount;?></td>
                                </tr>
                                <?php 
								if($orderamount->amount_prefix=='+'){
									$grandTotal+=$orderamount->amount;
								}else{
									$grandTotal-=$orderamount->amount;
								}
								endforeach;?>
								<tr><td>Grand Total</td><td><?php echo $grandTotal;?></td></tr>
                            </tbody>
                    <tfoot>
                    </tfoot>
                    </table> 
                        </div>
                    <div class="portlet-content" id="hide_box_line3">
					<table>
						<tr><td>Comment</td><td>Add/Sub</td><td>Amount</td></tr>
						<tr>
							<td><input type="text" name="Orderamount[comment]"></td>
							<td><select name="Orderamount[amount_prefix]" ><option value="+">+</option><option value="-">-</option></select></td>
							<td><input type="text" name="Orderamount[amount]"></td>
                                                        <td><?php echo CHtml::ajaxButton("Submit",
                              $this->createUrl('order/updateAmount',array('id'=>(int)$_GET['id'])), 
                              array('dataType' => 'text','type'=>'post','success'=>'function(data){
	res=data.split("---");
	if(res[0]==1){
	//alert("in if");	
            location=window.location.href;
	}else{
            //alert("in else");
		$("#grand_total").html(res[1]);
	}

	//$("#uploadBox").dialog("open");
}',/*,'update' => '#grand_total'*/
                                  ),array('confirm'=>'Are you sure??'));
                        ?></td>
						</tr>
					</table>
                    </div>
                </fieldset>
            </div>
                    
                    <div class="span6">
                <fieldset class="portlet " >
                   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                        <div class="span11">Payments Received</div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line7">
                    <table class="table uploading-status" border='0'>
                            <thead>
                                    <tr>
                                       <th>Comment</th>
                                       <th>Amount</th>
                                    </tr>
                            </thead>
                            <tbody id="amount_recived">
                                <tr>
                                       <td>Payable Amount</td>
                                       <td><?php echo $model['0']->payable_amount;?></td>
                                </tr>
                                <?php
								$payableAmount=$model['0']->payable_amount;
								foreach(Orderpaymenthistory::model()->findAll(array('condition'=>'id_order='.(int)$_GET['id'],'order'=>'id_order_payment_history desc')) as $orderpayment):?>
                                <tr>
                                       <td><?php echo $orderpayment->comment;?></td>
                                       <td><?php echo $orderpayment->amount;?></td>
                                </tr>
                                <?php 
									$payableAmount-=$orderpayment->amount;
								endforeach;?>
								<tr><td>Pending Amount</td><td><?php echo $payableAmount;?></td></tr>
                            </tbody>
                    <tfoot>
                    </tfoot>
                    </table> 
                      </div>  
                    <div class="portlet-content" id="hide_box_line3">
					<table>
						<tr><td>Comment</td><td>Pay Amount</td></tr>
						<tr>
							<td><input type="text" name="Orderpaymenthistory[comment]"></td>
							
							<td><input type="text" name="Orderpaymenthistory[amount]"></td>
                                                        <td><?php echo CHtml::ajaxButton("Submit",
                              $this->createUrl('order/payAmount',array('id'=>(int)$_GET['id'])), 
                              array('dataType' => 'text','type'=>'post','success'=>'function(data){
	res=data.split("---");
	if(res[0]==1){
	//alert("in if");	
            location=window.location.href;
	}else{
            //alert("in else");
		$("#amount_recived").html(res[1]);
	}

	//$("#uploadBox").dialog("open");
}',/*,'update' => '#amount_recived'*/),array('confirm'=>'Are you sure??'));
                        ?></td>
						</tr>
					</table>
                    </div>
                </fieldset>
            </div>
     
                 <div class="span6"  style="margin-left:0px;">
                <fieldset class="portlet " >
                   <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line4').slideToggle();">
                        <div class="span11">Update Status </div>
                        <div class="span1 Dev-arrow"><button class="btn btn-info arrow_main" type="button"></button> </div>
                        <div class="clearfix"></div>	
                    </div>
                    <div class="portlet-content" id="hide_box_line4">
                    <table class="table uploading-status" border='0'>
                            <thead>
                                    <tr>
                                       <th>Date</th>
                                       <th>Status</th>
                                       <th>Comment</th>
                                       <th>Notify</th>
                                    </tr>
                            </thead>
                            <tbody id="order_status">
                                <?php foreach(Orderhistory::model()->findAll(array('condition'=>'id_order='.(int)$_GET['id'],'order'=>'date_created desc')) as $history):?>
                                <tr>
                                       <td><?php echo $history->date_created;?></td>
                                       <td><?php echo $history->title;?></td>
                                       <td><?php echo $history->message;?></td>
                                       <td><?php echo $history->notified_by_customer=='1'?'Yes':'No';?></td>
                                </tr>
                                <?php endforeach;?>    
                            </tbody>
                    <tfoot>
                    </tfoot>
                    </table> 
                        
                    <div class="portlet-content" id="hide_box_line3">
                        <div class="span8"> <?php
                        echo $form->dropDownListRow($model['oh'],'title',
                                CHtml::listData(Orderstatus::model()->findAll(array('select'=>'concat(id_order_status,"#",title) as id_order_status,title','condition'=>'status=1')),'id_order_status', 'title')); ?>
</div>
                         <div class="span3 left_nodiv"> <?php  echo $form->checkboxRow(
                                $model['oh'], 'notified_by_customer',
                                array('rel' => 'tooltip', 'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );?>
</div>
                         <div class="span8"> <?php  echo $form->textAreaRow(
                                $model['oh'], 'message',
                                array('rel' => 'tooltip',  'data-toggle' => "tooltip",
                            'data-placement' => "right",)
                        );?>
</div>
                         <div class="span3"> <?php echo CHtml::ajaxButton ("Update Status",
                              $this->createUrl('order/updateStatus',array('id'=>(int)$_GET['id'])), 
                              array('dataType' => 'text','type'=>'post','update' => '#order_status'),array('confirm'=>'Are you sure you want to update the status', 'class'=>'btn btn-info maind_top_p'));
                        ?></div>
                    </div>
                </fieldset>
            </div>   
    </div>

  </div>
  
  </div>
<script type="text/javascript">
$('#modify_truck').live('click', function() {
	$.ajax({
		url: '<?echo $this->createUrl("order/modifyTruck");?>',
		dataType: 'json',
                type: 'post',
		data: 'id_order=<?php echo (int)$_GET['id'];?>&truck_reg_no=' + $('#truck_reg_no').val(),    
            	beforeSend: function() {
		},
		complete: function() {
		},
		success: function(json) {
                        
                        if (json['success']) {
                            $('#truck_reg_no_alert').html('Done!!');
			}else{
                            $('#truck_reg_no_alert').html('Failed!!');
                        }
		}
	});
});
</script>    