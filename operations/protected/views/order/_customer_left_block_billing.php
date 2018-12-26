
        <div class="span6" style="margin-left:0px">
            <fieldset class="portlet ">
                <div class="portlet-decoration arrow-minus" onclick=" $('#hide_box_line7').slideToggle();">
                    <div class="span11">Truck Owner Billing (<span style="color:green"><?php echo $model['0']->truck_attachment_policy_title;?></span>)</div>
                    <div class="clearfix"></div>	
                </div>
                <?php //if($model['0']->id_truck_attachment_policy==1){?>
                <div style="min-height:250px;max-height: 200px;overflow: auto;">
                <div class="portlet-content" id="hide_box_line7">
					<table class="table">
							<tr><td>Truck Owner</td><td>Owner No</td><td>Driver Name</td><td>Driver No</td><td>Truck No</td></tr>
							<tr><td><?php echo $model['0']->customer_fullname;?></td><td><?php echo $model['0']->customer_mobile;?></td><td><?php echo $model['0']->driver_name;?></td><td><?php echo $model['0']->driver_mobile;?></td><td><?php echo $model['0']->truck_reg_no;?></td></tr>
					</table>
                    <table class="table uploading-status" border='0'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Prefix</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="truck_owner_billing">
                            <?php
                            $billing_total=0;
							//and id_customer='.$model['0']->id_customer.'
                            foreach (Orderbillinghistory::model()->findAll(array("select"=>"*,DATE_FORMAT(date_created,'%d-%m-%Y %h:%i %p') as date_created",'condition' =>'customer_type="T"  and id_order=' . (int) $_GET['id'], 'order' => 'id_order_billing_history desc')) as $orderpayment):
                                $billing_total=$orderpayment->amount_prefix=="+"?$billing_total+$orderpayment->amount:$billing_total-$orderpayment->amount;
                                ?>
                                <tr>
                                    <td><?php echo substr($orderpayment->date_created,0,10); ?></td>
                                    <td><?php echo $orderpayment->comment; ?></td>
                                    <td><?php echo $orderpayment->amount_prefix; ?></td>
                                    <td><?php echo number_format($orderpayment->amount, 2); ?></td>
                                    <td id="row_<?php echo $orderpayment->id_order_billing_history;?>_billing"><a onclick="fnDelete('<?php echo $orderpayment->id_order_billing_history;?>','billing')"><i class="delete-icon-block"></i></a></td>
                                </tr>
                                <?php
                            endforeach;
                            $_SESSION[$_GET['id']]['truck_billing_total']=$billing_total;
                            ?>
                                <tr><td>&nbsp;</td><td>&nbsp;</td> <td> Grand Total </td><td><b><?php echo number_format($billing_total, 2); ?></b></td><td>&nbsp;</td></tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table> 
                </div>  
                </div>
                <div class="portlet-content" id="hide_box_line3">
                    <table>
                        <tr>
                            <!-- <td><?php echo Chtml::dropdownlist('Orderbillinghistory[status_T]','',Library::getBookingComments(),array('prompt'=>'Comment')); ?></td>
                            <td><input type="text" name="Orderbillinghistory[amount_T]"  placeholder="Amount" ></td>
                            <td><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'T','cid'=>$model['0']->id_customer,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
							<td>Date pod Received:<input onchange="fnUpdateDetails(this)" type="text" name="Order[date_pod_received]" id="date_pod_received" placeholder="Date Pod Received" value="<?php echo $model['0']->date_pod_received;?>"></td> -->

							<td width="100px"><?php echo Chtml::dropdownlist('Orderbillinghistory[status_T]','',Library::getBookingComments(),array('prompt'=>'Comment')); ?></td>
                            <!--<td><?php //echo Chtml::dropdownlist('Orderbillinghistory[prefix_T]','',array("+"=>"+","-"=>"-"),array('prompt'=>'Prefix')); ?></td>-->

                            <td width="70px"><input type="text" name="Orderbillinghistory[amount_T]"  placeholder="Amount" ></td>
                            <td width="50px"><?php
                                echo CHtml::ajaxButton("Submit", $this->createUrl('order/payAmount', array('type'=>'T','cid'=>$model['0']->id_customer,'id' => (int) $_GET['id'])), array('dataType' => 'json', 'type' => 'post', 'success' => 'function(data){
	if(data["status"]==1){
	    location=window.location.href;
	}else{
        alert(data["error"]);
        }
}', /* ,'update' => '#amount_recived' */), array('confirm' => 'Are you sure??'));
                                ?></td>
								<td width="50px">Date pod Received:<input onchange="fnUpdateDetails(this)" type="text" name="Order[date_pod_received]" id="date_pod_received" placeholder="Date Pod Received" value="<?php echo $model['0']->date_pod_received;?>"></td>
								<td width="50px">
								<div>
								Pod Front<input  type="file" name="Order[pod_img_front]" id="pod_img_front" placeholder="Pod File Front" value="<?php echo $model['0']->pod_img_front;?>">
								<input type="hidden" id="prev_pod_img_front" value="<?php echo $model['0']->pod_img_front;?>">
								<span id="span_pod_img_front"><?php if($model['0']->pod_img_front!=""){?><a href="<?php echo Library::getOrderUploadLink().$model['0']->pod_img_front?>"  target="_blank">Front Pod</a> | <a href='<?php echo $this->createUrl('order/downloadPod');?>?file=<?php echo $model['0']->pod_img_front?>' target='_blank'>Download</a><?php }?></span>
								</div>
									<div>Pod Back<input  type="file" name="Order[pod_img_back]" id="pod_img_back" placeholder="Pod File Back" value="<?php echo $model['0']->pod_img_back;?>">
								<input type="hidden" id="prev_pod_img_back" value="<?php echo $model['0']->pod_img_back;?>">
								<span id="span_pod_img_back"><?php if($model['0']->pod_img_back!=""){?><a href="<?php echo Library::getOrderUploadLink().$model['0']->pod_img_back?>" target="_blank">Back Pod</a> | <a href='<?php echo $this->createUrl('order/downloadPod');?>?file=<?php echo $model['0']->pod_img_back?>' target='_blank'>Download</a><?php }?></span>
								</div></td>
                        </tr>
                    </table>
                </div><?php //}else{ 
                //echo '<pre>';print_r($model['joinTable']);echo '</pre>';

                //}?>
                <?php if($model['0']->id_truck_attachment_policy!=1){?>
                <table>
                <tr style="border-top:1pt solid black;border-bottom:1pt solid black"><td><?php echo $model['0']->truck_attachment_policy_title;?></td><?php if($model['0']->id_truck_attachment_policy==2){ echo '<td>Per Km Charge:'.$model['0']->truck_attachment_policy_price_per_km.'</td><td>Min Km Guarenteed:'.$model['0']->truck_attachment_policy_min_kms.'</td>'; } else  if($model['0']->id_truck_attachment_policy==3){ echo '<td>Flat Payment:'.$model['0']->truck_attachment_policy_flat_rate.'</td><td>Diesel Price Per Km:'.$model['0']->truck_attachment_policy_diesel_price_per_km.'</td>'; } ?></tr>
                        <tr>
                            <td>Start Meter:<input onblur="fnUpdateDetails(this)" type="text" name="Order[truck_start_meter_reading]" id="truck_start_meter_reading" placeholder="Start Meter Reading" value="<?php echo $model['0']->truck_start_meter_reading;?>"></td>
                            <td>
                Stop Meter:<input onblur="fnUpdateDetails(this)" type="text" name="Order[truck_stop_meter_reading]"  id="truck_stop_meter_reading"   placeholder="Stop Meter Reading" value="<?php echo $model['0']->truck_stop_meter_reading;?>" ></td>
                            <td>
                Current Diesel Price:<input onblur="fnUpdateDetails(this)" type="text" name="Order[current_diesel_price]"  id="truck_current_diesel_price"   placeholder="Current Diesel Price" value="<?php echo $model['0']->truck_current_diesel_price;?>" ></td>
                            <td>Mileage:<br/><?php echo $model['0']->truck_mileage;?></td>
                            <td>Trip Expense:<b><?php echo $this->calcTripExpense(array('startKm'=>$model['0']->truck_start_meter_reading,'stopKm'=>$model['0']->truck_stop_meter_reading,'mileage'=>$model['0']->truck_mileage,'currentDieselPrice'=>$model['0']->truck_current_diesel_price));?></b></td>
                        </tr></table>
                    <?php }?>
            </fieldset>
        </div>

<script>
    function fnUpdateDetails(obj){
    //alert($(obj).attr('id'));
    var field=$(obj).attr('id');
    var value=$(obj).attr('value');
    var id="<?php echo (int)$_GET['id']?>";
    $.ajax({url: "<?php echo $this->createUrl('order/updateFields');?>",
            type:'POST',
            data:{field:field, val:value,id:id}, 
            success: function(result){
                if(result["status"]){
                    $('#'+field).css('border','1px solid green');
                }
            },
            dataType:'json'});
    }

	$('#pod_img_front,#pod_img_back').change(function() {
		//alert(this.id);
		var field=this.id;
		var date_pod_received=$("#date_pod_received").val();
		if(date_pod_received!=""){
			var file_data = $('#'+field).prop('files')[0];   
			var form_data = new FormData();                  
			form_data.append('file', file_data);
			form_data.append('id_order', '<?php echo (int)$_GET["id"]?>');
			form_data.append('prev_pod_img', $("#prev_"+field).val());
			form_data.append('type', field);
			//alert(form_data);                             
			$.ajax({
						url: '<?php echo $this->createUrl("order/uploadpod");?>', // point to server-side PHP script 
						dataType: 'json',  // what to expect back from the PHP script, if anything
						cache: false,
						contentType: false,
						processData: false,
						data: form_data,                         
						type: 'post',
						success: function(data){
							//alert(data['file']+ " " + data['status'] + " " + data['type']); // display response from the PHP script, if any
							if(data['status']){
								var text=field=="pod_img_front"?"Front Pod":"Back Pod";
							$("#span_"+field).html("<a href='<?php echo Library::getOrderUploadLink();?>"+data['file']+"' target='_blank'>"+text+"</a> | <a href='<?php echo $this->createUrl('order/downloadPod');?>?file="+data['file']+"' target='_blank'>Download</a>");
							$("#prev_"+field).val(data['file']);
							alert("Upload Successful!!");
							}else{
								alert("Image upload failed please try again!!");
							}

						}
			 });
		}else{
			alert("Date Pod received is empty?");
		}
	});
</script>